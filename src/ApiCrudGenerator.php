<?php

namespace Bizarg\Crud;

use Bizarg\StringHelper\StringHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ApiCrudGenerator extends Command
{
    protected $signature = 'crud:generate {name : Class (singular) for example User}';

    protected $description = 'Create CRUD operations';

    private ?string $entity = null;

    private ?array $variables = null;

    public function __construct(
        private Config $config
    ) {
        parent::__construct();
    }

    public function handle(): void
    {
        $this->entity = StringHelper::upperCaseCamelCase($this->argument('name'));

        $this->generateTemplates();
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
        file_put_contents(base_path($path), $this->replaceVariables($this->getStub($template)));
    }

    private function replaceVariables(string $content): array|string
    {
        return str_replace(
            [
                '{{modelName}}',
                '{{modelNameSingularLowerCaseFirst}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNamePluralLowerCaseUnderscore}}',
                '{{modelNamePlural}}',
                '{{modelNamePluralLowerCaseHyphen}}',
                '{{migrationName}}'
            ],
            $this->variables(),
            $content
        );
    }

    private function variables(): array
    {
        if (is_array($this->variables)) {
            return $this->variables;
        }

        $this->variables = [
            $this->entity,
            StringHelper::camelCase($this->entity),
            StringHelper::camelCase(Str::plural($this->entity)),
            StringHelper::toUnderscore(Str::plural($this->entity)),
            Str::plural($this->entity),
            StringHelper::toHyphen(Str::plural($this->entity)),
            date('Y_m_d_His') . '_create_' . StringHelper::toUnderscore(Str::plural($this->entity)) . '_table'
        ];

        return $this->variables;
    }

    private function generateTemplates(): void
    {
        foreach ($this->config->templates() as $template) {
            $this->makePath($path = sprintf($this->replaceVariables($template->path()), $this->entity));
            $this->put(
                $path . "/" . sprintf($this->replaceVariables($template->filename()), $this->entity) . ".php",
                $template->stub()
            );
        }
    }
}
