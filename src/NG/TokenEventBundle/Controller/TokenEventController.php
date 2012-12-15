<?php

/**
 * This file is part of the NGTokentEventBundle package
 *
 * (c) Shvets Serhiy <strifinder@gmail.com>
 *     Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyring and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace NG\TokenEventBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\HttpFoundation\Request,
    NG\TokenEventBundle\Exceptions\TokenException;

/**
 * Controller for executing TokenEvent
 */
class TokenEventController extends Controller
{
  /**
   * Token action
   *
   * @param Request $request
   * @param string $token
   */
  public function executeAction(Request $request, $token) {
    // Get token manager
    $tokenManager = $this->get('ng_token_event.event_manager');
    
    // Load token
    $token = $this->getDoctrine()
        ->getRepository('NGTokenEventBundle:TokenEvent')
        ->find($token);
    
    if (!$token) {
      throw $this->createNotFoundException();
    }
    
    // Control token errors
    try {
      $tokenManager->validateToken($token);
    }
    catch (TokenException $tokenException) {
      $this->get('logger')
          ->addWarning($tokenException->getMessage());
      
      throw $this->createNotFoundException();
    }
    
    // Execute token
    return $tokenManager->executeToken($token);
  }
}