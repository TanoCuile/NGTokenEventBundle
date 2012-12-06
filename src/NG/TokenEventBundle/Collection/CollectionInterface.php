<?php

/**
 * This file is part of the NGTokentEventBundle package
 *
 * (c) Shvets Serhiy <strifinder@gmail.com>
 *
 * For the full copyring and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace NG\TokenEventBundle\Collection;

use NG\TokenEventBundle\TokenEvent\TokenEventInterface;

interface CollectionInterface extends \Iterator
{
  /**
   * Add new TokenEventInterface
   */
  public function add(TokenEventInterface $event);
}