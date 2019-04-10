<?php

class CrudGeneratorTest extends TestCase
{
    public function testCrudGenerateCommand()
    {
        // $this->artisan('crud:generate', [
        //     'name' => 'Posts',
        //     '--fields' => "title#string; content#text; category#select#options=technology,tips,health",
        // ]);
        // $this->assertContains('Controller already exists!', $this->consoleOutput());
    }

    public function testControllerGenerateCommand()
    {
        $this->artisan('crud:admin', [
            'name' => 'Customer',
            '--table' => 'customers',
        ]);

        $this->assertContains('Controller created successfully.', $this->consoleOutput());

        $this->assertFileExists(app_path('Http/Controllers') . '/CustomersController.php');
    }
}
