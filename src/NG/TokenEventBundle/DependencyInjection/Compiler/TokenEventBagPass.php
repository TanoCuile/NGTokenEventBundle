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

namespace NG\TokenEventBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\DependencyInjection\Reference,
    Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException,
    NG\TokenEventBundle\TokenEvent\TokenEventInterface;


class TokenEventBagPass implements CompilerPassInterface
{
  /**
   * @{inerhitDoc}
   */
  public function process(ContainerBuilder $container)
  {    
    // All event names
    $eventNames = array();
    
    foreach ($container->findTaggedServiceIds('ng_token_event.event') as $id => $attributes) {
      $definition = $container->getDefinition($id);
      
      $refflectionClass = NULL;
      
      try {
        $class = $definition->getClass();
        
        if (strpos($class, '%') === 0) {
          $class = $container->getParameter(trim($class, '%'));
        }
        
        $refClass = new \ReflectionClass($class);
      }
      catch (\ReflectionException $refException) {
        throw new \RuntimeException(sprintf("Can not compile token event with id: %s", $id), 0, $refException);
      }
      catch (ParameterNotFoundException $e) {
        throw new \RuntimeException(sprintf('Can\'t compile token event type by ID: "%s"', $id), 0, $e);
      }
      
      if (!$refClass->implementsInterface('NG\\TokenEventBundle\\Events\\EventInterface')) {
        throw new \UnexpectedValueException(sprintf(
          'Class "%s" must be implements "NG\\TokenEventBundle\\Events\\EventInterface" interface.',
          $refClass->getName()
        ));
      }
      
      // Get new object service for cotnrol names
      $event = $container->get($id);
      
      // Add name to all names container
      $eventNames[$event->getName()] = $id;
      
      // Add container to event
      $definition
          ->addMethodCall('setContainer', array(new Reference('service_container')));
    }
    
    // Set parameter for control all event names
    $container->setParameter('ng_token_event.event_names', $eventNames);
  }
}
