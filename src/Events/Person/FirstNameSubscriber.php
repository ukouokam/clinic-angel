<?php

namespace App\Events\Person;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Person\FirstName;
use App\Entity\Person\User\User;
use DateTime;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class FirstNameSubscriber implements EventSubscriberInterface
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
        return[
            KernelEvents::VIEW =>[
                ['setCreatedAtForm', EventPriorities::PRE_VALIDATE],
                ['setAuthor', EventPriorities::PRE_VALIDATE]
            ]
        ];
    }

    public function setAuthor(ViewEvent $event): void
    {
        $firstName = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        if ($firstName instanceof FirstName && $method === 'POST') {
            /** @var User $user */
            $user = $this->security->getUser();
            $firstName->setCreatedBy($user);
        }
    }

    public function setCreatedAtForm(ViewEvent $event): void
    {
        $firstName = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        if ($firstName instanceof FirstName && $method === 'POST') {
            $firstName->setCreatedAt(new DateTime('now'));
        }
    }
}