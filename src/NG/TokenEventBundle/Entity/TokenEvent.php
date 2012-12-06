<?php

/**
 * This file is part of the NGTokentEventBundle package
 *
 * (c) Shvets Serhiy <strifinder@gmail.com>
 *
 * For the full copyring and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace NG\TokenEventBundle\Entity;

use Doctrine\ORM\Mapping as ORM,
    Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity,
    Symfony\Component\DependencyInjection\ContainerInterface,
    Symfony\Component\Validator\Constraints as Assert,
    NG\TokenEventBundle\Collection\Collection,
    NG\TokenEventBundle\TokenEvent\TokenEventInterface;

/**
 * SPS\Bundles\HomeBundle\Entity\Answer
 *
 * @ORM\Table(name="token_events")
 * @ORM\Entity(repositoryClass="NG\TokenEventBundle\Entity\TokenEventRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields="token")
 */
class TokenEvent
{
  /**
   * @var integer $id
   *
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;
  
  /**
   * @var string $token
   *
   * @ORM\Column(name="token", type="string", length=255)
   * @Assert\MaxLength(255)
   */
  private $token;
  
  /**
   * @var string $actions
   *
   * @ORM\Column(name="actions", type="text")
   */
  private $actions;
  
  /**
   * @var string $toTime
   *
   * @ORM\Column(name="to_time", type="datetime", nullable=true)
   * @Assert\DateTime()
   */
  private $toTime;
  
  /**
   * @var string $ip
   * 
   * @ORM\Column(name="ip", type="string", length=32, nullable=true)
   * @Assert\Ip(version="all")
   */
  private $ip;
  
  /**
   * @var integer $countUsed
   *
   * @ORM\Column(name="count_used", type="integer")
   */
  private $countUsed = 0;
  
  /**
   * @var integer $maxCount
   *
   * @ORM\Column(name="max_count", type="integer", nullable=true)
   */
  private $maxCount = NULL;
    
  /**
   * @var bool $blocked
   *
   * @ORM\Column(name="blocked", type="boolean")
   */
  private $blocked = FALSE;
  
  /**
   * Construct object
   */
  public function __construct ()
  {
    $this->actions = new Collection();
  }
  

  /**
   * Get id
   *
   * @return integer 
   */
  public function getId()
  {
      return $this->id;
  }

  /**
   * Set token
   *
   * @param string $token
   * @return TokenEvent
   */
  public function setToken($token)
  {
      $this->token = $token;
  
      return $this;
  }
  
  /**
   * Get token
   *
   * @return string 
   */
  public function getToken()
  {
      return $this->token;
  }
  
  /**
   * Set toTime
   *
   * @param \DateTime $toTime
   * @return TokenEvent
   */
  public function setToTime($toTime)
  {
      $this->toTime = $toTime;
  
      return $this;
  }
  
  /**
   * Get toTime
   *
   * @return \DateTime 
   */
  public function getToTime()
  {
      return $this->toTime;
  }
  
  /**
   * Set ip
   *
   * @param string $ip
   * @return TokenEvent
   */
  public function setIp($ip)
  {
      $this->ip = $ip;
  
      return $this;
  }
  
  /**
   * Get ip
   *
   * @return string 
   */
  public function getIp()
  {
      return $this->ip;
  }
  
  /**
   * Set countUsed
   *
   * @param integer $countUsed
   * @return TokenEvent
   */
  public function setCountUsed($countUsed)
  {
      $this->countUsed = $countUsed;
  
      return $this;
  }
  
  /**
   * Get countUsed
   *
   * @return integer 
   */
  public function getCountUsed()
  {
      return $this->countUsed;
  }
  
  /**
   * Set maxCount
   *
   * @param integer $maxCount
   * @return TokenEvent
   */
  public function setMaxCount($maxCount)
  {
      $this->maxCount = $maxCount;
  
      return $this;
  }
  
  /**
   * Get maxCount
   *
   * @return integer 
   */
  public function getMaxCount()
  {
      return $this->maxCount;
  }
  
  /**
   * Set blocked
   *
   * @param boolean $blocked
   * @return TokenEvent
   */
  public function setBlocked($blocked)
  {
      $this->blocked = $blocked;
  
      return $this;
  }
  
  /**
   * Get blocked
   *
   * @return boolean 
   */
  public function getBlocked()
  {
      return $this->blocked;
  }
  
  /**
   * Set actions
   *
   * @param string $actions
   * @return TokenEvent
   */
  public function setActions($actions)
  {
      $this->actions = $actions;
  
      return $this;
  }
  
  /**
   * Get actions
   *
   * @return string 
   */
  public function getActions()
  {
      return $this->actions;
  }
  
  /**
   * Add action
   *
   * @param TokenEventInterface $event
   *
   * @return TokenEvent
   */
  public function addAction(TokenEventInterface $event)
  {
    $this->actions->add($event);
    
    return $this;
  }
   
  
  /*******
   * LifeCycles
   *******/
  
  /**
   * @ORM\PostLoad
   */
  public function postLoad()
  {
    $this->actions = unserialize(base64_decode($this->actions));
  }
  
  /**
   * @ORM\PrePersist
   */
  public function prePersist()
  {
    $this->actions = base64_encode(serialize($this->actions));
  }
  
  /**
   * @ORM\PreUpdate
   */
  public function preUpdate()
  {
    $this->actions = base64_encode(serialize($this->actions));
  }  
}