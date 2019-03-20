<?php
/**
 * Copyright (c) 2019 Christophe Daloz - De Los Rios
 * This code is licensed under MIT license (see LICENSE for details)
 */

namespace Kernel;


use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class Renderer
{
    private $renderer;

    public function __construct(Environment $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * @param string|TemplateWrapper $name Template name
     * @param array $context Parameters for template
     * @param int $status HTTPCode
     * @param array $headers
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function renderResponse($name, array $context = [], int $status = 200, array $headers = [])
    {
        return new Response($this->renderer->render($name, $context), $status, $headers);
    }

    /**
     * @return Environment
     */
    public function getRenderer(): Environment
    {
        return $this->renderer;
    }
}
