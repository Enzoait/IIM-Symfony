<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use App\Entity\Product;
use App\Entity\Users;
use App\Entity\Notifs;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use DateTimeImmutable;

class AdminNotifsController extends AbstractController
{
    #[Route('/admin/notifs', name: 'app_admin_notifs')]
    public function index(Security $security, ManagerRegistry $doctrine): Response
    {
        $user = $security->getUser();
        $isAdmin = $user && in_array('ROLE_ADMIN', $user->getRoles());

        $notifList = $doctrine->getRepository(Notifs::class)->findAll();

        $userList = $doctrine->getRepository(Users::class)->findAll();

        $userMap = [];
        foreach ($userList as $u) {
            $userMap[$u->getId()] = $u->getName();
        }

        if (!$isAdmin) {
            return $this->redirectToRoute('app_products');
        }

        return $this->render('admin_notifs/index.html.twig', [
            'notifList' => array_map(function($n) use ($userMap) {
                return [
                    'id' => $n->getId(),
                    'label' => $n->getLabel(),
                    'userName' => $userMap[$n->getUserId()] ?? 'Utilisateur Inconnu',
                    'type' => $n->getType(),
                    'createdAt' => $n->getCreatedAt(),
                ];
            }, $notifList),
        ]);
    }
}