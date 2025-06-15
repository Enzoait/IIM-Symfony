<?php

namespace App\MessageHandler;

use App\Message\MillePoints;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class MillePointsHandler
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function __invoke(MillePoints $message)
    {
        $repo = $this->em->getRepository(Users::class);
        $users = $repo->findBy(['disabled' => false]);

        foreach ($users as $user) {
            $user->setPoints($user->getPoints() + $message->getPoints());
        }

        $this->em->flush();
    }
}
