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

class TokenEventForward extends AbstractTokenEvent implements TokenEventResponsableInterface
{
  // Routing
  protected $router = '';
  
  // Router parameters
  protected $parameters = array();
  
  /**
   * Set router
   *
   * @param string $router
   *
   * @return TokenEventLogin
   */
  public function setRouter($router)
  {
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