<?php

require __DIR__ . '/../vendor/autoload.php';

use League\Flysystem\Local\LocalFilesystemAdapter;
use Symfony\Component\HttpFoundation\Request;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use League\Flysystem\Filesystem;

$adapter = new LocalFilesystemAdapter(__DIR__ . '/../data');
$filesystem = new Filesystem($adapter);

$variables = json_decode($filesystem->read('menu.json'), true);

$request = Request::createFromGlobals();
$name = $request->get('name');

$loader = new FilesystemLoader(__DIR__ . '/../templates');
$twig = new Environment($loader);

echo $twig->render('index.html', $variables['index']);