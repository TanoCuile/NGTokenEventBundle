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

namespace NG\TokenEventBundle\Events;

use Symfony\Component\DependencyInjection\ContainerInterface,
    Symfony\Component\DependencyInjection\ContainerAware;

/**
 * Abstract class for token events
 */
abstract class AbstractEvent extends ContainerAware implements EventInterface
{ 
  // Priority of event
  protected $priority = 0;
  
  /**
   * Clone
   */
  public function __clone()
  {
    $this->priority = 0;
  }
  
  /**
   * Ger service from service container
   *
   * @param string $serviceName
   *
   * @return mixed
   */
  protected function get($serviceName)
  {
    return $this->container->get($serviceName);
  }
  
  /**
   * @{inerhitDoc}
   */
  public function setPriority($priority)
  {
    $this->priority = (int) $priority;
    
    return $this;
  }
  
  /**
   * @{inerhitDoc}
   */
  public function getPriority()
  {
    return $this->priority;
  }
  
  /**
   * @{inerhitDoc}
   */
  public function serialize()
  {
    $serializeData = array();
    foreach (get_object_vars($this) as $propertyKey => $propertyValue) {
      if (is_object($propertyValue)) {
        if ($propertyValue instanceof \Serializable) {
          $serializeData[$propertyKey] = $propertyValue;
        }
      }
      else {
        $serializeData[$propertyKey] = $propertyValue;
      }
    }
    
    return serialize($serializeData);
  }
  
  /**
   * @{inerhitDoc}
   */
  public function unserialize($data)
  {
    if (!$unserializedData = @unserialize($data)) {
      throw new \RuntimeException(sprintf('Can\'t unserialize data: %s', $data));
    }
    
    foreach ($unserializedData as $propertyKey => $propertyValue) {
      if (property_exists($this, $propertyKey)) {
        $this->{$propertyKey} = $propertyValue;
      }
      else {
        throw new \RuntimeException(sprintf(
          'Undefined property key "%s"',
          $propertyKey
        ));
      }
    }
  }
}