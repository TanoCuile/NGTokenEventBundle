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

use NG\TokenEventBundle\TokenEvent\TokenEventInterface,
    NG\TokenEventBundle\Entity\TokenEvent,
    NG\TokenEventBundle\Component\TokenEventFactory\TokenEventFactory;

/**
 * Interface for TokenEventManager
 */
interface TokenEventManagerInterface
{
  /**
   * Create new token
   */
  public function createNewToken();
  
  /**
   * Save token
   *
   * @param TokenEvent $tokenEvent
   */
  public function saveToken(TokenEvent $tokenEvent);
  
  /**
   * Execute event
   *
   * @param TokenEvent
   */
  public function executeToken(TokenEvent $event);
  
  /**
   * Get new event
   *
   * @param string $eventName
   * @param array $fields
   */
  public function getEvent($eventName, array $fields = array());
  
  /**
   * Get event service id
   *
   * @param string $eventName
   */
  public function getEventServiceId($eventName);
  
  /**
   * Validate event
   *
   * @param TotenEvent $event
   */
  public function validateToken(TokenEvent $event);
}