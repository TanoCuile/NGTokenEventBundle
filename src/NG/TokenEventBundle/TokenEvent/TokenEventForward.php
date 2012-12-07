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

use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Executing of this event redirect user to another page
 *
 * Event retust RequestResposse
 */
class TokenEventForward extends AbstractTokenEvent implements TokenEventResponsableInterface
{
  // Routing
  protected $router = '';
  
  // Router parameters
  protected $parameters = array();
  
  /**
   * Check router
   *
   * @param string $router
   * 
   * @return bool
   */
  public function checkRouter($router)
  {
    $collection = $this->container->get('router')->getRouteCollection()->all();
    
    foreach ($collection as $name => $val) {
      if ($name == $router) {
        return TRUE;
      }
    }
    return FALSE;
  }
  
  /**
   * Set router
   *
   * @param string $router
   *
   * @return TokenEventLogin
   */
  public function setRouter($router)
  {
    if ($this->container && !$this->checkRouter($router)){
      throw new \RuntimeException('You have not router: ' . $router . '.');
    }
    $this->router = $router;
    
    return $this;
  }
  
  /**
   * Get router
   * 
   * @return string
   */
  public function getRouter()
  {
    return $this->router;
  }
  
  /**
   * Set parameters for routing
   *
   * @param array $parameters
   *
   * @return TokenEventForward
   */
  public function setRouterParameters(array $parameters)
  {
    $this->parameters = $parameters;
  }
  
  /**
   * Get router parameters
   *
   * @return array
   */
  public function getRouterParameters()
  {
    return $this->parameters;
  }
  
  /**
   * @{inerhitDoc}
   */
  public function execute()
  {
    return new RedirectResponse($this->container->get('router')->getGenerator()->generate($this->router, $this->parameters, FALSE));
  }
}