<?php

namespace App\Controller\Account;

use App\Entity\Address;
use App\Form\AddressUserType;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class AddressController extends AbstractController
{

    private UserPasswordHasherInterface $passwordHasher;
    private EntityManagerInterface $entityManager;
    public function __construct(
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
    ) {
        $this->passwordHasher = $passwordHasher;
        $this->entityManager = $entityManager;
    }
    #[Route('/compte/addresses', name: 'app_account_adresses')]
    public function index(): Response
    {
        return $this->render('account/address/addresses.html.twig');
    }

    #[Route('/compte/addresse/ajouter/{id}', name: 'app_account_adress_form', defaults: ['id' => null])]
    public function form(Request $request, $id, AddressRepository $addressRepository): Response
    {
        if ($id) {
            $address = $addressRepository->findOneById($id);
            if (!$address || $address->getUser() != $this->getUser()) {
                return $this->redirectToRoute('app_account_adresses');
            }
        } else {
            $address = new Address();
            $address->setUser($this->getUser());
        }
        $form = $this->createForm(AddressUserType::class, $address);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($address);
            $this->entityManager->flush();
            $this->addFlash(
                'success',
                'Votre adresse est bien enregistrée!'
            );

            return $this->redirectToRoute('app_account_adresses');
        }

        return $this->render('account/address/addresseForm.html.twig', [
            'addresseForm' => $form
        ]);
    }

    #[Route('/compte/addresse/delete/{id}', name: 'app_account_adress_delete')]
    public function delete($id, AddressRepository $addressRepository): Response
    {
        if ($id) {
            $addresse = $addressRepository->findOneById($id);
            if (!$addresse || $addresse->getUser() != $this->getUser()) {
                return $this->redirectToRoute('app_account_adresses');
            }
            $this->entityManager->remove($addresse);
            $this->entityManager->flush();
            $this->addFlash(
                'success',
                'Votre adresse est bien supprimée!'
            );
        }
        return $this->render('account/address/addresses.html.twig');
    }
}
