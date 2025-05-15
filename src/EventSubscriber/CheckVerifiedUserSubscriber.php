<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\UserPassportInterface;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;

class CheckVerifiedUserSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            CheckPassportEvent::class => ['onCheckPassport', -10] ,
        ];
    }

    public function onCheckPassport(CheckPassportEvent $event): void
    {
        dump ($event);

        $passport = $event->getPassport();
        if (! $passport instanceof UserPassportInterface) {
            throw new \Exception(' invalid PASSPORT CUSTOM ');
        }

        $user = $passport->getUser(); 
        if (! $user instanceof UserInterface ) { 
            throw new \Exception(' invalid USER CUSTOM '); 

        } 

        if ( $user->getIsVerified() ) { 
            throw new CustomUserMessageAuthenticationException('User is not verified (custom message).');  
            // throw new \Exception(' User is not verified. '); 
        } 




    }
}
