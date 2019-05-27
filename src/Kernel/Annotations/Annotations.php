<?php
/**
 * Copyright (c) 2019 Christophe Daloz - De Los Rios
 * This code is licensed under MIT license (see LICENSE for details)
 */

namespace Kernel\Annotations;


use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\DocParser;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\RedirectResponse;

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

    private $listAnnotations;

    /**
     * Annotations constructor.
     * @param ContainerBuilder $container
     */
    public function __construct(ContainerBuilder $container)
    {
        $scan = scandir(
            __DIR__.'/../../../vendor/doctrine/annotations/lib/Doctrine/Common/Annotations/Annotation'
        );

        foreach ($scan as $index => $value) {
            if (substr($value, strrpos($value, '.')) === '.php') {
                require_once __DIR__.
                    "/../../../vendor/doctrine/annotations/lib/Doctrine/Common/Annotations/Annotation/$value";
            }
        }

        $this->container = $container;
        $this->defaultMessage = 'Unauthorized access, you are not admin';

        $listAnnotations = $this->container->getParameter('annotations.load');
        $this->listAnnotations = $listAnnotations;

        foreach ($listAnnotations as $annotation => $reflection) {
            $dir = __DIR__.'/../..';
            $dir .= '/'.str_replace('\\', '/', $annotation);

            require_once "$dir.php";

            AnnotationRegistry::loadAnnotationClass($annotation);
            $docParser = new DocParser();
        }
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
        $reader = new AnnotationReader();
        $methodReflection = new \ReflectionMethod($class, $method);
        $arrayObjects = $reader->getMethodAnnotations($methodReflection);

        foreach ($arrayObjects as $object) {
            foreach ($this->listAnnotations as $annotation => $reflection) {
                if ($object instanceof $annotation) {
                    /**
                     * @var Reflections $instance
                     */
                    $instance = new $reflection($this->container);
                    return $instance->execute($object);
                }
            }
        }

        return true;
    }
}
