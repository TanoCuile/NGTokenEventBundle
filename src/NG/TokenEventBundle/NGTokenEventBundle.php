<?php

/**
 * This file is part of the NGTokentEventBundle package
 *
 * (c) Shvets Serhiy <strifinder@gmail.com>
 *
 * For the full copyring and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace NG\TokenEventBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    NG\TokenEventBundle\DependencyInjection\Compiler\TokenEventBagPass;

class NGTokenEventBundle extends Bundle
{
  public function build(ContainerBuilder $buider) {
    parent::build($buider);
    
    $buider->addCompilerPass(new TokenEventBagPass());
  }  
}