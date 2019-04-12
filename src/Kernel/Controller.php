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
use Kernel\TwigExtension\AppExtension;
use Kernel\TwigExtension\AssetExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Twig\Environment;
use Twig\TwigFunction;

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

        /** @var Environment $twig */
        $twig = $this->container->get('twig.environment');

        $twig->addExtension(new AppExtension($this->container));
        $twig->addExtension(new AssetExtension($this->container));
        $twig->addGlobal('flashBag', $this->getFlashBag());

        $this->getDoctrine();
        $this->getSession()->start();
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

    /**
     * @return Session
     * @throws \Exception
     */
    public function getSession()
    {
        return $this->container->get('session');
    }

    /**
     * @return FlashBag
     * @throws \Exception
     */
    public function getFlashBag()
    {
        return $this->getSession()->getFlashBag();
    }

    /**
     * @param string $route
     * @param array $params
     * @return RedirectResponse
     * @throws \Exception
     */
    public function redirectToRoute(string $route, array $params = [])
    {
        return new RedirectResponse($this->container->get('router')->generate($route, $params));
    }
}
