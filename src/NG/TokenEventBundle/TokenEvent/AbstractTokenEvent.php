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

use NG\TokenEventBundle\Component\EventParameter\EventParameterInterface,
    Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Abstract class for token events
 */
abstract class AbstractTokenEvent implements TokenEventInterface
{
  //ContainerAware
  protected $container;
  
  // Priority of event
  protected $priority = 0;
  
  /**
   * Construct object
   *
   * @param ContainerAwareInterface $container
   */
  public function __construct(ContainerInterface $container)
  {
    $this->container = $container;
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
  public function getExcludedFields()
  {
    return array(
      'container' => 'container',
    );
  }
  
  /**
   * @{inerhitDoc}
   */
  public function serialize() {
    $fields = get_object_vars($this);
    
    $fields = array_diff_key($fields, $this->getExcludedFields());
    return serialize($fields);
  }
  
  /**
   * @{inerhitDoc}
   */
  public function unserialize($serialized) {
    $fields = unserialize($serialized);
    foreach ($fields as $name => $value) {
      if (property_exists($this, $name)) {
        $this->$name = $value;
      }
      else {
        throw new \InvalidArgumentException(sprintf('Property %s is not exist.', $name));
      }
    }
  }
  
  /**
   * @{inerhitDoc}
   */
  public function setDefaults(TokenEventInterface $pattern)
  {
    $this::varTransfer($pattern, $this);
    
    return $this;
  }
  
  /**
   * Function transfer default fields from one object to another
   *
   * @param AbstractTokenEvent $pattern - has default fields
   * @param AbstractTokenEvent $workObject - has custom fields
   */
  static protected function varTransfer(self $pattern, self $workObject)
  {
    if (!($workObject instanceof $pattern)) {
      throw new \InvalidArgumentException(sprintf('Arguments must have same class, $pattern (%s), $workObject (%s) - given', get_class($pattern), get_class($workObject)));
    }
    $vars = get_object_vars($pattern);
    
    $defFields = array_keys($workObject->getExcludedFields());
    
    foreach ($defFields as $field) {
      $workObject->$field = $pattern->$field;
    }
  }
}