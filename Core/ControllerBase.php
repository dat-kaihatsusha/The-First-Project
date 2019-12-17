<?php


class ControllerBase
{
    public $layout = app_path . '/View/layout.phtml';
    public $layoutLogin = app_path . '/View/layout-login.phtml';
    public $layoutDefault = app_path . '/View/layout-default.phtml';
    protected $dataView = NULL;
    protected $file_view_path = NULL;


    public function RenderView($view_name, $data)
    {
        $this->dataView = $data;

        $this->file_view_path = app_path . '/View/' . str_replace('.', '/', $view_name) . '.phtml';

        if (file_exists($this->layout)) {
            require_once "$this->layout";
        } else die("Khong ton tai file /View/layout.phtml");
    }

    public function RenderViewLogin($view_name, $data)
    {
        $this->dataView = $data;

        $this->file_view_path = app_path . '/View/' . str_replace('.', '/', $view_name) . '.phtml';

        if (file_exists($this->layoutLogin)) {
            require_once "$this->layoutLogin";
        } else die("Khong ton tai file /View/layout-login.phtml");
    }

    public function RenderViewDefault($view_name, $data)
    {
        $this->dataView = $data;

        $this->file_view_path = app_path . '/View/' . str_replace('.', '/', $view_name) . '.phtml';

        if (file_exists($this->layoutDefault)) {
            require_once "$this->layoutDefault";
        } else die("Khong ton tai file /View/layout-login.phtml");
    }

    public function ShowContent()
    {
        if (empty($this->file_view_path)) {
            die("Khong ton tai file: " . $this->file_view_path);
        }

        require_once "$this->file_view_path";
    }
}



