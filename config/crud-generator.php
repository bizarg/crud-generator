<?php

return [
    'paths' => [
        'stub' => null,
    ],
    'templates' => [
        [
            'path' => 'app/Http/Controllers/Api',
            'filename' => '{{modelName}}Controller.php',
            'stub' => 'Controller'
        ],
        [
            'path' => 'app/Domain/{{modelName}}',
            'filename' => '{{modelName}}Filter.php',
            'stub' => 'Filter'
        ],
        [
            'path' => 'app/Domain/{{modelName}}',
            'filename' => '{{modelName}}.php',
            'stub' => 'Model'
        ],
        [
            'path' => 'app/Domain/{{modelName}}',
            'filename' => '{{modelName}}NotFound.php',
            'stub' => 'NotFound'
        ],
        [
            'path' => 'app/Domain/{{modelName}}',
            'filename' => '{{modelName}}NotFound.php',
            'stub' => 'NotFound'
        ],
        [
            'path' => 'app/Domain/{{modelName}}',
            'filename' => '{{modelName}}RepositoryInterface.php',
            'stub' => 'RepositoryInterface'
        ],
        [
            'path' => 'app/Application/{{modelName}}/Delete{{modelName}}',
            'filename' => 'Delete{{modelName}}.php',
            'stub' => 'Delete'
        ],
        [
            'path' => 'app/Application/{{modelName}}/Delete{{modelName}}',
            'filename' => 'Delete{{modelName}}Handler.php',
            'stub' => 'DeleteHandler'
        ],
        [
            'path' => 'app/Application/{{modelName}}/Get{{modelName}}List',
            'filename' => 'Get{{modelName}}List.php',
            'stub' => 'GetList'
        ],
        [
            'path' => 'app/Application/{{modelName}}/Get{{modelName}}List',
            'filename' => 'Get{{modelName}}ListHandler.php',
            'stub' => 'GetListHandler'
        ],
        [
            'path' => 'app/Application/{{modelName}}/Store{{modelName}}',
            'filename' => 'Store{{modelName}}.php',
            'stub' => 'Store'
        ],
        [
            'path' => 'app/Application/{{modelName}}/Store{{modelName}}',
            'filename' => 'Store{{modelName}}Handler.php',
            'stub' => 'StoreHandler'
        ],
        [
            'path' => 'app/Application/{{modelName}}/Update{{modelName}}',
            'filename' => 'Update{{modelName}}.php',
            'stub' => 'Update'
        ],
        [
            'path' => 'app/Application/{{modelName}}/Update{{modelName}}',
            'filename' => 'Update{{modelName}}Handler.php',
            'stub' => 'UpdateHandler'
        ],
        [
            'path' => 'app/Http/Requests/{{modelName}}',
            'filename' => 'List{{modelName}}Request.php',
            'stub' => 'ListRequest'
        ],
        [
            'path' => 'app/Http/Requests/{{modelName}}',
            'filename' => 'Store{{modelName}}Request.php',
            'stub' => 'StoreRequest'
        ],
        [
            'path' => 'app/Http/Requests/{{modelName}}',
            'filename' => 'Update{{modelName}}Request.php',
            'stub' => 'UpdateRequest'
        ],
        [
            'path' => 'api-doc/collections/',
            'filename' => '{{modelNamePluralLowerCaseHyphen}}.php',
            'stub' => 'ApiDoc'
        ],
        [
            'path' => 'database/migrations/',
            'filename' => '{{migrationName}}.php',
            'stub' => 'Migration'
        ],
        [
            'path' => 'tests/Feature/',
            'filename' => '{{modelName}}Test.php',
            'stub' => 'Test'
        ],
        [
            'path' => 'app/Http/Resources/{{modelName}}',
            'filename' => '{{modelName}}Resource.php',
            'stub' => 'Resource'
        ],
        [
            'path' => 'app/Http/Resources/{{modelName}}',
            'filename' => '{{modelName}}ResourceCollection.php',
            'stub' => 'ResourceCollection'
        ],
        [
            'path' => 'app/Infrastructure/Repositories',
            'filename' => '{{modelName}}Repository.php',
            'stub' => 'Repository'
        ],
    ]
];
