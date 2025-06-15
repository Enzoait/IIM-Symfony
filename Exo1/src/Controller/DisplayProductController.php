<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Product;
use App\Entity\Users;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use DateTimeImmutable;
use App\Entity\Notifs;

final class DisplayProductController extends AbstractController
{
    #[Route('/display/product/{id}', name: 'app_display_product')]
    public function displayproduct(ManagerRegistry $doctrine, int $id, Security $security): Response
    {
        $connectedUser = $security->getUser();
        $userPoints = $connectedUser ? $connectedUser->getPoints() : 0;
        $isDisabled = $connectedUser ? $connectedUser->isDisabled() : false;
        $isAdmin = $connectedUser && in_array('ROLE_ADMIN', $connectedUser->getRoles());
        $product = $doctrine->getRepository(Product::class)->find($id);
        $user = $doctrine->getRepository(Users::class)->find($product->getUserId());

        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }

        return $this->render('display_product/displayproduct.html.twig', [
            'productId' => $product->getId(),
            'productName' => $product->getName(),
            'productDescription' => $product->getDescription(),
            'productPrice' => $product->getPrice(),
            'productCategory' => $product->getCategory(),
            'userName' => $user->getName(),
            'userId' => $user->getId(),
            'isAdmin' => $isAdmin,
            'userPoints' => $userPoints,
            'isDisabled' => $isDisabled,
        ]);
    }

    #[Route('/products/{id}/delete', name: 'app_product_delete', methods: ['POST'])]
    public function delete(Product $product, Request $request, ManagerRegistry $doctrine): Response
    {
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->request->get('_token'))) {
            $em = $doctrine->getManager();
            $em->remove($product);
            $em->flush();

            return $this->redirectToRoute('app_products');
        }

        throw $this->createAccessDeniedException('Token invalide');
    }

    #[Route('/products/{id}/buy', name: 'app_product_buy', methods: ['POST'])]
    public function buy(Product $product, Request $request, ManagerRegistry $doctrine, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour acheter un produit.');
        }
        $date = new DateTimeImmutable();
        $notif = new Notifs();
        if ($this->isCsrfTokenValid('buy' . $product->getId(), $request->request->get('_token'))) {
            
            $user->setUpdatedAt($date);
            $user->setPoints($user->getPoints() - $product->getPrice());
            $user->setOwnedProducts(array_merge($user->getOwnedProducts(), [$product->getName()]));
            $entityManager->persist($user);
            $entityManager->flush();

            $notif->setLabel('Produit acheté : ' . $product->getName());
            $notif->setType('Achat De Produit');
            $notif->setCreatedAt($date);
            $notif->setUpdatedAt($date);
            $notif->setUserId($user->getId());
            $entityManager->persist($notif);
            $entityManager->flush();

            return $this->redirectToRoute('app_products');
        }

        throw $this->createAccessDeniedException('Token invalide');
    }

}
