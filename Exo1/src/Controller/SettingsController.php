<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\UserForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Security;
use DateTimeImmutable;

final class SettingsController extends AbstractController
{
    #[Route('/settings', name: 'app_settings')]
    public function edit(Security $security, Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $security->getUser();
        $connectedUserId = $user->getId();
        $connectedUserName = $user->getName();
        $connectedUserFirstname = $user->getFirstname();
        $connectedUserId = (int)$connectedUserId ;

        $form = $this->createForm(UserForm::class, $user);
        $form->handleRequest($request);
        $date = new DateTimeImmutable();

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setUpdatedAt($date);
            $user->setId($connectedUserId);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_products');
        }

        return $this->render('settings/settings.html.twig', [
            'form' => $form->createView(),
            'connectedUserName' => $connectedUserName,
            'connectedUserFirstname' => $connectedUserFirstname,
        ]);
    }
}