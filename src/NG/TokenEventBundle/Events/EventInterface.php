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

namespace NG\TokenEventBundle\Events;

use NG\TokenEventBundle\Component\EventParameter\EventParameterInterface,
    Symfony\Component\DependencyInjection\ContainerAwareInterface;

/**
 * Interface for token events
 */
interface EventInterface extends \Serializable, ContainerAwareInterface
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
   * Get name
   */
  public function getName();
  
}