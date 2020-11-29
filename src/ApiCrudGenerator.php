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
     * @return void
     */
    public function handle(): void
    {
        $name = $this->argument('name');

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

        file_put_contents(
            base_path('routes/api.php'),
            '//Route::apiResource(\'' . Str::plural(strtolower($name)) . "', '{$name}Controller');",
            FILE_APPEND
        );
    }

    /**
     * @param string $name
     */
    protected function model(string $name)
    {
        $path = config('crud-generator.path.dir', 'api') . "/Domain/{$name}";

        $this->makePath($path);

        $this->put($path . "/{$name}.php", 'Model');
    }

    /**
     * @param string $name
     */
    protected function filter(string $name)
    {
        $path = config('crud-generator.path.dir', 'api') . "/Domain/{$name}";

        $this->makePath($path);

        $this->put($path . "/{$name}Filter.php", 'Filter');
    }

    /**
     * @param string $name
     */
    protected function notFound(string $name)
    {
        $path = config('crud-generator.path.dir', 'api') . "/Domain/{$name}";

        $this->makePath($path);

        $this->put($path . "/{$name}NotFound.php", 'NotFound');
    }

    /**
     * @param string $name
     */
    protected function repository(string $name)
    {
        $path = config('crud-generator.path.dir', 'api') . "/Domain/{$name}";

        $this->makePath($path);

        $this->put($path . "/{$name}Repository.php", 'Repository');
    }

    /**
     * @param string $name
     */
    protected function delete(string $name)
    {
        $path = config('crud-generator.path.dir', 'api') . "/Application/{$name}/Delete{$name}";

        $this->makePath($path);

        $this->put($path . "/Delete{$name}.php", 'Delete');
    }

    /**
     * @param string $name
     */
    protected function deleteHandler(string $name)
    {
        $path = config('crud-generator.path.dir', 'api') . "/Application/{$name}/Delete{$name}";

        $this->makePath($path);

        $this->put($path . "/Delete{$name}Handler.php", 'DeleteHandler');
    }

    /**
     * @param string $name
     */
    protected function register(string $name)
    {
        $path = config('crud-generator.path.dir', 'api') . "/Application/{$name}/Store{$name}";

        $this->makePath($path);

        $this->put($path . "/Store{$name}.php", 'Register');
    }

    /**
     * @param string $name
     */
    protected function registerHandler(string $name)
    {
        $path = config('crud-generator.path.dir', 'api') . "/Application/{$name}/Store{$name}";

        $this->makePath($path);

        $this->put($path . "/Store{$name}Handler.php", 'RegisterHandler');
    }

    /**
     * @param string $name
     */
    protected function update(string $name)
    {
        $path = config('crud-generator.path.dir', 'api') . "/Application/{$name}/Update{$name}";

        $this->makePath($path);

        $this->put($path . "/Update{$name}.php", 'Update');
    }

    /**
     * @param string $name
     */
    protected function updateHandler(string $name)
    {
        $path = config('crud-generator.path.dir', 'api') . "/Application/{$name}/Update{$name}";

        $this->makePath($path);

        $this->put($path . "/Update{$name}Handler.php", 'UpdateHandler');
    }

    /**
     * @param string $name
     */
    protected function getList(string $name)
    {
        $path = config('crud-generator.path.dir', 'api') . "/Application/{$name}/Get{$name}List";

        $this->makePath($path);

        $this->put($path . "/Get{$name}List.php", 'GetList');
    }

    /**
     * @param string $name
     */
    protected function getListHandler(string $name)
    {
        $path = config('crud-generator.path.dir', 'api') . "/Application/{$name}/Get{$name}List";

        $this->makePath($path);

        $this->put($path . "/Get{$name}ListHandler.php", 'GetListHandler');
    }

    /**
     * @param string $name
     */
    protected function eloquentRepository(string $name)
    {
        $path = config('crud-generator.path.dir', 'api') . "/Infrastructure/Eloquent";

        $this->makePath($path);

        $this->put($path . "/Eloquent{$name}Repository.php", 'EloquentRepository');
    }

    /**
     * @param string $name
     */
    protected function controller(string $name)
    {
        $path = config('crud-generator.path.dir', 'api') . "/Http/Controllers";

        $this->makePath($path);

        $this->put($path . "/{$name}Controller.php", 'Controller');
    }

    /**
     * @param string $name
     */
    protected function request(string $name)
    {
        $path = config('crud-generator.path.dir', 'api') . "/Http/Requests/{$name}";

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
        $path = 'tests/Feature';

        $this->makePath($path);

        $this->put($path . "/{$name}Test.php", 'Test');
    }

    /**
     * @param string $name
     */
    protected function resource(string $name)
    {
        $path = config('crud-generator.path.dir', 'api') . "/Http/Resources/{$name}";

        $this->makePath($path);

        $this->put($path . "/{$name}Resource.php", 'Resource');
    }

    /**
     * @param string $name
     */
    protected function resourceCollection(string $name)
    {
        $path = config('crud-generator.path.dir', 'api') . "/Http/Resources/{$name}";

        $this->makePath($path);

        $this->put($path . "/{$name}ResourceCollection.php", 'ResourceCollection');
    }

    /**
     * @param string $name
     * @return void
     */
    protected function migrate(string $name): void
    {
        $path = "database/migrations";

        $this->makePath($path);

        $filename = Carbon::now()->format('Y_m_d_His')
            . '_create_' . StringHelper::toUnderscore(Str::plural($name)) . '_table.php';

        $this->put($path . "/" . $filename, 'Migrate');
    }

    /**
     * @param string $name
     */
    protected function apiDoc(string $name)
    {
        $path = "api-doc";

        $this->makePath($path);

        $this->put($path . "/api-" . strtolower(StringHelper::toHyphen(Str::plural($name))) . ".php", 'ApiDoc');
    }

    /**
     * @param string $path
     */
    protected function makePath(string $path)
    {
        if (!file_exists($path = base_path($path))) {
            mkdir($path, 0777, true);
        }
    }

    /**
     * @param $type
     * @return false|string
     */
    protected function getStub($type)
    {
        return file_get_contents(
            rtrim(config('crud-generator.path.stubs', __DIR__ . '/../stubs'), '/') . "/$type.stub"
        );
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
    protected function variableTemplate(string $template)
    {
        $name = $this->argument('name');

        return str_replace(
            [
                '{{modelName}}',
                '{{modelNameSingularLowerCaseFirst}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNamePluralLowerCaseUnderscore}}',
                '{{modelNamePlural}}',
                '{{modelNamePluralLowerCaseHyphen}}',
            ],
            [
                $name,
                StringHelper::camelCase($name),
                StringHelper::camelCase(Str::plural($name)),
                StringHelper::toUnderscore(Str::plural($name)),
                Str::plural($name),
                StringHelper::toHyphen(Str::plural($name)),
            ],
            $this->getStub($template)
        );
    }
}
