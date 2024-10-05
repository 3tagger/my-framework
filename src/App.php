<?php

namespace ThreeTagger\MyFramework;

use League\Flysystem\Local\LocalFilesystemAdapter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use League\Flysystem\Filesystem;

class App
{
    private $filesystem;
    private $variables;
    private $twig;

    public function __construct()
    {
        $adapter = new LocalFilesystemAdapter(__DIR__ . '/../data');
        $this->filesystem = new Filesystem($adapter);

        $jsonVariables = $this->filesystem->read('menu.json');
        $this->variables = json_decode($jsonVariables, true);

        $loader = new FilesystemLoader(__DIR__ . '/../templates');
        $this->twig = new Environment($loader);
    }
    public function run(Request $request): Response
    {
        $path = $request->getPathInfo();
        $content = '';

        switch ($path) {
            case '/':
            case '/index':
                $content = $this->twig->render('index.html', $this->variables['index']);
                break;
            case '/about':
                $content = $this->twig->render('about.html', $this->variables['about']);
                break;
        }

        if (empty($content)) {
            $content = $this->twig->render('404.html');
            return new Response($content, Response::HTTP_NOT_FOUND);
        }

        return new Response($content);
    }
}