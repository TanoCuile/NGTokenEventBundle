<?php

/**
 * This file is part of the NGTokentEventBundle package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyring and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace  NG\TokenEventBundle\Events;

/**
 * Interface for control all events in token event
 */
interface EventsBagInterface extends \Iterator, \Countable, \ArrayAccess, \Serializable
{
  /**
   * Add event to storage
   *
   * @param TokenEventInterface $tokenEvent
   */
  public function add(EventInterface $tokenEvent);
  
  /**
   * Has token event
   *
   * @param TokenEventInterface|string $tokenEvent
   *
   * @return boolean
   */
  public function has($tokenEvent);
  
  /**
   * Remove event
   *
   * @param TokenEventInterface|string $tokenEvent
   */
  public function remove($tokenEvent);
}