<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class LoginController extends AbstractController
{
    #[Route('/connexion', name: 'app_login')] 
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        // Gérer les erreurs
        $error = $authenticationUtils->getLastAuthenticationError();
        //Dernier username entréé par utilisateur si user a entrée password erroné c pas la peine de retaper email
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('login/index.html.twig', [
            'error' => $error ,
            'last_username' => $lastUsername
        ]);
    }


    #[Route('/deconnexion', name: 'app_logout')] 
    public function logout(Security $security): void
    {
        // logout the user on the current firewall
        $response = $security->logout();

        // you can also disable the CSRF protection
        $response = $security->logout(false);

        // ... return $response (if set) or e.g. redirect to the homepage
    }


}
