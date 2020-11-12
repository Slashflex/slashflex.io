<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Panther\PantherTestCase;

class SecurityControllerTest extends PantherTestCase
{
    public function testDisplayLogin()
    {
        $client = static::createClient();
        $client->request('GET', '/login');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('div.stack__text--projects', 'Log in to your');
        $this->assertSelectorTextContains('div.stack__text--stack', 'ACCOUNT');
        $this->assertSelectorNotExists('.flash-notice-error');
    }

    public function testLoginWithBadCredentials()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Login')->form([
            '_username' => 'john@doe.com',
            '_password' => '12'
        ]);
        $client->submit($form);
        $this->assertResponseRedirects('/login');
        $client->followRedirect();
        $this->assertSelectorExists('.form__errors');
    }

    public function testSuccessFullLogin()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Login')->form([
            '_username' => $_ENV['DB_EMAIL'],
            '_password' => $_ENV['DB_PASSWORD']
        ]);
        $client->submit($form);
        $this->assertResponseRedirects('/me');
        $client->followRedirect();
        $user = self::$container->get(UserRepository::class)->findOneByEmail($_ENV['DB_EMAIL']);
        $this->assertSelectorTextContains('.stack__text--stack', $user->__toString());
    }

    /**
     * Test for a successfull registration process
     *
     * Run command with Panther :
     * env PANTHER_NO_HEADLESS=1 PANTHER_CHROME_BINARY=/usr/bin/google-chrome
     * ./bin/phpunit --filter testSuccessFullRegistration --debug
     *
     * @throws Exception
     */
    public function testSuccessFullRegistration()
    {
        // ACT
        $userEmail = 'john@doe.com';
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/register');
        $crawler->selectButton('Register')->form([
            'registration_form[email]' => $userEmail,
            'registration_form[firstname]' => 'john',
            'registration_form[lastname]' => 'doe',
            'registration_form[password][first]' => '012345678',
            'registration_form[password][second]' => '012345678',
            'registration_form[login]' => 'johnny',
        ]);
        // Perform click actions
        $client->executeScript("document.getElementById('registration_form_agreeTerms').click()");
        $client->executeScript("document.querySelector('.button__login.mx-auto.d-block').click()");
        // ARRANGE
        // Search for newly created user by it's email
        $user = self::$container->get(UserRepository::class)->findOneByEmail($userEmail);
        // ASSERT
        // that registered user exist in database
        $this->assertEquals($userEmail, $user->getEmail());
        // that newly created user only have the role of user
        foreach ($user->getRoles() as $role) {
            $this->assertEquals('ROLE_USER', $role);
            $this->assertNotEquals('ROLE_ADMIN', $role);
        }
        // that a folder is created with user firstname and lastname as slug
        $this->assertDirectoryExists($_SERVER['DOCUMENT_ROOT'].'public/uploads/avatars/'.$user->getSlug().'/');
    }

    public function testVisitingWhileLoggedIn()
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('john@doe.com');

        $client->loginUser($testUser);

        // user is now logged in, so you can test protected resources
        $client->request('GET', '/me');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.stack__text--stack', $testUser->__toString());
    }
}