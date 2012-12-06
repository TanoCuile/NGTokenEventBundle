<?php

/**
 * This file is part of the NGTokentEventBundle package
 *
 * (c) Shvets Serhiy <strifinder@gmail.com>
 *
 * For the full copyring and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace NG\TokenEventBundle\Component\TokenEventBag;

use NG\TokenEventBundle\TokenEvent\TokenEventInterface,
    Symfony\Component\DependencyInjection\ContainerInterface;

class TokenEventBag
{ 
  // Events array
  protected $events = array();
  
  // ContainerInterface contoiner
  protected $container = NULL;
  
  /**
   * Construnct object
   *
   * @param ContainerInterface $container
   */
  public function __construct(ContainerInterface $container)
  {
    $this->container = $container;
  } 
  
  /**
   * Add new event to bag
   *
   * @param string $event_id
   * @param string $class
   *
   * @return TokenEventBag
   */
  public function addTokenEvent($event_id, $class)
  {
    $this->events[$class] = $event_id;
    
    return $this;
  }
  
  /**
   * Get default event object by class name
   *
   * @param string $class
   *
   * @return TokenEventInterface
   */
  public function getTokenEvent($class)
  {
    if (!is_string($class)) {
      throw new \InvalidArgumentException(sprintf('$class must be string value, %s given.', gettype($class)));      
    }
    if (isset($this->events[$class]) && $this->container->has($this->events[$class])) {
      return $this->container->get($this->events[$class]);
    }
    throw new \InvalidArgumentException(sprintf('No token event found with class %s.', $class));
  }
}