<?php

namespace App\Controller\Account;


use App\Form\PasswordUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class ProfileController extends AbstractController
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
   

    #[Route('/compte/modifier-mot-passe', name: 'app_account_modifier_passe')]
    public function index(
                                    Request $request, 
                                    ): Response
    {
        $user = $this->getUser();
        $form =$this->createForm(PasswordUserType::class,$user,[
            'passwordHasher' => $this->passwordHasher
        ]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->addFlash(
                'success',
                'Votre mot de passe est mis a jour!'
            );
            $this->entityManager->flush();
        }

        return $this->render('account/profile/modifier-password.html.twig',[
            'modifierPassword' => $form->createView()
        ]);
    }

   
}
