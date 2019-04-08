<?php
/**
 * Copyright (c) 2019 Christophe Daloz - De Los Rios
 * This code is licensed under MIT license (see LICENSE for details)
 */

namespace Kernel;


use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Tools\Setup;
use Kernel\TwigExtension\AssetExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Twig\Environment;

/**
 * Base controller
 *
 * @author Christophe Daloz - De Los Rios
 * @version 1.0
 * @package Kernel
 */
class Controller
{
    /**
     * @var ContainerBuilder
     */
    protected $container;

    public function __construct(ContainerBuilder $container)
    {
        $this->container = $container;

        $explode = explode('\\', get_called_class());

        for ($i=0; $i < count($explode); $i++) {
            if ($explode[$i] === 'Controller') {
                $this->container->get('twig.loader')->addPath(
                    sprintf('src/Bundle/%s/web/template', $explode[$i-1]),
                    $explode[$i-1]
                );
                break;
            }
        }

        $this->container->get('twig.environment')->addExtension(new AssetExtension($this->container));
        $this->getDoctrine();
    }

    /**
     * @return Renderer
     * @throws \Exception
     */
    public function getTemplate()
    {
        return new Renderer($this->container->get('twig.environment'));
    }

    /**
     * @return Configuration
     * @throws \Exception
     */
    public function getDoctrine()
    {
        if (!$this->container->has('doctrine')) {
            $this->container->set('doctrine', Setup::createAnnotationMetadataConfiguration(['src']));
        }

        return $this->container->get('doctrine');
    }

    /**
     * @return EntityManager
     * @throws \Doctrine\ORM\ORMException
     */
    public function getEntityManager()
    {
        if (!$this->container->has('entity')) {
            $database['host'] = $this->container->getParameter('database.host');
            $database['user'] = $this->container->getParameter('database.user');
            $database['password'] = $this->container->getParameter('database.password');
            $database['driver'] = $this->container->getParameter('database.driver');
            $database['port'] = $this->container->getParameter('database.port');
            $database['dbname'] = $this->container->getParameter('database.name');

            $em = EntityManager::create($database, $this->getDoctrine());
            $em->getConfiguration()->setMetadataDriverImpl(AnnotationDriver::create(['src']));
            $this->container->set('entity', $em);
        }

        return $this->container->get('entity');
    }
}
