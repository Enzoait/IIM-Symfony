<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use App\Entity\Product;
use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use DateTimeImmutable;

class AdminDashboardController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'app_admin_dashboard')]
    public function index(Security $security, ManagerRegistry $doctrine): Response
    {
        $user = $security->getUser();
        $isAdmin = $user && in_array('ROLE_ADMIN', $user->getRoles());
        
        $userList = $doctrine->getRepository(Users::class)->findAll();

        if (!$isAdmin) {
            return $this->redirectToRoute('app_products');
        }

        return $this->render('admin_dashboard/admin.html.twig', [
            'userList' => array_map(function($u) {
                return [
                    'id' => $u->getId(),
                    'name' => $u->getName(),
                    'firstname' => $u->getFirstname(),
                    'email' => $u->getEmail(),
                    'points' => $u->getPoints(),
                    'roles' => implode(', ', $u->getRoles()),
                    'disabled' => $u->isDisabled(),
                ];
            }, $userList),
        ]);
    }

    #[Route('/users/{id}/desactivate', name: 'app_user_desactivate', methods: ['POST'])]
    public function desactivate(Users $user, Request $request, ManagerRegistry $doctrine, EntityManagerInterface $entityManager): Response
    {
        $date = new DateTimeImmutable();
        if ($this->isCsrfTokenValid('deactivate' . $user->getId(), $request->request->get('_token'))) {

            $user->setUpdatedAt($date);
            $isDisabled = $user->isDisabled();
            if ($isDisabled) {
                $user->setDisabled(false);
            }
            else {
                $user->setDisabled(true);
            }
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_dashboard');
        }

        throw $this->createAccessDeniedException('Token invalide');
    }
}