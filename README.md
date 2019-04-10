# Laravel Admin CRUD Generator

[![Build Status](https://travis-ci.org/tobidsn/admin-generator.svg)](https://travis-ci.org/tobidsn/admin-generator.svg)
[![Total Downloads](https://poser.pugx.org/tobidsn/admin-generator/d/total.svg)](https://packagist.org/packages/tobidsn/admin-generator)
[![Latest Stable Version](https://poser.pugx.org/tobidsn/admin-generator/v/stable.svg)](https://packagist.org/packages/tobidsn/admin-generator)
[![License](https://poser.pugx.org/tobidsn/admin-generator/license.svg)](https://packagist.org/packages/tobidsn/admin-generator)

This Generator package provides various generators like Admin CRUD, Controller, Model, View for your painless development of your applications.

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

## License

This project is licensed under the MIT License - see the [License File](LICENSE) for details
