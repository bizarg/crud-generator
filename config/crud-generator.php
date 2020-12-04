<?php

return [
    'namespace' => 'api',
    'paths' => [
        'domain' => 'Domain',
        'command' => 'Application',
        'repository' => 'Infrastructure/Eloquent',
        'controller' => 'Http/Controllers',
        'request' => 'Http/Requests',
        'resource' => 'Http/Resources',
        'migrate' => 'database/migrations',
        'test' => 'tests/Feature',
        'doc' => 'api-doc',
        'stub' => null,
    ],
    'repositoryFilePrefix' => 'Eloquent',
    'generate' => [
        'collection' => true
    ],
    'declare' => true
];
