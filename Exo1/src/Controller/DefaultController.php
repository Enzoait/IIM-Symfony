<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * DefaultController : La page d'accueil doit être la page des produits.
 */
class DefaultController extends AbstractController
{
    #[Route('/', name: 'home_redirect')]
    public function redirectToProducts(): RedirectResponse
    {
        return $this->redirectToRoute('app_products');
    }
}

