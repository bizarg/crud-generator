# crud-generator

config/app.php
```PHP
[
    'providers' => [
        Bizarg\Crud\CrudServiceProvider::class,
    ]
];
```
template property example UserProjectTest:

    {{modelName}} - UserProjectTest
    {{modelNameSingularLowerCaseFirst}} - userProjectTest
    {{modelNamePluralLowerCase}} - userProjectTests
    {{modelNamePluralLowerCaseUnderscore}} - user_project_tests
    {{modelNamePlural}} - UserProjectTests
    {{modelNamePluralLowerCaseHyphen}} -  user-project-tests

config:
```PHP
<?php
    return [
        'path' => [
            'dir' => 'api',
            'stubs' => null, //resources/stubs
        ]
    ];     
```             

php artisan vendor:bublish --tag=crud-generator-config
php artisan vendor:bublish --tag=crud-generator-stubs
php artisan crud:generate UserProjectTest
