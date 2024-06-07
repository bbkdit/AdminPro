<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ModuleGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:gen {name} {type?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new module with various components';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $moduleName = $this->argument('name');

        $modulePath = base_path('modules/' . $moduleName);
        $this->makeDirectory($modulePath);

        $modelPath = $modulePath . '/Models';
        $this->makeDirectory($modelPath);

        $controllerPath = $modulePath . '/Http/Controllers';
        $this->makeDirectory($controllerPath);

        $apiControllerPath = $controllerPath . '/Api';
        $this->makeDirectory($apiControllerPath);

        $repositoryPath = $modulePath . '/Repositories';
        $this->makeDirectory($repositoryPath);

        $viewPath = resource_path('views/' . $moduleName);
        $this->makeDirectory($viewPath);

        // Generate the model
        $this->generateModel($moduleName, $modelPath);

        // Generate the API controller
        $this->generateApiController($moduleName, $apiControllerPath);

        // Generate the controller
        $this->generateController($moduleName, $controllerPath);

        // Generate the repository
        $this->generateRepository($moduleName, $repositoryPath);

        // Generate the migration
        $this->generateMigration($moduleName, $modelPath);

        // Generate the views
        $this->generateViews($moduleName, $viewPath);

        $this->info('Module generated successfully.');
    }

    private function makeDirectory($path)
    {
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
    }

    private function generateModel($moduleName, $modelPath)
    {
        $modelName = studly_case($moduleName);
        $modelFilePath = $modelPath . '/' . $modelName . '.php';

        $modelStub = file_get_contents(base_path('stubs/model.stub'));
        $modelStub = str_replace('{{ modelName }}', $modelName, $modelStub);

        file_put_contents($modelFilePath, $modelStub);
    }

    private function generateApiController($moduleName, $apiControllerPath)
    {
        $apiControllerName = studly_case($moduleName) . 'ApiController';
        $apiControllerFilePath = $apiControllerPath . '/' . $apiControllerName . '.php';

        $apiControllerStub = file_get_contents(base_path('stubs/api-controller.stub'));
        $apiControllerStub = str_replace('{{ apiControllerName }}', $apiControllerName, $apiControllerStub);

        file_put_contents($apiControllerFilePath, $apiControllerStub);
    }

    private function generateController($moduleName, $controllerPath)
    {
        $controllerName = studly_case($moduleName) . 'Controller';
        $controllerFilePath = $controllerPath . '/' . $controllerName . '.php';

        $controllerStub = file_get_contents(base_path('stubs/controller.stub'));
        $controllerStub = str_replace('{{ controllerName }}', $controllerName, $controllerStub);

        file_put_contents($controllerFilePath, $controllerStub);
    }

    private function generateRepository($moduleName, $repositoryPath)
    {
        $repositoryName = studly_case($moduleName) . 'Repository';
        $repositoryFilePath = $repositoryPath . '/' . $repositoryName . '.php';

        $repositoryStub = file_get_contents(base_path('stubs/repository.stub'));
        $repositoryStub = str_replace('{{ repositoryName }}', $repositoryName, $repositoryStub);

        file_put_contents($repositoryFilePath, $repositoryStub);
    }

    private function generateMigration($moduleName, $modelPath)
    {
        $migrationName = studly_case($moduleName) . '_table';
        $migrationFilePath = database_path('migrations/' . date('Y_m_d_His') . '_create_' . $migrationName . '_table.php');

        $migrationStub = file_get_contents(base_path('stubs/migration.stub'));
        $migrationStub = str_replace('{{ migrationName }}', $migrationName, $migrationStub);

        file_put_contents($migrationFilePath, $migrationStub);
    }

    private function generateViews($moduleName, $viewPath)
{
    $views = [
        'index' => 'index.blade.php',
        'create' => 'create.blade.php',
        'edit' => 'edit.blade.php',
        'show' => 'show.blade.php',
    ];

    foreach ($views as $view => $viewFile) {
        $viewFilePath = $viewPath . '/' . $viewFile;
        $viewStub = file_get_contents(base_path('stubs/view.stub'));
        $viewStub = str_replace('{{ viewName }}', studly_case($view), $viewStub);

        file_put_contents($viewFilePath, $viewStub);
    }
}
}
