<?php

namespace ThreeTagger\MyFramework\Controller;

use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use League\Flysystem\Filesystem;

class Controller
{
    protected const DEFAULT_PAGE = 'index';
    protected const INDEX_PAGE = 'index.html';
    protected Filesystem $filesystem;
    protected array $menu;
    protected Environment $twig;

    public function __construct(Filesystem $filesystem, Environment $twig, array $variables)
    {
        $this->filesystem = $filesystem;
        $this->menu = $variables['menu'];
        $this->twig = $twig;
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