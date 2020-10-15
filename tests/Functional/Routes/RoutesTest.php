<?php

namespace App\Tests\Functional\Routes;

use App\Entity\Article;
use App\Entity\Project;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RoutesTest extends WebTestCase
{
    /**
     * @dataProvider getPublicUrls
     *
     * @param string $url
     * @return void
     */
    public function testGetUrlForAnonymousUsers(string $url): void
    {
        $client = static::createClient();

        $client->request('GET', $url);
        $this->assertResponseIsSuccessful(sprintf('The %s public URL loads correctly.', $url));
    }

    public function testPublicBlogPost(): void
    {
        $client = static::createClient();

        $blogPost = $client->getContainer()->get('doctrine')->getRepository(Article::class)->find(1);
        $client->request('GET', sprintf('/blog/post/%s', $blogPost->getSlug()));

        $this->assertResponseIsSuccessful();
    }

    public function testPublicWork(): void
    {
        $client = static::createClient();

        $project = $client->getContainer()->get('doctrine')->getRepository(Project::class)->find(1);
        $client->request('GET', sprintf('/works/%s', $project->getSlug()));

        $this->assertResponseIsSuccessful();
    }

    /**
     * @dataProvider notFoundUrls
     *
     * @param string $url
     * @return void
     */
    public function testPageNotFound(string $url): void
    {
        $client = static::createClient();

        $client->request('GET', $url);
        $this->assertTrue($client->getResponse()->isNotFound());
    }

    public function getPublicUrls(): ?\Generator
    {
        yield ['/'];
        yield ['/blog'];
        yield ['/works'];
        yield ['/about-me'];
        yield ['/contact'];
        yield ['/register'];
        yield ['/login'];
        yield ['/reset-password'];
    }

    public function notFoundUrls(): ?\Generator
    {
        yield ['/sdfdsf'];
        yield ['/blog/1'];
        yield ['/works/2'];
        yield ['/blog/post/1'];
        yield ['/works/test'];
    }

    public function testAnonymousUserSubmitLoginForm(): void
    {
        $userEmail = 'pro.davidsaoud@gmail.com';

        $client = static::createClient();
        $crawler = $client->request('GET', 'https://slashflex.io/login');

        $form = $crawler->selectButton('Login')->form();
        $form['_username'] = $userEmail;
        $form['_password'] = 'pass1234';

        $crawler = $client->submit($form);
        $user = self::$container->get(UserRepository::class)->findOneByEmail($userEmail);

        $this->assertSame($userEmail, $user->getEmail());
    }

    public function testAdminUserSubmitLoginForm(): void
    {
        $adminEmail = 'pro.davidsaoud@gmail.com';

        $client = static::createClient();
        $crawler = $client->request('GET', 'https://slashflex.io/admin/sign-in');

        $form = $crawler->selectButton('Login')->form();
        $form['_username'] = $adminEmail;
        $form['_password'] = 'pass1234';

        $crawler = $client->submit($form);

        $user = self::$container->get(UserRepository::class)->findOneByEmail($adminEmail);

        if ($user->getRoles()[0] == 'ROLE_ADMIN') {
            $this->assertEquals(302, $client->getResponse()->getStatusCode());
        }
    }

    public function testDenyAdminAccessForNonAdminRole(): void
    {
        $userEmail = 'supraderp@gmail.com';

        $client = static::createClient();
        $crawler = $client->request('GET', 'https://slashflex.io/admin/sign-in');

        $form = $crawler->selectButton('Login')->form();
        $form['_username'] = $userEmail;
        $form['_password'] = 'pass1234';

        $crawler = $client->submit($form);

        $user = self::$container->get(UserRepository::class)->findOneByEmail($userEmail);

        if ($user->getRoles()[0] != 'ROLE_ADMIN') {
            $this->assertEquals(302, $client->getResponse()->getStatusCode());
        }
    }
}