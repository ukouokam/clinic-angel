<?php

namespace App\Events\Person;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Person\PhoneNumber;
use App\Entity\Person\User\User;
use DateTime;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class PhoneNumberSubscriber implements EventSubscriberInterface
{
    /**
     * @var Security
     */
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @inheritDoc
     * @return mixed
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => [
                ['setCreatedAt', EventPriorities::PRE_VALIDATE],
                ['setAuthor', EventPriorities::PRE_VALIDATE]
            ]
        ];
    }

    public function setAuthor(ViewEvent $event): void
    {
        $phoneNumber = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        if ($phoneNumber instanceof PhoneNumber && $method === 'POST') {
            /** @var User $user */
            $user = $this->security->getUser();
            $phoneNumber->setCreatedBy($user);
        }
    }

    public function setCreatedAt(ViewEvent $event): void
    {
        $phoneNumber = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        if ($phoneNumber instanceof PhoneNumber && $method === 'POST') {
            $phoneNumber->setCreatedAt(new DateTime('now'));
        }
    }
}