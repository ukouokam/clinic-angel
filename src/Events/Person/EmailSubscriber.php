<?php

namespace App\Events\Person;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Person\Email;
use App\Entity\Person\User\User;
use DateTime;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class EmailSubscriber implements EventSubscriberInterface
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
                ['setAuthor', EventPriorities::PRE_VALIDATE],
                ['setCreatedAt', EventPriorities::PRE_VALIDATE]
            ]
        ];
    }

    public function setAuthor(ViewEvent $event): void
    {
        $email = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        if ($email instanceof Email && $method === 'POST') {
            /** @var User $user */
            $user = $this->security->getUser();
            $email->setCreatedBy($user);
        }
    }

    public function setCreatedAt(ViewEvent $event): void
    {
        $email = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        if ($email instanceof Email && $method === 'POST') {
            $email->setCreatedAt(new DateTime('now'));
        }
    }
}
