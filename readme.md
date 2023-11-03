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

     'CrudGenerate' - {{modelName}} 
     'crudGenerate' - {{modelNameSingularLowerCaseFirst}}
     'crudGenerates' - {{modelNamePluralLowerCase}} 
     'crud_generates' - {{modelNamePluralLowerCaseUnderscore}} 
     'CrudGenerates' - {{modelNamePlural}} 
     'crud-generates' - {{modelNamePluralLowerCaseHyphen}}
     '2023_11_02_154414_create_crud_generates_table' - {{migrationName}}


config:
```PHP
<?php

return [
    'paths' => [
        'stub' => base_path('resources/stubs'),
    ],
    'generate' => [
        'collection' => true,
        'migration' => true
    ],
    'templates' => [
        [
            'path' => 'app/Http/Controllers/Api',
            'filename' => '{{modelName}}Controller',
            'stub' => 'Controller'
        ],
        ...
    ]
];
```             

    php artisan vendor:publish --tag=crud-generator-config
    php artisan vendor:publish --tag=crud-generator-stubs
    php artisan crud:generate UserProjectTest
