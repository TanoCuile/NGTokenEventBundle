NGTokenEventBundle for Symfony 2.*
==================================

Allows you to create an auto-executable actions available on a unique token.

#Configure routing.yml
``` yml
_token_event:
    resource: '@NGTokenEventBundle/Resources/config/routing.php'
    prefix: /
```

#Declaration custom event
``` php
// ...
use NG\TokenEventBundle\Events\AbstractEvent;

class EventCustom extends AbstractEvent
{
//
}
``` 
Declare as service

``` xml
<service id="token_event.custom" class="Path\To\Your\TokenEvent\TokenEventCustom">
  <!--- Any arguments -->
  <tag name="ng_token_event.type" />
</service>
```

#Using TokenEvent 
``` php
$tokenEventManager = $this->get('ng_token_event.event_manager');
        
$forwardEvent = $tokenEventManager->getEvent('forward', array(
  'routeName' => 'api',
  'routeParameters' => array('apiMethod' => 'userIsLogin')
));

// Must be enabled (FOSUserBundle)
$loginEvent = $tokenEventManager->getEvent('login', array('userId' => 4));

// Create a new token
$newToken = $tokenEventManager->createNewToken();

$newToken
    ->addEvent($forwardEvent)
    ->addEvent($loginEvent);

$tokenEventManager
        ->saveToken($newToken);
```

If you have any problems, you have a suggestion for improving Bundle - will be happy to listen.