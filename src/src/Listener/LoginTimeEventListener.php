<?php


namespace App\Listener;


use App\EventSubscriber\LoginTimeEvent;
use Doctrine\ORM\EntityManagerInterface;

class LoginTimeEventListener
{
    /** @var EntityManagerInterface $entityManager */
    private EntityManagerInterface $entityManager;

    /**
     * LoginTimeEventListener constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param LoginTimeEvent $event
     * @param string $eventName
     */
    public function setUserLastLoginTime(LoginTimeEvent $event, string $eventName)
    {
        $event->getUser()->setLastLogin(new \DateTime());
        $this->entityManager->persist($event->getUser());
        $this->entityManager->flush();
    }
}