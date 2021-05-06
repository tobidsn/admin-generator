<?php

namespace Tobidsn\CrudGenerator\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use DB;

class ApiGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:api-cms {name : Class (singular) for example User}
                            {--table= : The name of the Table.}
                            {--model= : The name of the Model.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create API CMS operations';

    /**
     * The route file
     */

    protected $routeFile = 'routes/api-cms.php';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        if (config('apigenerator.route_file')) {
            $this->routeFile = config('apigenerator.route_file');
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
        $modelName  = $this->option('model') ? $this->option('model') : $name ;
        $tableName  = $this->option('table');

        if (file_exists(app_path("/{$name}.php"))) {
            if ($this->confirm('MVC already exist. Do you want to overwrite?')) {
                $this->buildClass($name, $modelName, $tableName);
            }
            else {
                $this->info('CRUD Generator stopped.');
            }
        } else {
            $this->buildClass($name, $modelName, $tableName);
        }
    }

    protected function buildClass($name, $modelName, $tableName)
    {
        $this->controller($modelName, $tableName);
        $this->model($name, $tableName);
        $this->request($name, $tableName);
        $this->resources($name, $tableName);
        $this->collection($name, $tableName);
        $this->observer($name, $tableName);

        $string = `/* {$name} Routes */
        Route::group(['prefix' => '{$name}', 'middleware' => 'auth:api-cms'], function() {
            Route::get('', ['uses' => '{$name}Controller@index', 'as' => 'api-cms.{$name}']);
            Route::post('', ['uses' => '{$name}Controller@store', 'as' => 'api-cms.{$name}-create']);
            Route::get('/{{$name}}', ['uses' => '{$name}Controller@show', 'as' => 'api-cms.{$name}-detail']);
            Route::put('/{{$name}}', ['uses' => '{$name}Controller@update', 'as' => 'api-cms.{$name}-update']);
            Route::delete('/{{$name}}', ['uses' => '{$name}Controller@destroy', 'as' => 'api-cms.{$name}-delete']);
        });`;

        File::append(base_path($this->routeFile), $string);

        $this->info('Controller successfully.');
        $this->info('Model successfully.');
        $this->info('Request Validation successfully.');
    }

    protected function controller($name, $tableName)
    {

        $controllerTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}'
            ],
            [
                $name,
                strtolower(str_plural($name)),
                strtolower($name),
            ],
            $this->getStub('Controller')
        );

        if (!file_exists(app_path("Http/Controllers/Api/Cms"))) {
          mkdir(app_path("Http/Controllers/Api/Cms"), 0755, true);
        }

        file_put_contents(app_path("Http/Controllers/Api/Cms/{$name}Controller.php"), $controllerTemplate);
    }

    protected function model($name, $tableName)
    {
        $formFields = $this->getField($tableName);
        $fillable = '';
        foreach ($formFields as $key => $value) {
            $fieldExist = array('id','created_at','updated_at');
            if (!in_array($value['name'], $fieldExist)) {
                if ($key == 1) {

                    $fillable = $fillable. "'".$value["name"]."',\n";
                } else {

                    $fillable = $fillable. "\x20\x20\x20\x20\x20\x20\x20\x20'".$value["name"]."',\n";
                }
            }
        }

        $modelTemplate = str_replace(
            [
                '{{modelName}}',
                '{{tableName}}',
                '{{modelNameSingularLowerCase}}',
                '{{fillable}}'
            ],
            [
                $name,
                $tableName,
                strtolower($name),
                $fillable
            ],
            $this->getStub('Model')
        );

        file_put_contents(app_path("Models/{$name}.php"), $modelTemplate);
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

        file_put_contents(app_path("Http/Requests/Cms/{$name}Request.php"), $requestTemplate);
    }

    protected function resources($name, $tableName)
    {
        $requestTemplate = str_replace(
            [
                '{{modelName}}',
            ],
            [
                $name,
            ],
            $this->getStub('Resource')
        );

        file_put_contents(app_path("Http/Resources/Cms/{$name}Resource.php"), $requestTemplate);
    }

    protected function collection($name, $tableName)
    {
        $requestTemplate = str_replace(
            [
                '{{modelName}}',
            ],
            [
                $name,
            ],
            $this->getStub('Collection')
        );

        file_put_contents(app_path("Http/Resources/Cms/{$name}Collection.php"), $requestTemplate);
    }

    protected function observer($name, $tableName)
    {
        $requestTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNameSingularLowerCase}}',
            ],
            [
                $name,
                strtolower($name),
            ],
            $this->getStub('Observer')
        );

        file_put_contents(app_path("Observers/{$name}Observer.php"), $requestTemplate);
    }

    protected function getStub($type)
    {
        return file_get_contents(resource_path("stubsapi/$type.stub"));
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