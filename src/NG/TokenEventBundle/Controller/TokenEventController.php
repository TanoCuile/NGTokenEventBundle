<?php

/**
 * This file is part of the NGTokentEventBundle package
 *
 * (c) Shvets Serhiy <strifinder@gmail.com>
 *
 * For the full copyring and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace NG\TokenEventBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController,
    Symfony\Component\HttpFoundation\Response,
    Symfony\Component\HttpFoundation\Request,
    NG\TokenEventBundle\TokenEvent\TokenEventLogin,
    NG\TokenEventBundle\TokenEvent\TokenEventForward,
    NG\TokenEventBundle\Collection\Collection,
    NG\TokenEventBundle\Entity\TokenEvent;

class TokenEventController extends BaseController
{
  /**
   * Token action
   *
   * Execute tokens
   */
  public function executeAction(Request $request, $token) {
    $manager = $this->get('token_event.event_manager');
    
    $event = $manager->getEvent($token);
    
    return $manager->executeEvent($event);
  }
  
  /**
   * Token action
   *
   * Execute tokens
   */
  public function testAction(Request $request) {
    $manager = clone $this->get('token_event.event_manager');
    
    $login = $manager->getEmptyEvent('token_event.login');
    $login->setUserId(33);
    
    $forward = $manager->getEmptyEvent('token_event.forward');
    $forward->setRouter('faq');
    
    $event = new TokenEvent();
    
    $event->addAction($login);
    $event->addAction($forward);
    
    $manager->saveEvent($event);
    //$event = $manager->getEvent('a5ab1eb0ea6e07eba7684c36b7b4d148929c353dbf68ce846e08929f9f12e92c');
    //print '<pre>' . htmlspecialchars(print_r(var_dump($event),1)) . '</pre>';
    //$event->setPriority(13);
    //$event->setUserId(13);
    //
    //$for = clone $this->get('token_event.forward');
    //$for->setPriority(16);
    //$for->setRouter('faq');
    //
    //$collection = new Collection();
    //$collection->add($event);
    //$collection->add($for);
    //
    //$ser = serialize($collection);
    //
    //print '<pre>' . htmlspecialchars(print_r($ser,1)) . '</pre>';
    //
    //$collection = unserialize($ser);
    //
    //foreach ($collection as $event) {
    //  print '<pre>' . htmlspecialchars(print_r(var_dump($event),1)) . '</pre>';
    //}
    //print '<pre>' . htmlspecialchars(print_r(var_dump($collection),1)) . '</pre>';
    
    //$ser = serialize($event);
    //$ev = unserialize($ser);
    //$ev->setDefaults(clone $this->get('token_event.login'));
    //$ev->execute();
    return new Response('');
  }
}