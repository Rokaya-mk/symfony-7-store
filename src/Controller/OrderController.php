<?php

namespace App\Controller;

use App\Form\OrderType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class OrderController extends AbstractController
{
    /**
     * step 1 choisir l'address de livraison et le transporteur
     */
    #[Route('/commande/livraison', name: 'app_order')]
    public function index(): Response
    {
        $addresses = $this->getUser()->getAddresses();
        if(count($addresses) === 0){
            $this->addFlash('warning', 'Vous devez ajouter une adresse avant de passer commande');
            return $this->redirectToRoute('app_account_address_add');
        }
        $form = $this->createForm(OrderType::class, null, [
            'addresses' => $addresses
        ]);
        return $this->render('order/index.html.twig', [
            'addressForm' => $form->createView(),
        ]);
    }
}
