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

use Symfony\Component\HttpFoundation\RedirectResponse,
    Symfony\Bundle\FrameworkBundle\Routing\Router,
    Symfony\Component\Routing\Exception\RouteNotFoundException,
    Symfony\Component\Routing\Exception\MissingMandatoryParametersException;

/**
 * Executing of this event redirect user to another page
 *
 * Event retust RequestResposse
 */
class ForwardEvent extends AbstractEvent
{
  // Prioroty
  protected $priority = 100;
  
  // Routing
  protected $routeName = '';
  
  // Router parameters
  protected $routeParameters = array();
  
  /**
   * Clone
   */
  public function __clone()
  {
    parent::__clone();
    $this->routeName = '';
    $this->routeParameters = array();
    $this->priority = 100;
  }
  
  /**
   * Set route name
   *
   * @param string $route
   *
   * @return TokenEventLogin
   */
  public function setRouteName($routeName)
  {
    $this->routeName = $routeName;
    
    return $this;
  }
  
  /**
   * Get router
   * 
   * @return string
   */
  public function getRouteName()
  {
    return $this->routeName;
  }
  
  /**
   * Set parameters for routing
   *
   * @param array $routeParameters
   *
   * @return TokenEventForward
   */
  public function setRouteParameters(array $routeParameters)
  {
    $this->routeParameters = $routeParameters;
  }
  
  /**
   * Get router parameters
   *
   * @return array
   */
  public function getRouteParameters()
  {
    return $this->routeParameters;
  }
  
  /**
   * @{inerhitDoc}
   */
  public function execute()
  {
    $url = $this->get('router')
        ->generate($this->routeName, $this->routeParameters);
        
    return new RedirectResponse($url);
  }
  
  /**
   * @{insehitDoc}
   */
  public function serialize()
  {
    $router = $this->get('router');
    $router->generate($this->routeName, $this->routeParameters);
    return parent::serialize();
  }
  
  /**
   * @{inerhitDoc}
   */
  public function getName()
  {
    return 'forward';
  }
}