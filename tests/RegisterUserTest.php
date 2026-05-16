<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterUserTest extends WebTestCase
{
    public function testSomething(): void
    {
        /**
         * Test la creation  d'un compte utilisateur 
         * 
         */
        // faire request
        $client = static::createClient();
        $crawler = $client->request('GET', '/inscription');

        // remplire le formulaire
        /**
         * register_user[email]
         * register_user[plainPassword][first]
         * register_user[plainPassword][second]
         * register_user[firstname]
         * register_user[lastname]
         */
        $client->submitForm('register_user_submit',
        [
            'register_user[email]' => 'fake_user@email.com',
            'register_user[plainPassword][first]' =>  'fake_user@email.com',
            'register_user[plainPassword][second]' => 'fake_user@email.com',
            'register_user[firstname]' => 'fake1',
            'register_user[lastname]' => 'fakename'
        ]);
        // verifier la redirection a login page
        $this->assertResponseRedirects('/connexion');
        $client->followRedirect();

        // vérifier le fonction retourne message success
        $this->assertSelectorExists('div:contains("Votre Compte à été creer avec succès!")');

        // $this->assertResponseIsSuccessful();
        // $this->assertSelectorTextContains('h1', 'Hello World');
    }
}
