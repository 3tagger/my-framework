<?php

namespace ThreeTagger\MyFramework\Controller;

use League\Flysystem\FilesystemOperator;
use Symfony\Component\HttpFoundation\Response;
use ThreeTagger\MyFramework\Service\ConfigService;
use Twig\Environment;

class Controller
{
    protected const DEFAULT_PAGE = 'index';
    protected const INDEX_PAGE = 'index.html';
    protected FilesystemOperator $filesystem;
    protected array $menu;
    protected Environment $twig;

    public function __construct(FilesystemOperator $filesystem, Environment $twig, ConfigService $configService)
    {
        $this->filesystem = $filesystem;
        $this->twig = $twig;

        $config = $configService->getConfig();
        $this->menu = $config['menu'];
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