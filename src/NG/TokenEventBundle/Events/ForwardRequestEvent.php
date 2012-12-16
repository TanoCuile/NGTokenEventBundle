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

use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Executing of this event redirect user to another page
 *
 * Event retust RequestResposse
 */
class ForwardRequestEvent extends AbstractEvent
{
  // Prioroty
  protected $priority = 100;
  
  /**
   * Clone
   */
  public function __clone()
  {
    parent::__clone();
    $this->priority = 100;
  }
  
  /**
   * @{inerhitDoc}
   */
  public function execute()
  {
    $request = $this->get('request');
    $url = '/' . ltrim($request->get('forward', '/'), '/');
    return new RedirectResponse($url);
  }
  
  /**
   * @{inerhitDoc}
   */
  public function getName()
  {
    return 'forward_request';
  }
}