<?php

namespace Tobidsn\CrudGenerator\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use DB;
use Illuminate\Support\Str;

class ApiWebGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:api-web {name : Class (singular) for example User}
                            {--table= : The name of the Table.}
                            {--model= : The name of the Model.}
                            {--type= : The name of Type.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create API Web operations';

    /**
     * The route file
     */

    protected $routeFile = 'routes/api-web.php';

    /**
     * The stub file
     */

    protected $stubFile = true;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        if (config('crudgenerator.route_web_file')) {
            $this->routeFile = config('crudgenerator.route_web_file');
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
        $type       = $this->option('type');

        if (file_exists(app_path("Http/Controllers/Api/Web/{$name}Controller.php"))) {
            if ($this->confirm('Controller Web already exist. Do you want to overwrite?')) {
                $this->buildClass($name, $modelName, $tableName, $type);
            }
            else {
                $this->info('CRUD Generator stopped.');
            }
        } else {
            $this->buildClass($name, $modelName, $tableName, $type);
        }
    }

    protected function buildClass($name, $modelName, $tableName, $type)
    {
        if ($type == 'resource') {
            $this->resources($name, $tableName);
            $this->collection($name, $tableName);
        } else{
            $this->controller($modelName, $tableName);
            $this->resources($name, $tableName);
            $this->collection($name, $tableName);
        }

        $lowerName = strtolower($name);
        $upperName = strtoupper($name);

        $string = "
        /* {$upperName} ROUTES */
        Route::get('/{$lowerName}', ['uses' => '{$name}Controller@index', 'as' => 'api-web.{$lowerName}']);
        Route::get('/{$lowerName}/{{$lowerName}}', ['uses' => '{$name}Controller@show', 'as' => 'api-web.{$lowerName}-detail']);";

        File::append(base_path($this->routeFile), $string);

        $this->info($name.' Controller successfully.');
        // $this->info($name.' Model successfully.');
        // $this->info($name.' Request Validation successfully.');
        $this->info($name.' Resources Validation successfully.');
        $this->info($name.' Collection Validation successfully.');
        // $this->info($name.' Observer Validation successfully.');
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
                strtolower(Str::plural($name, 2)),
                strtolower($name),
            ],
            $this->getStub('Controller')
        );

        if (!file_exists(app_path("Http/Controllers/Api/Web"))) {
          mkdir(app_path("Http/Controllers/Api/Web"), 0755, true);
        }

        file_put_contents(app_path("Http/Controllers/Api/Web/{$name}Controller.php"), $controllerTemplate);
    }

    protected function resources($name, $tableName)
    {
        $formFields = $this->getField($tableName);
        $field = '';
        foreach ($formFields as $key => $value) {
            $fieldExist = array('created_by','updated_by','created_at','updated_at');
            if (!in_array($value['name'], $fieldExist)) {

                $object = "$"."this->".$value['name'];
                if ($key == 0) {
                    $field = $field. "'".$value["name"]."' => ".$object.",\n";
                } else {

                    $field = $field. "\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20'".$value["name"]."' => ".$object.",\n";
                }
            }
        }

        $requestTemplate = str_replace(
            [
                '{{modelName}}',
                '{{field}}',
            ],
            [
                $name,
                $field,
            ],
            $this->getStub('Resource')
        );

        if (!file_exists(app_path("Http/Resources/Web"))) {
          mkdir(app_path("Http/Resources/Web"), 0755, true);
        }

        file_put_contents(app_path("Http/Resources/Web/{$name}Resource.php"), $requestTemplate);
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

        if (!file_exists(app_path("Http/Resources/Web"))) {
          mkdir(app_path("Http/Resources/Web"), 0755, true);
        }

        file_put_contents(app_path("Http/Resources/Web/{$name}Collection.php"), $requestTemplate);
    }

    protected function getStub($type)
    {
        if (file_exists(resource_path("stubs_web_api/$type.stub"))) {
            return file_get_contents(resource_path("stubs_web_api/$type.stub"));
        } else {
            return file_get_contents(__DIR__ . "/../stubs_web_api/$type.stub");
        }
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