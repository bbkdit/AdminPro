<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Menu;
use Carbon\Carbon;

class GenerateModule extends Command
{
    protected $signature = 'generate:module {name}';
    protected $description = 'Generate a complete module with model, controllers, repository, views, and migration';
    protected $api = 'Api';


    public function __construct()
    {
        parent::__construct();
    }

    protected function getStub($path)
    {
        return file_get_contents(resource_path("stubs/$path"));
    }

    public function handle()
    {
        $name = $this->argument('name');
        $this->generateModel($name);
        $this->loadModuleToDatabase($name);
        $this->generateApiController($name);
        $this->generateController($name);
        $this->generateRepository($name);
        $this->generateViews($name);
        $this->generateMigration($name);
        $this->info('Module generated successfully!');
    }

    public function loadModuleToDatabase($name) {
        // $name
        $menu = new Menu();
        $menu->parent_id = 0;
        $menu->sorted_id = Menu::where('parent_id', 0)->count()+1;
        $menu->title = $name;
        $menu->icon = 'fa fa-cubes';
        $menu->uri = strtolower($name);
        $menu->save();
    }

    protected function generateModel($name)
    {
        $modelTemplate = str_replace(
            ['{{modelName}}'],
            [$name],
            $this->getStub('Model.stub')
        );

        File::put(app_path("/Models/{$name}.php"), $modelTemplate);

        // $this->call('make:model', ['name' => $name, '--migration' => true]);
    }

    public function generateApiController($name) {
        // $ValidationArr = $this->getCoreValidation($name);

        $controllerTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}',
                '{{validationArr}}'
            ],
            [
                $name,
                strtolower(Str::plural($name)),
                strtolower($name),
                '',
            ],
            file_get_contents(resource_path("stubs/".$this->api."Controller.stub"))
        );


        if(!file_exists($path = app_path("/Http/Controllers/".$this->api)))
            mkdir($path, 0777, true);

        file_put_contents(app_path("/Http/Controllers/".$this->api."/{$name}Controller.php"), $controllerTemplate);

        $newRouteControllerName = $name."Controller";
        $apiRouteFilePath = base_path('routes/api.php');
        $routeTemplate = str_replace(
            ['<?php',],
            [
               "<?php\nuse App\Http\Controllers\\".$this->api."\\{$name}Controller;"
            ],
            file_get_contents($apiRouteFilePath)
        );
        if(!file_exists($apiRouteFilePath))
            mkdir($apiRouteFilePath, 0777, true);

        file_put_contents($apiRouteFilePath, $routeTemplate);
        \File::append($apiRouteFilePath, 'Route::resource(\'' .strtolower($name) . "', {$newRouteControllerName}::class);".PHP_EOL);

        // \File::append(base_path('routes/api.php'), "use App\Http\Controllers\\".$this->api."\\{$name}Controller;".PHP_EOL);
        // \File::append(base_path('routes/api.php'), 'Route::resource(\'' . Str::plural(strtolower($name)) . "', {$newRouteControllerName}::class);".PHP_EOL);
        //we can use it for alias controller name in route file
        // \File::append(base_path('routes/api.php'), "use App\Http\Controllers\\".$this->api."\\{$name}Controller as ".$newRouteControllerName.";".PHP_EOL);
        // \File::append(base_path('routes/api.php'), 'Route::resource(\'' . Str::plural(strtolower($name)) . "', {$newRouteControllerName}::class);".PHP_EOL);
    }

    protected function generateApiControllerold($name)
    {
        $controllerName = "{$name}ApiController";
        $this->call('make:controller', ['name' => "Api/{$controllerName}", '--api' => true]);
    }

    public function generateController($name) {
        // $ValidationArr = $this->getCoreValidation($name);

        $controllerTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}',
                '{{validationArr}}'
            ],
            [
                $name,
                strtolower(Str::plural($name)),
                strtolower($name),
                // $ValidationArr,
                ''

            ],
            file_get_contents(resource_path("stubs/Controller.stub"))
        );


        if(!file_exists($path = app_path("/Http/Controllers/")))
            mkdir($path, 0777, true);

        file_put_contents(app_path("/Http/Controllers/{$name}Controller.php"), $controllerTemplate);

        $newRouteControllerName = $name."Controller";
        $routeFilePath = base_path('routes/web.php');
        $routeTemplate = str_replace(
            ['<?php',],
            [
               "<?php\nuse App\Http\Controllers\\{$name}Controller;"
            ],
            file_get_contents($routeFilePath)
        );
        if(!file_exists($routeFilePath))
            mkdir($routeFilePath, 0777, true);

        file_put_contents($routeFilePath, $routeTemplate);
        \File::append($routeFilePath, 'Route::resource(\'' .strtolower($name). "', {$newRouteControllerName}::class);".PHP_EOL);

        // \File::append(base_path('routes/api.php'), "use App\Http\Controllers\\".$this->api."\\{$name}Controller;".PHP_EOL);
        // \File::append(base_path('routes/api.php'), 'Route::resource(\'' . Str::plural(strtolower($name)) . "', {$newRouteControllerName}::class);".PHP_EOL);
        //we can use it for alias controller name in route file
        // \File::append(base_path('routes/api.php'), "use App\Http\Controllers\\".$this->api."\\{$name}Controller as ".$newRouteControllerName.";".PHP_EOL);
        // \File::append(base_path('routes/api.php'), 'Route::resource(\'' . Str::plural(strtolower($name)) . "', {$newRouteControllerName}::class);".PHP_EOL);
    }

    protected function generateControllerold($name)
    {
        $controllerName = "{$name}Controller";
        $this->call('make:controller', ['name' => $controllerName, '--resource' => true]);
    }

    public function generateRepository($name) {

        $template = str_replace(
            ['{{modelName}}'],
            [ucwords($name)],
            $this->getStub('Repository.stub')
        );

        if(!file_exists($path = app_path("/Repositories")))
            mkdir($path, 0777, true);

        file_put_contents(app_path("/Repositories/{$name}Repository.php"), $template);
    }

    protected function generateRepositoryold($name)
    {
        $repositoryName = "{$name}Repository";
        $path = app_path("Repositories/{$repositoryName}.php");

        if (!File::isDirectory(app_path('Repositories'))) {
            File::makeDirectory(app_path('Repositories'), 0755, true);
        }

        $stub = $this->getRepositoryStub();
        $stub = str_replace('{{modelName}}', $name, $stub);
        $stub = str_replace('{{repositoryName}}', $repositoryName, $stub);

        File::put($path, $stub);
    }

    protected function generateViews($name)
    {

        $viewPath = resource_path("views/{$name}");
        if (!File::isDirectory($viewPath)) {
            File::makeDirectory($viewPath, 0755, true);
        }

        $views = ['index', 'create', 'edit', 'show'];
        
        foreach ($views as $view) {

            $viewTemplate = str_replace(
                ['{{NameUppderCase}}'],
                [ucfirst($view)],
                $this->getStub("scaffold/".strtolower($view).".stub"),
            );
            
            File::put("{$viewPath}/{$view}.blade.php", $viewTemplate);
        }
    }

    protected function generateMigration($name)
    {
        // Migrations are automatically generated with the model
    }

    public function getRepositoryStub($name) {

        $template = str_replace(
            ['{{modelName}}'],
            [ucwords($name)],
            $this->getStub('Repository.stub')
        );

        if(!file_exists($path = app_path("/Repositories")))
            mkdir($path, 0777, true);

        file_put_contents(app_path("/Repositories/{$name}Repository.php"), $template);
    }  
}
