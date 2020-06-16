<?php

namespace App\Events\User;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Person\User\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

class UserSubscriber implements EventSubscriberInterface
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $encoder;
    /**
     * @var Security
     */
    private Security $security;

    public function __construct(UserPasswordEncoderInterface $encoder, Security $security)
    {
        $this->encoder = $encoder;
        $this->security = $security;
    }

    /**
     * @inheritDoc
     * @return mixed
     */
    public static function getSubscribedEvents()
    {
        //On va se brancher à la méthode encoderPassword juste avant l'écriture dans la BD
        return [
            KernelEvents::VIEW => [
                ['encodePassword', EventPriorities::PRE_WRITE],
                ['setAuthorForUser', EventPriorities::PRE_VALIDATE]
            ]
        ];
    }

    public function encodePassword(ViewEvent $event): void
    {
        $user = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        //On va se rassurer que nous avons une instance d'un utilisateur et qu'on est entrain de créer un nouveau
        if ($user instanceof User && $method === 'POST') {
            $hash = $this->encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
        }
    }

    public function setAuthorForUser(ViewEvent $event):void
    {
        $user = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        //On va se rassurer que nous avons une instance d'un utilisateur et qu'on est entrain de créer un nouveau
        if ($user instanceof User && $method === 'POST') {
            /** @var User $connectedUser */
            $connectedUser = $this->security->getUser();
            $user->setCreatedBy($connectedUser);
        }
    }
}
