<?php
$basePath = __DIR__.'/';
require_once $basePath.'modules/core/autoloader.php';
require_once $basePath.'modules/settings.php';

$app = new \Core\Application;
$app->run();


?>