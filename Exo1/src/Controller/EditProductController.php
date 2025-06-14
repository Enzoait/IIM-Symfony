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
use DateTimeImmutable;

final class EditProductController extends AbstractController
{
    #[Route('/product/edit/{id}', name: 'app_product_edit')]
    public function edit(Security $security, Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $user = $security->getUser();
        $connectedUserId = $user->getId();
        $connectedUserId = (int)$connectedUserId ;

        $product = $entityManager->getRepository(Product::class)->find($id);
        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }

        $form = $this->createForm(ProductForm::class, $product);
        $form->handleRequest($request);
        $date = new DateTimeImmutable();

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setUpdatedAt($date);
            $product->setUserId($connectedUserId);
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('app_products');
        }

        return $this->render('product/edit.html.twig', [
            'form' => $form->createView(),
            'productName' => $product->getName(),
            'productDescription' => $product->getDescription(),
            'productPrice' => $product->getPrice(),
            'productCategory' => $product->getCategory(),
        ]);
    }
}