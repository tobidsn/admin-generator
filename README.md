# Laravel Admin CRUD Generator

[![Build Status](https://travis-ci.org/tobidsn/admin-generator.svg)](https://travis-ci.org/tobidsn/admin-generator.svg)
[![Total Downloads](https://poser.pugx.org/tobidsn/admin-generator/d/total.svg)](https://packagist.org/packages/tobidsn/admin-generator)
[![Latest Stable Version](https://poser.pugx.org/tobidsn/admin-generator/v/stable.svg)](https://packagist.org/packages/tobidsn/admin-generator)
[![License](https://poser.pugx.org/tobidsn/admin-generator/license.svg)](https://packagist.org/packages/tobidsn/admin-generator)

This Generator package provides various generators like Admin CRUD, Controller, Model, View based from table for your painless development of your applications.

## Requirements
    Laravel >= 5.5
    PHP >= 7.0

## Installation
```
composer require tobidsn/admin-generator
```

Once the package is installed, you should register the `Tobidsn\CrudGenerator\CrudGeneratorServiceProvider` service provider. Normally, Laravel 5.5+ will register the service provider automatically.

After that, publish its assets using the `vendor:publish` Artisan command:
```
php artisan vendor:publish --provider="Tobidsn\CrudGenerator\CrudGeneratorServiceProvider"
```

## Usage

### Generating Migrations

```
php artisan make:migration create_users_table
```

### Running Migrations

```
php artisan migrate
```

### Admin CRUD Command

```
php artisan crud:admin User --table=users
```


### Signature information

```php
protected $signature = 'crud:admin {name : Class (singular) for example User}
                        {--table= : The name of the Table.}
                        {--model= : The name of the Model.}
                        {--route-group= : The name of the Model.}
                        {--view-path= : The name of the view path.}';
```

## Configuration

You will find a configuration file located at `config/crudgenerator.php`

### Custom Template

When you want to use your own custom template files, then you should turn it on and it will use the files from `resources/stubs/`

```php
'custom_template' => true,
```

### Path

You can change your template path easily, the default path is `resources/stubs/`.

```php
'path' => base_path('resources/stubs/'),
```

### View Columns

When generating CRUD or the views, the generator will assume the column number to show for the CRUD grid or detail automatically from the config. You can change it.

```php
'view_columns_number' => 5,
```

### Custom Delimiter

Set your delimiter which you use for your template vars. The default delimiter is `%%` in everywhere.

```php
'custom_delimiter' => ['%%', '%%'],
```
Note: You should use the delimiter same as yours template files.

### View Template Vars

This configuration will help you to use any custom template vars in the views `index`, `form`,`list`, `create`, `edit`, `show`

```php
'dynamic_view_template' => [],
```

### Route group

Route group of the controller

```php
'route_group' => 'admin',
```

### View path

View path for view generator

```php
'view_path' => '_admin',
```

### Form Helper

Helper for custom view and form  

```php
'form_helper' => 'adminlte3',
```

## Custom Templates

The package allows user to extensively customize or use own templates.

### All Templates

To customize or change the template, you need to follow these steps:

1. Just make sure you've published all assets of this package. If you didn't just run this command.
    ```php
    php artisan vendor:publish --provider="Tobidsn\CrudGenerator\CrudGeneratorServiceProvider"
    ```
2. To override the default template with yours, turn on `custom_template` option in the `config/crudgenerator.php` file.

    ```php
    'custom_template' => true,
    ```

3. Now you can customize everything from this `resources/stubs/` directory.

4. Even if you need to use any custom variable just add those in the `config/crudgenerator.php` file.


## License

This project is licensed under the MIT License - see the [License File](LICENSE) for details
