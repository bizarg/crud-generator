<?php

namespace Bizarg\Crud;

use Bizarg\StringHelper\StringHelper;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ApiCrudGenerator extends Command
{
    protected $signature = 'crud:generate {name : Class (singular) for example User}';

    protected $description = 'Create CRUD operations';

    private Config $config;

    private ?string $entity = null;

    private ?array $variables = null;

    public function __construct(Config $config)
    {
        parent::__construct();
        $this->config = $config;
    }

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
        $this->customTemplate();
    }

    protected function domainFiles(): void
    {
        if ($this->config->domainPath()) {
            $path = $this->config->domainPath() . $this->entity;

            $this->makePath($path);

            $this->put($path . "/{$this->entity}.php", 'Model');
            $this->put($path . "/{$this->entity}Filter.php", 'Filter');
            $this->put($path . "/{$this->entity}NotFound.php", 'NotFound');
            $this->put($path . "/{$this->entity}RepositoryInterface.php", 'RepositoryInterface');
        }
    }

    protected function deleteCommand(): void
    {
        if ($this->config->commandPath()) {
            $path = $this->config->commandPath() . "{$this->entity}/Delete{$this->entity}";

            $this->makePath($path);

            $this->put($path . "/Delete{$this->entity}.php", 'Delete');
            $this->put($path . "/Delete{$this->entity}Handler.php", 'DeleteHandler');
        }
    }

    protected function registerCommand(): void
    {
        if ($this->config->commandPath()) {
            $path = $this->config->commandPath() . "{$this->entity}/Store{$this->entity}";

            $this->makePath($path);

            $this->put($path . "/Store{$this->entity}.php", 'Register');
            $this->put($path . "/Store{$this->entity}Handler.php", 'RegisterHandler');
        }
    }

    protected function updateCommand(): void
    {
        if ($this->config->commandPath()) {
            $path = $this->config->commandPath() . "{$this->entity}/Update{$this->entity}";

            $this->makePath($path);

            $this->put($path . "/Update{$this->entity}.php", 'Update');
            $this->put($path . "/Update{$this->entity}Handler.php", 'UpdateHandler');
        }
    }

    protected function getListCommand(): void
    {
        if ($this->config->commandPath()) {
            $path = $this->config->commandPath() . "{$this->entity}/Get{$this->entity}List";

            $this->makePath($path);

            $this->put($path . "/Get{$this->entity}List.php", 'GetList');
            $this->put($path . "/Get{$this->entity}ListHandler.php", 'GetListHandler');
        }
    }

    protected function repository(): void
    {
        if ($path = $this->config->repositoryPath()) {
            $this->makePath($path);

            $this->put($path . "{$this->config->repositoryFilePrefix()}{$this->entity}Repository.php", 'Repository');
        }
    }

    protected function controller(): void
    {
        if ($path = $this->config->controllerPath()) {
            $this->makePath($path);

            $this->put($path . "{$this->entity}Controller.php", 'Controller');
        }
    }

    protected function request(): void
    {
        if ($this->config->requestPath()) {
            $path = $this->config->requestPath() . $this->entity;

            $this->makePath($path);

            $this->put($path . "/List{$this->entity}Request.php", 'ListRequest');
            $this->put($path . "/Store{$this->entity}Request.php", 'StoreRequest');
            $this->put($path . "/Update{$this->entity}Request.php", 'UpdateRequest');
        }
    }

    protected function test(): void
    {
        if ($path = $this->config->testPath()) {
            $this->makePath($path);

            $this->put($path . "{$this->entity}Test.php", 'Test');
        }
    }

    protected function resource(): void
    {
        if ($this->config->requestPath()) {
            $path = $this->config->resourcePath() . $this->entity;

            $this->makePath($path);

            $this->put($path . "/{$this->entity}Resource.php", 'Resource');
            $this->put($path . "/{$this->entity}ResourceCollection.php", 'ResourceCollection');
        }
    }

    protected function migrate(): void
    {
        if ($path = $this->config->migratePath()) {
            $this->makePath($path);

            $filename = Carbon::now()->format('Y_m_d_His')
                . '_create_' . StringHelper::toUnderscore(Str::plural($this->entity)) . '_table.php';

            $this->put($path . $filename, 'Migrate');
        }
    }

    protected function apiDoc(): void
    {
        if ($path = $this->config->docPath()) {
            $this->makePath($path);

            $this->put($path . "api-" . strtolower(StringHelper::toHyphen(Str::plural($this->entity))) . ".php", 'ApiDoc');
        }
    }

    protected function collection(): void
    {
        if ($this->config->docPath()) {
            $path = $this->config->docPath() . 'collections';

            $this->makePath($path);

            $this->put($path . "/{$this->entity}.json", 'Collection');
        }
    }

    protected function makePath(string $path): string
    {
        if (!file_exists($path = base_path($path))) {
            mkdir($path, 0777, true);
        }

        return $path;
    }

    protected function getStub(string $type): bool|string
    {
        return file_get_contents($this->config->stubPath() . "$type.stub");
    }

    protected function put(string $path, string $template): void
    {
        file_put_contents(base_path($path), $this->variableTemplate($template));
    }

    protected function variableTemplate(string $template): string
    {
        $string = '<?php' . PHP_EOL;

        if ($this->config->needDeclare()) {
            $string .= PHP_EOL . 'declare(strict_types=1);' . PHP_EOL;
        }

        if ($template == 'ApiDoc' || $template == 'Collection') {
            $string = '';
        }
        $string .= $this->replaceVariables($this->getStub($template));

        return $string;
    }

    private function replaceVariables(string $content): array|string
    {
        return str_replace(
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
            $content
        );
    }

    public function preparePath(?string $path): string
    {
        return ucfirst(str_replace('/', '\\', trim($path, '/')));
    }

    protected function route(): void
    {
        if (is_null($this->config->routePath())) {
            return;
        }

        $entity = StringHelper::camelCase($this->entity);
        $prefix = StringHelper::toHyphen(Str::plural($this->entity));

        $route = "
        //  Route::group(['prefix' => '{$prefix}', 'as' => '{$prefix}.'], function () {
        //      Route::get('/', '{$this->entity}Controller@index')->name('index');
        //      Route::post('/', '{$this->entity}Controller@store')->name('store');
        //      Route::put('{{$entity}}', '{$this->entity}Controller@update')->name('update')->where('{$entity}', '[0-9]+');
        //      Route::get('{{$entity}}', '{$this->entity}Controller@show')->name('show')->where('{$entity}', '[0-9]+');
        //      Route::delete('{{$entity}}', '{$this->entity}Controller@destroy')->name('delete')->where('{$entity}', '[0-9]+');
        //  });";

        file_put_contents(
            $this->config->routePath(),
            $route,
            FILE_APPEND
        );
    }

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

    private function customTemplate(): void
    {
        foreach ($this->config->custom() as $template) {
            $this->makePath($path = sprintf($this->replaceVariables($template->path()), $this->entity));
            $this->put($path . "/" . sprintf($this->replaceVariables($template->filename()), $this->entity) . ".php", $template->stub());
        }
    }
}
