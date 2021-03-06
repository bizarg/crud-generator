# crud-generator

config/app.php
```PHP
[
    'providers' => [
        Bizarg\Crud\CrudServiceProvider::class,
    ]
];
```
template property example CrudGenerate:

     'Api', - {{namespace}} 
     'CrudGenerate', - {{modelName}} 
     'crudGenerate', - {{modelNameSingularLowerCaseFirst}}
     'crudGenerates', - {{modelNamePluralLowerCase}} 
     'crud_generates', - {{modelNamePluralLowerCaseUnderscore}} 
     'CrudGenerates', - {{modelNamePlural}} 
     'crud-generates', - {{modelNamePluralLowerCaseHyphen}} 
     'Api\Domain', - {{domainPath}} 
     'Api\Application', - {{commandPath}}
     'Api\Http\Resources', - {{resourcePath}}
     'Api\Http\Requests', - {{requestPath}}
     'Api\Http\Controllers', - {{controllerPath}} 
     'Api\Infrastructure\Eloquent', - {{repositoryPath}} 
     'Tests\Feature', - {{testPath}} 
     'Eloquent', - {{repositoryFilePrefix}}

config:
```PHP
<?php
return [
    'namespace' => 'app',
    'paths' => [
        'domain' => 'Domain',
        'command' => 'Application',
        'repository' => 'Infrastructure/Eloquent',
        'controller' => 'Http/Controllers/Api',
        'request' => 'Http/Requests',
        'resource' => 'Http/Resources',
        'migrate' => 'database/migrations',
        'test' => 'tests/Feature',
        'doc' => 'api-doc',
        'stub' => 'resources/stubs',
        'route' => base_path('routes/api.php'),
    ],
    'repositoryFilePrefix' => 'Eloquent',
    'routePrefix' => 'api',
    'generate' => [
        'collection' => true
    ],
    'declare' => true,
    'custom' => [
        [
            'path' => 'api/%s/template',
            'filename' => '%sCustomTemplate',
            'stub' => 'CustomTemplate'
        ],
    ]
];
```             

    php artisan vendor:publish --tag=crud-generator-config
    php artisan vendor:publish --tag=crud-generator-stubs
    php artisan crud:generate UserProjectTest
