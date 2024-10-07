<?php

namespace ThreeTagger\MyFramework;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
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

    protected ContainerBuilder $container;
    protected array $variables;
    public function __construct()
    {
        $container = new ContainerBuilder();
        $loader = new YamlFileLoader($container, new FileLocator(Path::GetFromSourcePath('')));
        $loader->load('services.yml');

        $this->container = $container;
    }

    public function run(Request $request): Response
    {
        $path = $request->getPathInfo();

        switch ($path) {
            case "/":
            case "/index":
            case "/about":
                $controller = $this->container->get('basic_controller');
                return $controller->get($path);
            case "/contact":
                $controller = $this->container->get('contact_controller');

                $method = $request->getMethod();
                if ($method == Request::METHOD_POST) {
                    return $controller->post($request);
                }

                return $controller->get();
            default:
        }

        $controller = $this->container->get('basic_controller');
        return new Response($controller->notFoundPage(), Response::HTTP_NOT_FOUND);
    }
}