NGTokenEventBundle for Symfony 2.*
==================================

Allows you to create an auto-executable actions available on a unique token.

#Configure routing.yml

_token_event:
    resource: '@NGTokenEventBundle/Resources/config/routing/routing.php'
    prefix: /


#Declaration custom event
``` php
// ...
use NG\TokenEventBundle\TokenEvent\TokenEventInterface;

class TokenEventCustom implements TokenEventInterface [TokenEventResponsableInterface/* Mark your event if it must return Response object */]
``` php
Declare as service

,,,
<service id="token_event.custom" class="Path\To\Your\TokenEvent\TokenEventCustom">
  <!--- Any arguments -->
  <tag name="token_event.type" />
</service>
,,,

#Using TokenEvent
``` php
// ...
use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController,
    Symfony\Component\HttpFoundation\Response,
    Symfony\Component\HttpFoundation\Request,
    NG\TokenEventBundle\TokenEvent\TokenEventLogin,
    NG\TokenEventBundle\TokenEvent\TokenEventForward,
    NG\TokenEventBundle\Collection\Collection,
    NG\TokenEventBundle\Entity\TokenEvent;

class CustomController extends BaseController
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
   * Test action just add TokenEvent
   */
  public function testAction(Request $request) {
  {
    $manager = clone $this->get('token_event.event_manager');
    
    $login = $manager->getEmptyEvent('token_event.login');
    $login->setUserId(33);
    
    $forward = $manager->getEmptyEvent('token_event.forward');
    $forward->setRouter('faq');
    
    $event = new TokenEvent();
    
    $event->addAction($login);
    $event->addAction($forward);
    
    $manager->saveEvent($event);
  }
}
``` php

If you have any problems, you have a suggestion for improving Bundle - will be happy to listen.