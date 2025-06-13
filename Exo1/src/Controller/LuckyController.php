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

class LuckyController extends AbstractController
{
    #[Route('/products', name: 'app_products')]
    public function products(Security $security, ManagerRegistry $doctrine): Response
    {
        $user = $security->getUser();
        $connectedUser = $user ? $user->getName() : null;
        $isAdmin = $user && in_array('ROLE_ADMIN', $user->getRoles());

        $productList = $doctrine->getRepository(Product::class)->findAll();

        return $this->render('lucky/products.html.twig', [
            'connectedUser' => $connectedUser,
            'isAdmin' => $isAdmin,
            'productList' => array_map(function($p) {
                return [
                    'name' => $p->getName(),
                    'description' => $p->getDescription(),
                    'price' => $p->getPrice(),
                    'category' => $p->getCategory(),
                ];
            }, $productList),
        ]);
    }
}