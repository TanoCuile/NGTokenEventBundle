<?php

/**
 * This file is part of the NGTokentEventBundle package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyring and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace NG\TokenEventBundle\Events;

use NG\TokenEventBundle\Exceptions\EventNotFoundException,
    NG\TokenEventBundle\Exceptions\EventAlreadyExistsException;

/**
 * Bag for control all events in token event
 */
class EventsBag implements EventsBagInterface
{
  // Events
  protected $_events = array();
  
  /**
   * Implements \Iterator
   */
  public function next()
  {
    return next($this->_events);
  }
  
  /**
   * Implements \Iterator
   */
  public function key()
  {
    return key($this->_events);
  }
  
  /**
   * Impelemets \Itaretor
   */
  public function current()
  {
    return current($this->_events);
  }
  
  /**
   * Implements \Iterator
   */
  public function valid()
  {
    return $this->current();
  }
  
  /**
   * Implements \Iterator
   */
  public function rewind()
  {
    return reset($this->_events);
  }
  
  /**
   * Implements \Countable
   */
  public function count()
  {
    return count($_events);
  }
  
  /**
   * Implements \ArrayAccess
   */
  public function offsetGet($offset)
  {
    if (!isset($this->_events[$offset])) {
      throw new EventNotFoundException(sprintf('Not found event by key "%s".', $offset));
    }
    
    return $this->_events[$offset];
  }
  
  /**
   * Impelemnts \ArrayAccess
   */
  public function offsetSet($offset, $value)
  {
    if (!is_object($value)) {
      throw new \InvalidArgumentException(sprintf('Token event must be a Event object, "%s" given.', gettype($value)));
    }
    
    if (!$value instanceof EventInterface) {
      throw new \InvalidArgumentException(sprintf('Token event must be instance "TokenEventInterface" interface.'));
    }
    
    return $this->add($value);
  }
  
  /**
   * Implements \ArrayAccess
   */
  public function offsetUnset($offset)
  {
    unset ($this->_events[$offset]);
  }
  
  /**
   * Implements \ArrayAccess
   */
  public function offsetExists($offset)
  {
    return isset($this->_events[$offset]);
  }
  
  /**
   * @{inerhitDoc}
   */
  public function has($eventName)
  {
    return is_object($eventName) && $eventName instanceof EventInterface ? $this->offsetExists($eventName->getName()) : $eventName;
  }
  
  /**
   * @{inerhitDoc}
   */
  public function add(EventInterface $tokenEvent)
  {
    if ($this->has($tokenEvent)) {
      throw new EventAlreadyExistsException(sprintf('Token event by key "%s" already exists.', $tokenEvent->getName()));
    }
    
    $this->_events[$tokenEvent->getName()] = $tokenEvent;
    
    return $this;
  }
  
  /**
   * @{inerhitDoc}
   */
  public function remove($tokenEvent)
  {
    $tokenEvent = $tokenEvent instanceof EventInterface ? $tokenEvent->getName() : $tokenEvent;
    if (!$this->has($tokenEvent)) {
      throw new EventNotFoundException(sprintf('Token event "%s" not found.', $tokenEvent));
    }
    
    $this->offsetUnset($tokenEvent);
    return $this;
  }
  
  /**
   * Implements \Serializable
   */
  public function serialize()
  {
    return serialize($this->_events);
  }
  
  /**
   * Implements \Serializable
   */
  public function unserialize($data)
  {
    if (!$data = @unserialize($data)) {
      throw new \RuntimeException('Can\'t unserialize object.');
    }
    
    $this->_events = $data;
  }
  
  /**
   * Sort
   *
   * @param Callbale $callback
   */
  public function uasort($callback)
  {
    if (!is_callable($callback)) {
      throw new \InvalidArgumentException('Callback must be a callable');
    }
    
    return uasort($this->_events, $callback);
  }
}