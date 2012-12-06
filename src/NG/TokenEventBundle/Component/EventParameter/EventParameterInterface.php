<?php

/**
 * This file is part of the NGTokentEventBundle package
 *
 * (c) Shvets Serhiy <strifinder@gmail.com>
 *
 * For the full copyring and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace NG\TokenEventBundle\Component\EventParameter;

/**
 * Interface for event executing parameters
 */
interface EventParameterInterface
{
  /**
   * Get parameter by key
   *
   * @param string $key
   *
   * @return value|NULL
   */
  public function get(string $key);
  
  /**
   * Check if this parameter isset
   *
   * @param string $key
   *
   * @return bool
   */
  public function is(string $key);
  
  /**
   * Set param
   * 
   * @param $key
   *
   * @param $value
   *
   * @return EventParameterInterface
   */
  public function set($key, $value);
} 