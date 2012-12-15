<?php

/**
 * This file is part of the NGTokentEventBundle package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyring and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace NG\TokenEventBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs,
    NG\TokenEventBundle\Entity\TokenEvent,
    Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Doctrine event listener for generate token hash ID
 * and set service container to all events
 */
class TokenEventListener
{
  // Service container
  protected $container = NULL;
  
  /**
   * Construct
   *
   * @param ContainerInterface
   */
  public function __construct(ContainerInterface $container)
  {
    $this->container = $container;
  }
  
  /**
   * Pre persist callback
   *
   * @param LifecycleEventArgs $event
   */
  public function prePersist(LifecycleEventArgs $event)
  {
    $entity = $event->getEntity();
    if ($entity instanceof TokenEvent) {
      $hashId = hash('md5', uniqid('token_event', TRUE));
      $entity->setToken($hashId);
    }
  }
  
  /**
   * Post load
   */
  public function postLoad(LifecycleEventArgs $event)
  {
    $entity = $event->getEntity();
    if ($entity instanceof TokenEvent) {
      foreach ($entity->getEvents() as $event) {
        $event->setContainer($this->container);
      }
    }
  }
}