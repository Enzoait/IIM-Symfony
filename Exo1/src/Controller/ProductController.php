<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Security;
use Doctrine\Persistence\ManagerRegistry;
use DateTimeImmutable;

final class ProductController extends AbstractController
{
    #[Route('/product/create', name: 'app_product_create')]
    public function create(Security $security, Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $security->getUser();
        $connectedUserId = $user ? $user->getId() : null;
        $product = new Product();
        $form = $this->createForm(ProductForm::class, $product);
        $form->handleRequest($request);
        $date = new DateTimeImmutable();

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setCreatedAt($date);
            $product->setUpdatedAt($date);
            $product->setUserId($connectedUserId);
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('app_products');
        }

        return $this->render('product/create.html.twig', [
            'form' => $form->createView(),
            'connectedUserId' => $connectedUserId,
        ]);
    }

}