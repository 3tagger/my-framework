<?php

namespace ThreeTagger\MyFramework;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ThreeTagger\MyFramework\Controller\Controller;

class App
{
    public function run(Request $request): Response
    {
        $path = $request->getPathInfo();

        $controller = null;

        switch ($path) {
            case "/":
            case "/index":
            case "/about":
                $controller = new Controller();
        }

        if (is_null($controller)) {
            $controller = new Controller();
            return new Response($controller->notFoundPage(), Response::HTTP_NOT_FOUND);
        }

        return $controller->get($path);
    }
}