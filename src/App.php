<?php

namespace ThreeTagger\MyFramework;

use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ThreeTagger\MyFramework\Controller\ContactController;
use ThreeTagger\MyFramework\Controller\Controller;
use ThreeTagger\MyFramework\Utils\Path;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class App
{
    protected const NOT_FOUND_PAGE = '404.html';
    protected const PATH_TO_DATA = 'data';
    protected const PATH_TO_TEMPLATES = 'templates';
    protected const VARIABLE_MENU_FILE = 'menu.json';

    protected Filesystem $filesystem;
    protected Environment $twig;
    protected array $variables;
    public function __construct()
    {
        // initialize filesystem
        $adapter = new LocalFilesystemAdapter(Path::GetFromBasePath($this::PATH_TO_DATA));
        $this->filesystem = new Filesystem($adapter);

        // initialize twig
        $loader = new FilesystemLoader(Path::GetFromBasePath($this::PATH_TO_TEMPLATES));
        $this->twig = new Environment($loader);

        // get variables
        $this->variables = $this->getVariables();
    }

    public function getVariables(): array
    {
        $jsonVariables = $this->filesystem->read($this::VARIABLE_MENU_FILE);

        $variables = [];
        $variables['menu'] = json_decode($jsonVariables, true);

        return $variables;
    }

    public function run(Request $request): Response
    {
        $path = $request->getPathInfo();

        $controller = new Controller($this->filesystem, $this->twig, $this->variables);

        switch ($path) {
            case "/":
            case "/index":
            case "/about":
                return $controller->get($path);
            case "/contact":
                $controller = new ContactController($this->twig);

                $method = $request->getMethod();
                if ($method == Request::METHOD_POST) {
                    return $controller->post($request);
                }

                return $controller->get();
        }

        return new Response($controller->notFoundPage(), Response::HTTP_NOT_FOUND);
    }
}