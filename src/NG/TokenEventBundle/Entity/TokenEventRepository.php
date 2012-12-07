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

use Doctrine\ORM\EntityRepository as BaseRepository;

/**
 * Repository for TokenEvent
 */
class TokenEventRepository extends BaseRepository
{
  /**
   * Get Event by token
   *
   * @param string $token
   */
  public function findByToken($token)
  {
    if (!is_string($token)) {
      throw new RuntimeException('Token must be string value ' . gettype($token) . ' given.');
    }
    
    return $this->findOneBy(array('token' => $token));    
  }
}