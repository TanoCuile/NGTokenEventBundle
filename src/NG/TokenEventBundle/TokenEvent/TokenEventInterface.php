<?php


/**
 * This file is part of the NGTokentEventBundle package
 *
 * (c) Shvets Serhiy <strifinder@gmail.com>
 *
 * For the full copyring and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace NG\TokenEventBundle\TokenEvent;

use NG\TokenEventBundle\Component\EventParameter\EventParameterInterface;

/**
 * Interface for token events
 */
interface TokenEventInterface extends \Serializable
{
  /**
   * Set priority for events call by order
   *
   * @param integer $priority
   */
  public function setPriority($priority);
  
  /**
   * Get priority
   */
  public function getPriority();
  
  /**
   * Ececute callback
   */
  public function execute();
  
  /**
   * Return excluded fields
   */
  public function getExcludedFields();
  
  /**
   * Set defaults from $pattern
   *
   * @param AbstractTokenEvent @patternt contain default fields
   *
   * @return AbstractTokenEvent
   */
  public function setDefaults(TokenEventInterface $pattern);
}