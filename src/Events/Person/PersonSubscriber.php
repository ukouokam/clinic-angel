<?php

namespace App\Events\Person;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Person\Person;
use App\Entity\Person\User\User;
use Cocur\Slugify\Slugify;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class PersonSubscriber implements EventSubscriberInterface
{
    /**
     * @var Security
     */
    private Security $security;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $manager;

    public function __construct(Security $security, EntityManagerInterface $manager)
    {
        $this->security = $security;
        $this->manager = $manager;
    }

    /**
     * @inheritDoc
     * @return mixed
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => [
                ['setUserForPerson', EventPriorities::PRE_VALIDATE],
                ['setCreatedAtForPerson', EventPriorities::PRE_VALIDATE],
                ['setSlugForPerson', EventPriorities::POST_WRITE]
            ]
        ];
    }

    public function setUserForPerson(ViewEvent $event): void
    {
        $person = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        if ($person instanceof Person && $method === 'POST') {
            //Choper l'utilisateur actuellement connecté
            /** @var User $user */
            $user = $this->security->getUser();
            //Assigner l'utilisateur à la personne qu'on est entrain de créer
            $person->setCreatedBy($user);
        }

    }
    public function setCreatedAtForPerson(ViewEvent $event): void
    {
        $person = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        if ($person instanceof Person && $method === 'POST') {
            $person->setCreatedAt(new DateTime('now'));
        }
    }

    /**
     * Après l'enregistrement en base de données, on génère le slug de la personne et que l'on persiste à nouveau
     * @param ViewEvent $event
     */
    public function setSlugForPerson(ViewEvent $event):void
    {
        $person = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        if ($person instanceof Person && $method === 'POST') {
            $slugger = new Slugify();
            $firstName = $person->getFirstName();
            $slug = is_null($firstName)
                ? $slugger->slugify($person->getId() . ' ' . $person->getLastName())
                : $slugger->slugify($person->getId() . ' ' . $person->getLastName() . ' ' . $firstName);
            $person->setSlug($slug);
            $this->manager->flush();
        }
    }
}