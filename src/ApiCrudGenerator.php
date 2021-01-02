<?php

namespace Bizarg\Crud;

use Bizarg\StringHelper\StringHelper;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

/**
 * Class ApiCrudGenerator
 * @package Bizarg\Crud
 */
class ApiCrudGenerator extends Command
{
    /**
     * @var string
     */
    protected $signature = 'crud:generate {name : Class (singular) for example User}';
    /**
     * @var string
     */
    protected $description = 'Create CRUD operations';
    /**
     * @var Config
     */
    private Config $config;
    /**
     * @var string|null
     */
    private $entity = null;
    /**
     * @var array|null
     */
    private $variables = null;

    /**
     * ApiCrudGenerator constructor.
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        parent::__construct();
        $this->config = $config;
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        $this->entity = StringHelper::upperCaseCamelCase($this->argument('name'));

        $this->domainFiles();
        $this->deleteCommand();
        $this->registerCommand();
        $this->updateCommand();
        $this->getListCommand();
        $this->controller();
        $this->request();
        $this->repository();
        $this->resource();
        $this->test();
        $this->migrate();
        $this->apiDoc();

        if ($this->config->needGenerateCollection()) {
            $this->collection();
        }

        $this->route();
    }

    /**
     * @return void
     */
    protected function domainFiles(): void
    {
        $path = $this->config->domainPath() . $this->entity;

        $this->makePath($path);

        $this->put($path . "/{$this->entity}.php", 'Model');
        $this->put($path . "/{$this->entity}Filter.php", 'Filter');
        $this->put($path . "/{$this->entity}NotFound.php", 'NotFound');
        $this->put($path . "/{$this->entity}Repository.php", 'RepositoryInterface');
    }

    /**
     * @return void
     */
    protected function deleteCommand()
    {
        $path = $this->config->commandPath() . "{$this->entity}/Delete{$this->entity}";

        $this->makePath($path);

        $this->put($path . "/Delete{$this->entity}.php", 'Delete');
        $this->put($path . "/Delete{$this->entity}Handler.php", 'DeleteHandler');
    }

    /**
     * @return void
     */
    protected function registerCommand()
    {
        $path = $this->config->commandPath() . "{$this->entity}/Store{$this->entity}";

        $this->makePath($path);

        $this->put($path . "/Store{$this->entity}.php", 'Register');
        $this->put($path . "/Store{$this->entity}Handler.php", 'RegisterHandler');
    }

    /**
     * @return void
     */
    protected function updateCommand()
    {
        $path = $this->config->commandPath() . "{$this->entity}/Update{$this->entity}";

        $this->makePath($path);

        $this->put($path . "/Update{$this->entity}.php", 'Update');
        $this->put($path . "/Update{$this->entity}Handler.php", 'UpdateHandler');
    }

    /**
     * @return void
     */
    protected function getListCommand()
    {
        $path = $this->config->commandPath() . "{$this->entity}/Get{$this->entity}List";

        $this->makePath($path);

        $this->put($path . "/Get{$this->entity}List.php", 'GetList');
        $this->put($path . "/Get{$this->entity}ListHandler.php", 'GetListHandler');
    }

    /**
     * @return void
     */
    protected function repository()
    {
        $path = $this->config->repositoryPath();

        $this->makePath($path);

        $this->put($path . "{$this->config->repositoryFilePrefix()}{$this->entity}Repository.php", 'Repository');
    }

    /**
     * @return void
     */
    protected function controller()
    {
        $path = $this->config->controllerPath();

        $this->makePath($path);

        $this->put($path . "{$this->entity}Controller.php", 'Controller');
    }

    /**
     * @return void
     */
    protected function request()
    {
        $path = $this->config->requestPath() . $this->entity;

        $this->makePath($path);

        $this->put($path . "/List{$this->entity}Request.php", 'ListRequest');
        $this->put($path . "/Store{$this->entity}Request.php", 'StoreRequest');
        $this->put($path . "/Update{$this->entity}Request.php", 'UpdateRequest');
    }

    /**
     * @return void
     */
    protected function test()
    {
        $path = $this->config->testPath();

        $this->makePath($path);

        $this->put($path . "{$this->entity}Test.php", 'Test');
    }

    /**
     * @return void
     */
    protected function resource()
    {
        $path = $this->config->resourcePath() . $this->entity;

        $this->makePath($path);

        $this->put($path . "/{$this->entity}Resource.php", 'Resource');
        $this->put($path . "/{$this->entity}ResourceCollection.php", 'ResourceCollection');
    }

    /**
     * @return void
     */
    protected function migrate(): void
    {
        $path = $this->config->migratePath();

        $this->makePath($path);

        $filename = Carbon::now()->format('Y_m_d_His')
            . '_create_' . StringHelper::toUnderscore(Str::plural($this->entity)) . '_table.php';

        $this->put($path . $filename, 'Migrate');
    }

    /**
     * @return void
     */
    protected function apiDoc()
    {
        $path = $this->config->docPath();

        $this->makePath($path);

        $this->put($path . "api-" . strtolower(StringHelper::toHyphen(Str::plural($this->entity))) . ".php", 'ApiDoc');
    }

    /**
     * @return void
     */
    protected function collection()
    {
        $path = $this->config->docPath() . 'collections';

        $this->makePath($path);

        $this->put($path . "/{$this->entity}.json", 'Collection');
    }

    /**
     * @param string $path
     * @return string
     */
    protected function makePath(string $path): string
    {
        if (!file_exists($path = base_path($path))) {
            mkdir($path, 0777, true);
        }

        return $path;
    }

    /**
     * @param $type
     * @return false|string
     */
    protected function getStub($type)
    {
        return file_get_contents($this->config->stubPath() . "$type.stub");
    }

    /**
     * @param string $path
     * @param string $template
     * @return void
     */
    protected function put(string $path, string $template): void
    {
        file_put_contents(base_path($path), $this->variableTemplate($template));
    }

    /**
     * @param string $template
     * @return string|string[]
     */
    protected function variableTemplate(string $template): string
    {
        $string = '<?php' . PHP_EOL;

        if ($this->config->needDeclare()) {
            $string .= PHP_EOL . 'declare(strict_types=1);' . PHP_EOL;
        }

        if ($template == 'ApiDoc' || $template == 'Collection') {
            $string = '';
        }

        $string .= str_replace(
            [
                '{{namespace}}',
                '{{modelName}}',
                '{{modelNameSingularLowerCaseFirst}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNamePluralLowerCaseUnderscore}}',
                '{{modelNamePlural}}',
                '{{modelNamePluralLowerCaseHyphen}}',
                '{{domainPath}}',
                '{{commandPath}}',
                '{{resourcePath}}',
                '{{requestPath}}',
                '{{controllerPath}}',
                '{{repositoryPath}}',
                '{{testPath}}',
                '{{repositoryFilePrefix}}',
            ],
            $this->variables(),
            $this->getStub($template)
        );

        return $string;
    }

    /**
     * @param string $path
     * @return string
     */
    public function preparePath($path): string
    {
        return ucfirst(str_replace('/', '\\', trim($path, '/')));
    }

    /**
     * @return void
     */
    protected function route()
    {
        $as = StringHelper::camelCase($this->entity);
        $prefix = StringHelper::toHyphen(Str::plural($this->entity));

        $route = "
        //  Route::group(['prefix' => '{$prefix}', 'as' => '{$as}.'], function () {
        //      Route::get('/', '{$this->entity}Controller@index')->name('index');
        //      Route::post('/', '{$this->entity}Controller@store')->name('store');
        //      Route::put('{{$as}}', '{$this->entity}Controller@update')->name('update')->where('{$as}', '[0-9]+');
        //      Route::get('{{$as}}', '{$this->entity}Controller@show')->name('show')->where('{$as}', '[0-9]+');
        //      Route::delete('{{$as}}', '{$this->entity}Controller@destroy')->name('delete')->where('{$as}', '[0-9]+');
        //  });";

        file_put_contents(
            base_path('routes/api.php'),
            $route,
            FILE_APPEND
        );
    }

    /**
     * @return array
     */
    private function variables(): array
    {
        if (is_array($this->variables)) {
            return $this->variables;
        }

        $this->variables = [
            ucfirst($this->config->namespace()),
            $this->entity,
            StringHelper::camelCase($this->entity),
            StringHelper::camelCase(Str::plural($this->entity)),
            StringHelper::toUnderscore(Str::plural($this->entity)),
            Str::plural($this->entity),
            StringHelper::toHyphen(Str::plural($this->entity)),
            $this->preparePath($this->config->domainPath()),
            $this->preparePath($this->config->commandPath()),
            $this->preparePath($this->config->resourcePath()),
            $this->preparePath($this->config->requestPath()),
            $this->preparePath($this->config->controllerPath()),
            $this->preparePath($this->config->repositoryPath()),
            $this->preparePath($this->config->testPath()),
            $this->config->repositoryFilePrefix(),
        ];

        return $this->variables;
    }
}
