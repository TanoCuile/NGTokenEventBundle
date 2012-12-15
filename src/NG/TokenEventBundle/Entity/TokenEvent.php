<?php

/**
 * This file is part of the NGTokentEventBundle package
 *
 * (c) Shvets Serhiy <strifinder@gmail.com>
 *     Zhuk Vitaliy <zhuk2205@gmail.com>
 *
 * For the full copyring and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace NG\TokenEventBundle\Entity;

use Doctrine\ORM\Mapping as ORM,
    Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity,
    Symfony\Component\DependencyInjection\ContainerInterface,
    NG\TokenEventBundle\Events\EventInterface,
    NG\TokenEventBundle\Events\EventsBagInterface,
    NG\TokenEventBundle\Events\EventsBag;

/**
 * SPS\Bundles\HomeBundle\Entity\Answer
 *
 * @ORM\Table(name="token_events")
 * @ORM\Entity()
 */
class TokenEvent
{
  /**
   * @var string $token
   * 
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="NONE")
   * @ORM\Column(name="token", type="string", length=255)
   */
  protected $token;
  
  /**
   * @var string $actions
   *
   * @ORM\Column(name="events", type="object")
   */
  protected $events;
  
  /**
   * @var string $toTime
   *
   * @ORM\Column(name="to_time", type="datetime", nullable=true)
   */
  protected $toTime;
  
  /**
   * @var string $ip
   * 
   * @ORM\Column(name="ip", type="string", length=32, nullable=true)
   */
  protected $ip;
  
  /**
   * @var integer $countUsed
   *
   * @ORM\Column(name="count_used", type="integer")
   */
  protected $countUsed = 0;
  
  /**
   * @var integer $maxCount
   *
   * @ORM\Column(name="max_count_usage", type="integer")
   */
  protected $maxCountUsage = 0;
    
  /**
   * @ORM\Column(name="last_used", type="datetime", nullable=true)
   */
  protected $lastUsed = NULL;
  
  /**
   * @var bool $blocked
   *
   * @ORM\Column(name="blocked", type="boolean")
   */
  protected $blocked = FALSE;
  
  /**
   * 
   */
  public function __construct()
  {
    $this->events = new EventsBag;
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
  public function setToTime(\DateTime $toTime)
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
      $this->countUsed = (int) $countUsed;
  
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
   * @param integer $maxCountUsage
   * @return TokenEvent
   */
  public function setMaxCountUsage($maxCountUsage)
  {
      $this->maxCountUsage = (int) $maxCountUsage;
  
      return $this;
  }
  
  /**
   * Get maxCount
   *
   * @return integer 
   */
  public function getMaxCountUsage()
  {
      return $this->maxCountUsage;
  }
  
  /**
   * Set last use
   *
   * @param \DateTime $lastUsed
   */
  public function setLastUsed(\DateTime $lastUsed)
  {
    $this->lastUsed = $lastUsed;
    return $this;
  }
  
  /**
   * Get last use
   *
   * @return \DateTime
   */
  public function getLastUsed()
  {
    return $this->lastUsed;
  }
  
  /**
   * Set blocked
   *
   * @param boolean $blocked
   * @return TokenEvent
   */
  public function setBlocked($blocked)
  {
      $this->blocked = (bool) $blocked;
  
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
   * @param EventsBagInterface $actions
   * @return TokenEvent
   */
  public function setEvents(EventsBagInterface $events)
  {
      $this->events = $events;
      
      return $this;
  }
  
  /**
   * Get actions
   *
   * @return string 
   */
  public function getEvents()
  {
      return $this->events;
  }
  
  /**
   * Add action
   *
   * @param TokenEventInterface $event
   *
   * @return TokenEvent
   */
  public function addEvent(EventInterface $event)
  {
    $this->events->add($event);
    
    return $this;
  }
}