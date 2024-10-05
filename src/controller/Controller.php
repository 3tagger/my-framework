<?php

namespace ThreeTagger\MyFramework\Controller;

use League\Flysystem\Local\LocalFilesystemAdapter;
use Symfony\Component\HttpFoundation\Response;
use ThreeTagger\MyFramework\Utils\Path;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use League\Flysystem\Filesystem;

class Controller
{
    protected const NOT_FOUND_PAGE = '404.html';
    protected const INDEX_PAGE = 'index.html';
    protected const PATH_TO_DATA = 'data';
    protected const PATH_TO_TEMPLATES = 'templates';
    protected const VARIABLE_MENU_FILE = 'menu.json';
    protected const DEFAULT_PAGE = 'index';
    protected $filesystem;
    protected $variables;
    protected $menu;
    protected $twig;

    public function __construct()
    {
        $adapter = new LocalFilesystemAdapter(Path::GetFromBasePath($this::PATH_TO_DATA));
        $this->filesystem = new Filesystem($adapter);

        $jsonVariables = $this->filesystem->read($this::VARIABLE_MENU_FILE);
        $this->variables = [];
        $this->variables['menu'] = json_decode($jsonVariables, true);

        $this->menu = $this->variables['menu'];


        $loader = new FilesystemLoader(Path::GetFromBasePath($this::PATH_TO_TEMPLATES));
        $this->twig = new Environment($loader);
    }

    public function get($path)
    {
        $page = $this::DEFAULT_PAGE;

        if ($path != '/') {
            $page = substr($path, 1);
        }

        if (isset($this->menu[$page])) {
            $content = $this->twig->render($this::INDEX_PAGE, $this->menu[$page]);
            return new Response($content);
        }

        return $this->notFoundPage();
    }

    public function notFoundPage(): Response
    {
        $content = $this->twig->render($this::NOT_FOUND_PAGE);
        return new Response($content, Response::HTTP_NOT_FOUND);
    }
}