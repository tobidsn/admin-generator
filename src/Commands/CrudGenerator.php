<?php

namespace Tobidsn\CrudGenerator\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use DB;

class CrudGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:admin {name : Class (singular) for example User}
                            {--table= : The name of the Table.}
                            {--model= : The name of the Model.}
                            {--route-group= : The name of the Model.}
                            {--view-path= : The name of the view path.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create CRUD Admin operations';


    /**
     * Prefix of the route group
     */
    
    protected $routeGroup = 'admin';

    /**
     * Prefix of the route group
     */
    
    protected $viewPath = '_admin';

    /**
     * Helper for the form
     */
    
    protected $formHelper = 'html';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        if (config('crudgenerator.route_group')) {
            $this->routeGroup = config('crudgenerator.route_group');
        }
        if (config('crudgenerator.view_path')) {
            $this->viewPath = config('crudgenerator.view_path');
        }
        if (config('crudgenerator.form_helper')) {
            $this->formHelper = config('crudgenerator.form_helper');
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name       = ucfirst($this->argument('name'));
        $viewPath   = $this->option('view-path') ? $this->option('view-path') : $this->viewPath;
        $routeGroup = $this->option('route-group') ? $this->option('route-group') : $this->routeGroup;
        $modelName  = $this->option('model') ? $this->option('model') : $name ;
        $tableName  = $this->option('table');

        if (file_exists(app_path("/{$name}.php"))) {
            if ($this->confirm('MVC already exist. Do you want to overwrite?')) {
                $this->buildClass($name, $viewPath, $modelName, $tableName, $routeGroup);                
            }
            else {
                $this->info('CRUD Generator stopped.');
            }
        } else {
            $this->buildClass($name, $viewPath, $modelName, $tableName, $routeGroup);                
        }
    }

    protected function buildClass($name, $viewPath, $modelName, $tableName, $routeGroup)
    {
        $this->controller($modelName, $viewPath, $tableName);
        $this->model($name, $tableName);
        $this->request($name, $tableName);
        $this->call('crud:view', ['name' => strtolower($name), '--table' => $tableName, '--view-path' => $viewPath, '--route-group' => $routeGroup, '--form-helper' => $this->formHelper]);
        

        $list = strtolower($name).'list';
        File::append(base_path('routes/web.php'), "\xA". 'Route::resource(\'' . strtolower($name) . "', '{$name}Controller');");
        File::append(base_path('routes/web.php'), "\xA". 'Route::get(\'' . $list . "', '{$name}Controller@list');");

        $this->info('Controller successfully.');
        $this->info('Model successfully.');
        $this->info('Request Validation successfully.');
    }

    protected function controller($name, $viewPath, $tableName)
    {   
        $formFields = $this->getField($tableName);
        $request = '';
        foreach ($formFields as $key => $value) {
            $fieldExist = array('id','created_at','updated_at');
            if (!in_array($value['name'], $fieldExist)) {
                if ($key == 1) {

                    $request = $request. "\$data->{$value['name']} = \$request->get('{$value['name']}');\n";
                } else {
                    
                    $request = $request. "\x20\x20\x20\x20\x20\x20\x20\x20\$data->{$value['name']} = \$request->get('{$value['name']}');\n";
                }
            }
        }

        $controllerTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}',
                '{{viewPath}}',
                '{{request}}',
            ],
            [
                $name,
                strtolower(str_plural($name)),
                strtolower($name),
                $viewPath,
                $request,
            ],
            $this->getStub('Controller')
        );

        file_put_contents(app_path("/Http/Controllers/Admin/{$name}Controller.php"), $controllerTemplate);
    }

    protected function model($name, $tableName)
    {
        $modelTemplate = str_replace(
            [
                '{{modelName}}',
                '{{tableName}}',
            ],
            [
                $name,
                $tableName,
            ],
            $this->getStub('Model')
        );

        file_put_contents(app_path("/{$name}.php"), $modelTemplate);
    }

    protected function request($name, $tableName)
    {   
        $formFields = $this->getField($tableName);
        $rules = '';
        foreach ($formFields as $key => $value) {
            if ($value['required'] && $value['name'] != 'id') {

                $rule = 'required';
                if ($key == 1) {
                    $rules = $rules. "'".$value["name"]."' => '".$rule."',\n";
                } else {

                    $rules = $rules. "\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20'".$value["name"]."' => '".$rule."',\n";
                }
            }
        }

        $requestTemplate = str_replace(
            [
                '{{modelName}}', 
                '{{rules}}' 
            ],
            [
                $name,
                $rules,
            ],
            $this->getStub('Request')
        );

        file_put_contents(app_path("/Http/Requests/{$name}Request.php"), $requestTemplate);
    }

    protected function getStub($type)
    {
        return file_get_contents(resource_path("stubs/$type.stub"));
    }

    protected function getField($tableName)
    {
        $fieldsArray = DB::select(DB::raw('SHOW FIELDS FROM '.$tableName));
        $validations = '';
        $formFields = [];
        $x = 0;
        foreach ($fieldsArray as $item) {

            $type = preg_replace("/\([^)]+\)/","",$item->Type);
            $type = explode(' ', trim($type));
            $type = $type[0];

            $formFields[$x]['name'] = $item->Field;
            $formFields[$x]['type'] = $type;
            $formFields[$x]['required'] = ($item->Null == 'NO') ? true : false;

            if (($formFields[$x]['type'] === 'select' || $formFields[$x]['type'] === 'enum')) 
            {
                preg_match('#\((.*?)\)#', $item->Type, $match);
                $options = $match[1];

                $formFields[$x]['options'] = $options;
            }

            $x++;
        }

        return $formFields;
    }
}
