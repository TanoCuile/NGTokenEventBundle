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

use Symfony\Component\Security\Core\User\AdvancedUserInterface,
    Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class TokenEventLogin extends AbstractTokenEvent
{    
  // User id
  protected $userId = 0;
  
  // Firewall name
  protected $firewall = '';
  
  /**
   * Construct object
   *
   * @param ContainerAwareInterface $container
   */
  public function __construct($container, $firewall)
  {
    $this->container = $container;
    
    $this->firewall = $firewall;
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
    if (is_object($userId) && $userId instanceof AdvancedUserInterface && method_exists($userId, 'getId')) {
      $userId = $userId->getId();
    }
    if ($userId && is_integer($userId)) {
      $this->userId = $userId;
      
      return $this;
    }
    throw new InvalidArgumentException(sprintf('User id is not valid, %s given', $userId));
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
   * Ececute callback
   */
  public function execute()
  {
    if ($this->userId) {
      $user = $this->container->get('fos_user.user_manager')->findUserBy(array('id' => $this->userId));
      if ($user && is_object($user) && $user instanceof AdvancedUserInterface) {
        $newToken = new UsernamePasswordToken($user, NULL, $this->firewall, $user->getRoles());        
        $this->container->get('security.context')->setToken($newToken);
      }
      else {
        throw new \RuntimeException(sprintf('User not found with id: %s.', $this->userId));
      }
    }
    else {
      throw new \RuntimeException('No user id for execute user login event');
    }
  }
  
}