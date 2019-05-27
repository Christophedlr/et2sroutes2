<?php
/**
 * Copyright (c) 2019 Christophe Daloz - De Los Rios
 * This code is licensed under MIT license (see LICENSE for details)
 */

namespace Kernel\Annotations\Reflections;


use Bundle\User\Entity\User;
use Kernel\Annotations\Annotations\Security as SecurityAnnotation;
use Kernel\Annotations\Reflections;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class Security
 * @package Kernel\Annotations\Reflections
 *
 * @author Christophe Daloz - De Los Rios
 * @version 0.2.0-Alpha
 */
class Security extends Reflections
{
    /**
     * Reflections constructor.
     *
     * @param object $object
     * @param ContainerBuilder $container
     */
    public function __construct(ContainerBuilder $container)
    {
        $this->container = $container;

        $this->defaultMessage = 'Unauthorized access';
    }

    /**
     * Execute annotation orders
     *
     * @param SecurityAnnotation $security
     * @return mixed
     * @throws \Exception
     */
    public function execute(SecurityAnnotation $security)
    {
        if (is_null($security->route)) {
            throw new \Exception('Security Annotation: route is required');
        }

        if (is_null($security->type)) {
            throw new \Exception('Security Annotation: type is required');
        }

        switch ($security->type) {
            case 'IS_ADMIN':
                if (!$this->container->get('session')->get('user')->getAdmin()) {
                    return $this->messageAndRedirect($security->message, $security->route);
                }
                break;

            case 'IS_NOT_ADMIN':
                if ($this->container->get('session')->get('user')->getAdmin()) {
                    return $this->messageAndRedirect($security->message, $security->route);
                }
                break;

            case 'IS_ANONYMOUS':
                /**
                 * @var User $user
                 */
                $user = $this->container->get('session')->get('user');

                if (!is_null($user->getId()) ) {
                    return $this->messageAndRedirect($security->message, $security->route);
                }
                break;

            case 'IS_USER':
                /**
                 * @var User $user
                 */
                $user = $this->container->get('session')->get('user');

                if (is_null($user->getId()) || !$user->getActive() ) {
                    return $this->messageAndRedirect($security->message, $security->route);
                }
                break;
        }

        return true;
    }
}
