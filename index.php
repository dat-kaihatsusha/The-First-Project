<?php

define('app_path', __DIR__);
define('base_path', '/PROJECT');

require_once app_path . '/Core/App.php';
require_once app_path . '/Core/DB.php';
require_once app_path . '/Core/ControllerBase.php';

$app = new App();
$app->run();
