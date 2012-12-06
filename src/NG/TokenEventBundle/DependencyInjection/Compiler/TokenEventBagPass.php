<?php

/**
 * This file is part of the NGTokentEventBundle package
 *
 * (c) Shvets Serhiy <strifinder@gmail.com>
 *
 * For the full copyring and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace NG\TokenEventBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    NG\TokenEventBundle\TokenEvent\TokenEventInterface;

class TokenEventBagPass implements CompilerPassInterface
{
  public function process(ContainerBuilder $container)
  {    
    $bag = $container->getDefinition('token_event.event_bag');
    
    foreach ($container->findTaggedServiceIds('token_event.type') as $id => $attributes) {
      $event = $container->getDefinition($id);      
      
      $refflectionClass = NULL;
      
      try {
        $refflectionClass = new \ReflectionClass($event->getClass());
      }
      catch (\ReflectionException $e) {
        throw new \RuntimeException(sprintf("Can not compile token event with id: %s",
          $id
        ),0, $e);
      }
      
      $objectInterfaces = $refflectionClass->getInterfaceNames();
      
      $requiredInterfaces = array(
        'NG\\TokenEventBundle\\TokenEvent\\TokenEventInterface',
      );
      
      foreach ($requiredInterfaces as $interface) {
        if (!in_array($interface,$objectInterfaces)) {
          throw new \RuntimeException(sprintf('Token event %s do not implement require interface %s',
            $id,
            $interface
          ));
        }
      }
      
      $bag->addMethodCall('addTokenEvent', array($id, $event->getClass()));
    }
  }
}
