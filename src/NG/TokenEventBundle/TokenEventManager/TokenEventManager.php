<?php

/**
 * This file is part of the NGTokentEventBundle package
 *
 * (c) Shvets Serhiy <strifinder@gmail.com>
 *    Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyring and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace NG\TokenEventBundle\TokenEventManager;

use NG\TokenEventBundle\Entity\TokenEvent,
    NG\TokenEventBundle\Exceptions\EventNotFoundException,
    NG\TokenEventBundle\Exceptions as TE,
    Symfony\Component\DependencyInjection\ContainerInterface,
    Symfony\Component\HttpFoundation\RedirectResponse,
    Symfony\Component\HttpFoundation\Response;

/**
 * Class-manager fot TokenEvent
 */
class TokenEventManager implements TokenEventManagerInterface
{
  // Service container
  protected $container;
  
  /**
   * Set object defaults
   *
   * @param ContainerInterface $container
   */
  public function __construct(ContainerInterface $container)
  {
    $this->container = $container;    
  }
  
  /**
   * @{inerhitDoc}
   */
  public function createNewToken()
  {
    return new TokenEvent;
  }
  
  /**
   * @{inerhitDoc}
   */
  public function saveToken(TokenEvent $tokenEvent)
  {
    $em = $this->container
        ->get('doctrine')
        ->getEntityManager();
    
    $em->persist($tokenEvent);
    $em->flush();
    
    return $tokenEvent;
  }
  
  /**
   * @{inerhitDoc}
   */
  public function validateToken(TokenEvent $token)
  {
    if ($token->getBlocked()) {
      throw new TE\TokenIsBlockedException(sprintf('Token "%s" have blocked.', $token->getToken()));
    }
    
    if ($toTime = $token->getToTime()) {
      $nowDate = new \DateTime();
      $nowDate->setTimezone($toTime->getTimezone());
      
      if ($nowDate > $toTime) {
        throw new TE\TokenTimeNotValidException(sprintf('Token is valid to "%s".', $toTime->format('H:i:s m/d/Y')));
      }
    }
    
    if ($maxCountUsage = $token->getMaxCountUsage()) {
      if ($token->getCountUsed() >= $maxCountUsage) {
        throw new TE\TokenMaxUsedException(sprintf('Token "%s" can use "%s" iterations.', $token->getToken(), $maxCountUsage));
      }
    }
    
    if ($ip = $token->getIp()) {
      $clientIp = $this->container->get('request')->getClientIp();
      if ($clientIp != $ip) {
        throw new TE\TokenIpDeniedException(sprintf('Token not executing from "%s" IP.', $clientIp));
      }
    } 
  }
  
  /**
   * @{inerhitDoc}
   */
  public function executeToken(TokenEvent $token)
  {
    // Update token before call
    $em = $this->container->get('doctrine')->getEntityManager();
    
    $token
        ->setCountUsed($token->getCountUsed() + 1)
        ->setLastUsed(new \DateTime());
    
    $em->flush($token);
    
    // Get token events
    $tokenEvents = $token->getEvents();
    
    // Sort token events by priority
    $tokenEvents->uasort(function($a, $b) {
      if ($a->getPriority() == $b->getPriority()) {
        return 0;
      }
      
      return $a->getPriority() < $b->getPriority() ? -1 : 1;
    });
    
    foreach ($tokenEvents as $event) {
      $response = $event->execute();
    
      if ($response instanceof RedirectResponse) {
        return $response;
      }
    }
    
    if (!$response instanceof Response) {
      $response = new RedirectResponse('/');
    }
    
    return $response;
  }
  
  /**
   * @{inerhitDoc}
   */
  public function getEvent($eventName, array $fields = array())
  {
    try {
      $eventServiceId = $this->getEventServiceId($eventName);
      $event = clone ($this->container->get($eventServiceId));
    }
    catch (\Exception $e) {
      throw $e;
    }
    
    if ($fields) {
      foreach ($fields as $fieldKey => $fieldValue) {
        $setter = 'set' . $this->camelize($fieldKey);
        if (method_exists($event, $setter)) {
          $event->$setter($fieldValue);
        }
        else {
          throw new \RuntimeException(sprintf('Undefined setter method "%s" for set field key "%s".', $setter, $fieldKey));
        }
      }
    }
    
    return $event;
  }
  
  /**
   * @{inerhitDoc}
   */
  public function getEventServiceId($eventName)
  {
    $eventIds = $this->container->getParameter('ng_token_event.event_names');
    
    if (!isset($eventIds[$eventName])) {
      throw new EventNotFoundException(sprintf('Not found event by name "%s".', $eventName));
    }
    
    return $eventIds[$eventName];
  }
  
  /**
   * Camelizes a given string.
   *
   * @param  string $string Some string.
   *
   * @return string The camelized version of the string.
   */
  private function camelize($string)
  {
    return preg_replace_callback('/(^|_|\.)+(.)/', function ($match) { return ('.' === $match[1] ? '_' : '').strtoupper($match[2]); }, $string);
  }
}