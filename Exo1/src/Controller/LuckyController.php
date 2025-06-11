<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use App\Entity\Product;
use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LuckyController extends AbstractController
{
    #[Route('/products', name: 'app_products')]
    public function products(Security $security): Response
    {
        $user = $security->getUser();
        $connectedUser = $user ? $user->getName() : null;
        $isAdmin = $user && in_array('ROLE_ADMIN', $user->getRoles());

        $productList = [
            (new Product())->setName('Product 1')->setPrice(100)->setCategory('Category A')->setDescription('Description 1')->setUserId(1),
            (new Product())->setName('Product 2')->setPrice(200)->setCategory('Category B')->setDescription('Description 2')->setUserId(2),
            (new Product())->setName('Product 3')->setPrice(300)->setCategory('Category C')->setDescription('Description 3')->setUserId(3),
        ];

        return $this->render('lucky/products.html.twig', [
            'connectedUser' => $connectedUser,
            'isAdmin' => $isAdmin,
            'productList' => array_map(function($p) {
                return [
                    'name' => $p->getName(),
                    'description' => $p->getDescription(),
                ];
            }, $productList),
        ]);
    }
}