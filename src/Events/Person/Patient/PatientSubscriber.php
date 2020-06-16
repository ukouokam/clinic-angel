<?php

namespace App\Events\Person\Patient;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Person\Patient\Patient;
use App\Entity\Person\User\User;
use DateTime;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class PatientSubscriber implements EventSubscriberInterface
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
        $patient = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        if ($patient instanceof Patient && $method === 'POST') {
            /** @var User $user */
            $user = $this->security->getUser();
            $patient->setCreatedBy($user);
        }
    }

    public function setCreatedAt(ViewEvent $event): void
    {
        $patient = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        if ($patient instanceof Patient && $method === 'POST') {
            $patient->setSaveAt(new DateTime('now'));
        }
    }
}