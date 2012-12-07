<?php

/**
 * This file is part of the NGTokentEventBundle package
 *
 * (c) Shvets Serhiy <strifinder@gmail.com>
 *
 * For the full copyring and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace NG\TokenEventBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController,
    Symfony\Component\HttpFoundation\Response,
    Symfony\Component\HttpFoundation\Request,
    NG\TokenEventBundle\TokenEvent\TokenEventLogin,
    NG\TokenEventBundle\TokenEvent\TokenEventForward,
    NG\TokenEventBundle\Collection\Collection,
    NG\TokenEventBundle\Entity\TokenEvent;

/**
 * Controller for executing TokenEvent
 */
class TokenEventController extends BaseController
{
  /**
   * Token action
   *
   * Execute tokens
   */
  public function executeAction(Request $request, $token) {
    $manager = $this->get('token_event.event_manager');
    
    $event = $manager->getEvent($token);
    
    return $manager->executeEvent($event);
  }
}