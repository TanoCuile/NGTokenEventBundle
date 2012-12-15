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

use FOS\UserBundle\Model\UserInterface,
    Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
    
/**
 * Executing of this event login user
 *
 * Require: FOSUserBundle
 */
class LoginEvent extends AbstractEvent
{
  // Priority
  protected $priority = -100;
  
  // User id
  protected $userId = NULL;
  
  // Firewall name
  protected $firewall = 'main';
  
  /**
   * Clone
   */
  public function __clone()
  {
    parent::__clone();
    $this->userId = NULL;
    $this->firewall = 'main';
    $this->priority = -100;
  }
  
  /**
   * Set user id
   *
   * @param integer|AdvancedUserInterface $userId
   *
   * return TokenEventLogin
   */
  public function setUserId($userId)
  {
    if (is_object($userId) && $userId instanceof UserInterface) {
      $userId = $userId->getId();
    }
    else {
      if(!$user = $this->container->get('fos_user.user_manager')->findUserBy(array('id' => $userId))) {
        throw new Exceptions\LoginUserNotFoundException(sprintf('Not found user by id "%s".', $userId));
      }
      
      $this->userId = $userId;
    }
    
    return $this;
  }
  
  /**
   * Set firewall
   *
   * @param string $firewall
   */
  public function setFirewall($firewall)
  {
    // TODO: Control firewall validate
    $this->firewall = $firewall;
    return $this;
  }
  
  /**
   * Get user id
   *
   * @return integer
   */
  public function getUserId()
  {
    return $this->userId;
  }
  
    
  /**
   * @{inerhitDoc}
   */
  public function execute()
  {
    $user = $this->container->get('fos_user.user_manager')->findUserBy(array('id' => $this->userId));
    
    if (!$user || !$user instanceof UserInterface) {
      throw new Exceptions\LoginUserNotFoundException(sprintf('Not found user by id "%s".', $this->userId));
    }
    
    $newToken = new UsernamePasswordToken($user, NULL, $this->firewall, $user->getRoles());        
    $this->container->get('security.context')->setToken($newToken);
  }
  
  /**
   * @{inerhitDoc}
   */
  public function getName()
  {
    return 'login';
  }
}