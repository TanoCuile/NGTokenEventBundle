<?php

/**
 * This file is part of the NGTokentEventBundle package
 *
 * (c) Shvets Serhiy <strifinder@gmail.com>
 *
 * For the full copyring and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace NG\TokenEventBundle\TokenEventManager;

use NG\TokenEventBundle\TokenEvent\TokenEventInterface,
    NG\TokenEventBundle\Entity\TokenEvent,
    NG\TokenEventBundle\Exception\TokenEventException,
    NG\TokenEventBundle\TokenEvent\TokenEventResponsableInterface,
    Symfony\Component\DependencyInjection\ContainerInterface;

class TokenEventManager implements TokenEventManagerInterface
{
  //ContainerInterface
  protected $container;
  
  /**
   * Set object defaults
   *
   * @param ContainerInterface $container
   */
  public function __construct(ContainerInterface $container) {
    $this->container = $container;    
  }
  
  /**
   * Save TokenEvent
   *
   * @param TokenEvent $event
   */
  public function saveEvent(TokenEvent $event)
  {
    if ($event) {
      if (!$event->getToken()) {
        $event->setToken(substr(hash('sha256', spl_object_hash($event) . time()), 0, 64));
      }
      $em = $this->container
        ->get('doctrine')
        ->getEntityManager();
      $em->persist($event);
      $em->flush();
    }
    else {
      throw new \RuntimeException('$event must be not empty.');
    }
  }  
  
  /**
   * Get empty TokeEvent shape
   *
   * @param string $event_id 
   *
   * @return TokenEventInterface
   *
   * @throws \InvalidArgumentException
   */
  public function getEmptyEvent($event_id)
  {
    if ($this->container->has($event_id)) {
      return clone $this->container->get($event_id);
    }
    throw new InvalidArgumentException(sprintf('No service with %s found.', $event_id));
  }
  
  /**
   * Validate event
   *
   * @param TotenEvent $event
   *
   * @throws TokenEventException
   */
  public function validateEvent(TokenEvent $event)
  {
    if ($event->getBlocked()) {
      throw new TokenEventException('This token has been blocked');
    }
    
    if ($toTime = $event->getToTime()) {
      if (strtotime($toTime) < time()) {
        throw new TokenEventException('Duration of the token has expired.');
      }
    }
    
    if ($maxCount = $event->getMaxCount()) {
      if ($maxCount > $event->getCountUsed()) {
        throw new TokenEventException('Number of uses token has expired.');
      }
    }
    
    if ($ip = $event->getIp()) {
      if ($this->container->get('request')->getClientIp() != $ip) {
        throw new TokenEventException('From this you can not use the token.');
      }
    } 
    
    return TRUE;
  }
  
  /**
   * Execute event
   *
   * @param TokenEvent
   */
  public function executeEvent(TokenEvent $event)
  {    
    $em = $this->container->get('doctrine')->getEntityManager();
    
    $responce = NULL;
    
    foreach ($event->getActions() as $action) {
      if ($action instanceof TokenEventResponsableInterface) {
        if ($responce) {
          throw new \RuntimeException('Response are alredy set, can not add second.');
        }
        $responce = $action->execute();
        if (!$responce) {
          throw new \RuntimeException('No response retunrned by Responsable event.');
        }
      }
      else {
        $action->execute();
      }
    }
    
    $countUsage = $event->getCountUsed();    
    $event->setCountUsed($countUsage + 1);
    
    $em->persist($event);
    $em->flush();
    
    return $responce;
  }
  
  /**
   * Get Event by Token
   *
   * @param string $token
   */
  public function getEvent($token)
  {
    $em = $this->container->get('doctrine')->getEntityManager();
    $event = $em->getRepository('NGTokenEventBundle:TokenEvent')->findByToken($token);
    
    if (!$event) {
      throw new TokenEventException('No event found by this token.');
    }
    
    try {
      $this->validateEvent($event);
    }
    catch (TokenEventException $e) {
      if (!$event->getBlocked()) {
        $event->setBlocked(TRUE);
        $em->persist($event);
        $em->flush();
      }
      throw $e;
    }
    
    $bag = $this->container->get('token_event.event_bag');
    
    foreach ($event->getActions() as $action) {
      $action->setDefaults($bag->getTokenEvent(get_class($action)));
    }
    
    return $event;
  }  
}