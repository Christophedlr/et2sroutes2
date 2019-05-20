<?php
/**
 * Copyright (c) 2019 Christophe Daloz - De Los Rios
 * This code is licensed under MIT license (see LICENSE for details)
 */

namespace Kernel\Annotations;


use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\DocParser;
use Kernel\Annotations\Reflections\Security;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

require_once 'Reflections/Security.php';

/**
 * Class Annotations
 * @package Kernel\Annotations
 *
 * @author Christophe Daloz - De Los Rios
 * @version 0.2.0-Alpha
 */
class Annotations
{
    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     * @var string
     */
    private $defaultMessage;

    /**
     * Annotations constructor.
     * @param ContainerBuilder $container
     */
    public function __construct(ContainerBuilder $container)
    {
        $this->container = $container;
        $this->defaultMessage = 'Unauthorized access, you are not admin';
    }

    /**
     * Execute annotations orders
     *
     * @param string $class
     * @param string $method
     * @return bool|RedirectResponse
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function execute(string $class, string $method)
    {
        AnnotationRegistry::loadAnnotationClass(Security::class);
        $docParser = new DocParser();

        $reader = new AnnotationReader();
        $methodReflection = new \ReflectionMethod($class, $method);
        $arrayObjects = $reader->getMethodAnnotations($methodReflection);

        foreach ($arrayObjects as $object) {
            if ($object instanceof Security) {
                $result = $this->securityAnnotation($object);
            }

            if ($result instanceof Response) {
                return $result;
                break;
            }
        }

        return true;
    }

    /**
     * Orders of Security annotation
     *
     * @param Security $security
     * @return bool|RedirectResponse
     * @throws \Exception
     */
    private function securityAnnotation(Security $security)
    {
        if (is_null($security->type)) {
            throw new \Exception('Security Annotation: type is required');
        }
        if (is_null($security->route)) {
            throw new \Exception('Security Annotation: route is required');
        }

        switch ($security->type) {
            case 'IS_ADMIN':
                if (!$this->container->get('session')->get('user')->getAdmin()) {
                    return $this->messageAndRedirect($security->message, $security->route);
                }
                break;
        }

        return true;
    }

    /**
     * Add flashbag and redirect to selected route
     *
     * @param string|null $message
     * @param string $route
     * @return RedirectResponse
     * @throws \Exception
     */
    private function messageAndRedirect($message, string $route)
    {
        $msg = $this->defaultMessage;

        if (!empty($message)) {
            $msg = $message;
        }

        $this->container->get('session')->getFlashBag()->add('danger', $msg);
        return new RedirectResponse($this->container->get('router')->generate($route, []));
    }
}
