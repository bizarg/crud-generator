<?php

namespace Bizarg\Crud;

use Api\Infrastructure\UseCase\StringCase as StringHelper;
//use Bizarg\StringHelper\StringHelper;
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
        $name = StringHelper::upperCaseCamelCase($this->argument('name'));

        $this->model($name);
        $this->filter($name);
        $this->notFound($name);
        $this->repository($name);
        $this->delete($name);
        $this->deleteHandler($name);
        $this->register($name);
        $this->registerHandler($name);
        $this->update($name);
        $this->updateHandler($name);
        $this->getList($name);
        $this->getListHandler($name);
        $this->controller($name);
        $this->request($name);
        $this->eloquentRepository($name);
        $this->resource($name);
        $this->resourceCollection($name);
        $this->test($name);
        $this->migrate($name);
        $this->apiDoc($name);

        if ($this->config->needGenerateCollection()) {
            $this->collection($name);
        }

        $this->route($name);
    }

    /**
     * @param string $name
     */
    protected function model(string $name)
    {
        $path = $this->config->domainPath() . $name;

        $this->makePath($path);

        $this->put($path . "/{$name}.php", 'Model');
    }

    /**
     * @param string $name
     */
    protected function filter(string $name)
    {
        $path = $this->config->domainPath() . $name;

        $this->makePath($path);

        $this->put($path . "/{$name}Filter.php", 'Filter');
    }

    /**
     * @param string $name
     */
    protected function notFound(string $name)
    {
        $path = $this->config->domainPath() . $name;

        $this->makePath($path);

        $this->put($path . "/{$name}NotFound.php", 'NotFound');
    }

    /**
     * @param string $name
     */
    protected function repository(string $name)
    {
        $path = $this->config->domainPath() . $name;

        $this->makePath($path);

        $this->put($path . "/{$name}Repository.php", 'Repository');
    }

    /**
     * @param string $name
     */
    protected function delete(string $name)
    {
        $path = $this->config->commandPath() . "{$name}/Delete{$name}";

        $this->makePath($path);

        $this->put($path . "/Delete{$name}.php", 'Delete');
    }

    /**
     * @param string $name
     */
    protected function deleteHandler(string $name)
    {
        $path = $this->config->commandPath() . "{$name}/Delete{$name}";

        $this->makePath($path);

        $this->put($path . "/Delete{$name}Handler.php", 'DeleteHandler');
    }

    /**
     * @param string $name
     */
    protected function register(string $name)
    {
        $path = $this->config->commandPath() . "{$name}/Store{$name}";

        $this->makePath($path);

        $this->put($path . "/Store{$name}.php", 'Register');
    }

    /**
     * @param string $name
     */
    protected function registerHandler(string $name)
    {
        $path = $this->config->commandPath() . "{$name}/Store{$name}";

        $this->makePath($path);

        $this->put($path . "/Store{$name}Handler.php", 'RegisterHandler');
    }

    /**
     * @param string $name
     */
    protected function update(string $name)
    {
        $path = $this->config->commandPath() . "{$name}/Update{$name}";

        $this->makePath($path);

        $this->put($path . "/Update{$name}.php", 'Update');
    }

    /**
     * @param string $name
     */
    protected function updateHandler(string $name)
    {
        $path = $this->config->commandPath() . "{$name}/Update{$name}";

        $this->makePath($path);

        $this->put($path . "/Update{$name}Handler.php", 'UpdateHandler');
    }

    /**
     * @param string $name
     */
    protected function getList(string $name)
    {
        $path = $this->config->commandPath() . "{$name}/Get{$name}List";

        $this->makePath($path);

        $this->put($path . "/Get{$name}List.php", 'GetList');
    }

    /**
     * @param string $name
     */
    protected function getListHandler(string $name)
    {
        $path = $this->config->commandPath() . "{$name}/Get{$name}List";

        $this->makePath($path);

        $this->put($path . "/Get{$name}ListHandler.php", 'GetListHandler');
    }

    /**
     * @param string $name
     */
    protected function eloquentRepository(string $name)
    {
        $path = $this->config->repositoryPath();

        $this->makePath($path);

        $this->put($path . "{$this->config->repositoryFilePrefix()}{$name}Repository.php", 'EloquentRepository');
    }

    /**
     * @param string $name
     */
    protected function controller(string $name)
    {
        $path = $this->config->controllerPath();

        $this->makePath($path);

        $this->put($path . "{$name}Controller.php", 'Controller');
    }

    /**
     * @param string $name
     */
    protected function request(string $name)
    {
        $path = $this->config->requestPath() . $name;

        $this->makePath($path);

        $this->put($path . "/List{$name}Request.php", 'ListRequest');
        $this->put($path . "/Store{$name}Request.php", 'StoreRequest');
        $this->put($path . "/Update{$name}Request.php", 'UpdateRequest');
    }

    /**
     * @param string $name
     */
    protected function test(string $name)
    {
        $path = $this->config->testPath();

        $this->makePath($path);

        $this->put($path . "{$name}Test.php", 'Test');
    }

    /**
     * @param string $name
     */
    protected function resource(string $name)
    {
        $path = $this->config->resourcePath() . $name;

        $this->makePath($path);

        $this->put($path . "/{$name}Resource.php", 'Resource');
    }

    /**
     * @param string $name
     */
    protected function resourceCollection(string $name)
    {
        $path = $this->config->resourcePath() . $name;

        $this->makePath($path);

        $this->put($path . "/{$name}ResourceCollection.php", 'ResourceCollection');
    }

    /**
     * @param string $name
     * @return void
     */
    protected function migrate(string $name): void
    {
        $path = $this->config->migratePath();

        $this->makePath($path);

        $filename = Carbon::now()->format('Y_m_d_His')
            . '_create_' . StringHelper::toUnderscore(Str::plural($name)) . '_table.php';

        $this->put($path . $filename, 'Migrate');
    }

    /**
     * @param string $name
     */
    protected function apiDoc(string $name)
    {
        $path = $this->config->docPath();

        $this->makePath($path);

        $this->put($path . "api-" . strtolower(StringHelper::toHyphen(Str::plural($name))) . ".php", 'ApiDoc');
    }

    /**
     * @param string $name
     */
    protected function collection(string $name)
    {
        $path = $this->config->docPath() . 'collections';

        $this->makePath($path);

        $this->put($path . "/{$name}.json", 'Collection');
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
        $name = $this->argument('name');

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
            [
                ucfirst($this->config->namespace()),
                $name,
                StringHelper::camelCase($name),
                StringHelper::camelCase(Str::plural($name)),
                StringHelper::toUnderscore(Str::plural($name)),
                Str::plural($name),
                StringHelper::toHyphen(Str::plural($name)),
                $this->preparePath($this->config->domainPath()),
                $this->preparePath($this->config->commandPath()),
                $this->preparePath($this->config->resourcePath()),
                $this->preparePath($this->config->requestPath()),
                $this->preparePath($this->config->controllerPath()),
                $this->preparePath($this->config->repositoryPath()),
                $this->preparePath($this->config->testPath()),
                $this->config->repositoryFilePrefix()
            ],
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
     * @param string $name
     */
    protected function route(string $name)
    {
        $as = StringHelper::camelCase($name);
        $prefix = StringHelper::toHyphen(Str::plural($name));

        $route = "
        //  Route::group(['prefix' => '{$prefix}', 'as' => '{$as}.'], function () {
        //      Route::get('/', '{$name}Controller@index')->name('index');
        //      Route::post('/', '{$name}Controller@store')->name('store');
        //      Route::put('{{$as}}', '{$name}Controller@update')->name('update')->where('{$as}', '[0-9]+');
        //      Route::get('{{$as}}', '{$name}Controller@show')->name('show')->where('{$as}', '[0-9]+');
        //      Route::delete('{{$as}}', '{$name}Controller@destroy')->name('delete')->where('{$as}', '[0-9]+');
        //  });";

        file_put_contents(
            base_path('routes/api.php'),
            $route,
            FILE_APPEND
        );
    }
}
