<?php

namespace ThreeTagger\MyFramework\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ThreeTagger\MyFramework\Utils\Path;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class ContactController
{
    protected Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function get(): Response
    {
        $form = $this->twig->render('forms/contact.html');
        $content = $this->twig->render('index.html', [
            'title' => 'Contact',
            'content' => $form,
        ]);

        return new Response($content);
    }

    public function post(Request $request): Response
    {
        // DO SOMETHING with the input
        $content = $this->twig->render('index.html', [
            'title' => 'Contact',
            'content' => 'Thank you for your message',
        ]);

        return new Response($content);
    }
}