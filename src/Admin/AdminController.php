<?php
namespace SimaBase\Admin;

class AdminController
{

    protected string $viewsPath;

    public function __construct()
    {
        $this->viewsPath = __DIR__ . DIRECTORY_SEPARATOR . 'Admin' . DIRECTORY_SEPARATOR . 'views';
    }

    protected function view($filename)
    {
        return require($this->viewsPath . DIRECTORY_SEPARATOR . $filename);
    }

    public function settingsView(): void
    {
        $this->view('settings.php');
    }

}