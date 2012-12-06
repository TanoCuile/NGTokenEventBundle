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
    NG\TokenEventBundle\Component\TokenEventFactory\TokenEventFactory;

/**
 * 
 */
interface TokenEventManagerInterface
{
  /**
   * Save TokenEvent
   *
   * @param TokenEvent $event
   */
  public function saveEvent(TokenEvent $event);
  
  /**
   * Get Event by Token
   *
   * @param string $token
   */
  public function getEvent($token);  
}