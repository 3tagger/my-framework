<?php

require __DIR__ . '/../vendor/autoload.php';

use ThreeTagger\MyFramework\App;
use Symfony\Component\HttpFoundation\Request;

$request = Request::createFromGlobals();
$app = new App();
$app->run($request)->send();
