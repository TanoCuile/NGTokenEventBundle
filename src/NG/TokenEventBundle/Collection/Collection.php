<?php

/**
 * This file is part of the NGTokentEventBundle package
 *
 * (c) Shvets Serhiy <strifinder@gmail.com>
 *
 * For the full copyring and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace NG\TokenEventBundle\Collection;

use NG\TokenEventBundle\TokenEvent\TokenEventInterface;

/**
 * Collection for TokenEvents
 */
class Collection implements CollectionInterface
{  
  // Array of TokenEventInterface
  protected $events = array();
  
  // Curent position
  protected $current_key = NULL;
  
  /**
   * @{InerhitDoc}
   */
  public function current()
  {
    $keys = array_keys($this->events);
    return $this->events[$keys[$this->current_key]];
  }
  
  /**
   * @{InerhitDoc}
   */
  public function key()
  {
    return $this->current_key;
  }
  
  /**
   * @{InerhitDoc}
   */
  public function next()
  {
    ++$this->current_key;
  }
  
  /**
   * @{InerhitDoc}
   */
  public function rewind()
  {
    $this->current_key = 0;
  }
  
  /**
   * @{InerhitDoc}
   */
  public function valid()
  {
    $keys = array_keys($this->events);
    return isset($keys[$this->current_key]) && isset($this->events[$keys[$this->current_key]]);
  }
  
  /**
   * @{inerhitDoc}
   */
  public function add(TokenEventInterface $event)
  {
    if (!empty($event)) {
      $key = $event->getPriority();
      
      while (isset($this->events[$key])) {
        ++$key;
      }
      
      $this->events[$key] = $event;
            
      return $this;
    }
    throw new \InvalidArgumentException(sprintf('TokenEvent, passed to %s function, must be not empty...', __FUNCTION__));
  }
}