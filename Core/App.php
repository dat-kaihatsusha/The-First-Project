<?php

class App
{
    public function run()
    {
        // Link kien ket: ?ct=home&act=home
        // ?ct=log-in&act=login
        // ?ct=sign-in&act=sign-in

        if (isset($_GET['ct']))
        {
            $ct = $_GET['ct'];
        }
        else if (!isset($_GET['ct']))
        {
            $ct = 'home-default';
        }

        if (isset($_GET['act']))
        {
            $act = $_GET['act'];
        }
        else if (!isset($_GET['act']))
        {
            $act = 'home-default';
        }

        // Nhung duoi controller

        $classCt = str_replace('-', ' ', $ct);
        $classCt = ucwords($classCt);
        $classCt = str_replace(' ', '', $classCt);
        $classCt = $classCt .'Controller';

        // Tao duong dan file Controller

        $file_controller = app_path .'/Controller/' .$classCt .'.php';

        if (file_exists($file_controller))
        {
            require_once $file_controller; // Nhung file controller tuong ung voi $ct
        }
        else die("Khong ton tai file $file_controller");

        $objCt = new $classCt();

        // Tao ten ham tuong ung trong file controller

        $actName = str_replace('-', ' ', $act);
        $actName = ucwords($actName);
        $actName = str_replace(' ','', $actName);

        if (method_exists($objCt, $actName))
        {
            $objCt->$actName();
        }
        else die("Khong ton tai <b>$actName</b>");
    }
}
?>