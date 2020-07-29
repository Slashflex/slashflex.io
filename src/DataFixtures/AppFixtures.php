<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Project;
use App\Entity\Reply;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr-Fr');

        // Setup an admin
        $admin = new User();

        // Create an admin role
        $roleAdmin = new Role();
        $roleAdmin->setName('ROLE_ADMIN');

        $password = $_ENV['DB_PASSWORD'];
        $email = $_ENV['DB_EMAIL'];
        $firstname = $_ENV['DB_FIRSTNAME'];
        $lastname = $_ENV['DB_LASTNAME'];

        $admin->setAvatar('avatar.png');

        $admin
            ->setFirstname($firstname)
            ->setLastname($lastname)
            ->setEmail($email)
            ->setPassword($this->passwordEncoder->encodePassword($admin, $password))
            ->setDescription('Creator of https://slashflex.io')
            ->setLogin('Slashflex')
            ->addRoleUser($roleAdmin)
            ->initializeSlug();

        $manager->persist($admin);

        // Populate the database with fake project, fake images and fake paragraphs
        for ($i = 1; $i <= 6; $i++) {
            $project = new Project();

            $title = $faker->word(3);

            $src = __DIR__ . "/../../public/uploads/images/error_404.gif";

            $file = new UploadedFile(
                $src,
                'error_404.gif',
                'image/gif',
                false,
                true //  Set test mode true !!! " Local files are used in test mode hence the code should not enforce HTTP uploads."
            );
            $project->setImageName($file);
            $file2 = new File(__DIR__ . "/../../public/uploads/images/error_404.gif");
            $project->setImageFile($file2);
            $project
                ->setTitle($title)
                ->setIntroduction($faker->sentence(6))
                ->setContent($faker->sentence(18))
                ->setUsers($admin)
                ->initializeSlug($title);

            $manager->persist($project);
        }

        for ($j = 1; $j <= 4; $j++) {
            $article = new Article();

            $title = $faker->word(3);

            $article->setImageName($file);
            $article->setImageFile($file2);
            $article
                ->setTitle($title)
                ->setIntroduction($faker->sentence(9))
                ->setContent($faker->sentence(18))
                ->setUsers($admin)
                ->initializeSlug($title);

            $manager->persist($article);
        }

        // Create a user role
        $roleUser = new Role();
        $roleUser->setName('ROLE_USER');

        $manager->persist($roleAdmin);
        $manager->persist($roleUser);

        for ($k = 1; $k < mt_rand(1, 12); $k++) {
            $user = new User();

            $user->setAvatar('avatar.png');

            $user
                ->setFirstname($faker->firstName())
                ->setLastname($faker->lastname())
                ->setDescription($faker->sentence())
                ->setEmail($faker->email())
                ->setPassword($this->passwordEncoder->encodePassword($user, 'mdp'))
                ->setLogin($faker->userName())
                ->addRoleUser($roleUser)
                ->initializeSlug();

            for ($l = 1; $l < 8; $l++) {
                $comment = new Comment();

                $reply = new Reply();

                $reply
                    ->setMessage($faker->sentence(10))
                    ->setUsers($user);
                $manager->persist($reply);

                $comment
                    ->addReply($reply)
                    ->setUsers($user)
                    ->setContent($faker->sentence(8))
                    ->setArticle($article);

                $user
                    ->addComment($comment)
                    ->addReply($reply);

                $manager->persist($comment);
            }

            $manager->persist($user);
        }

        $manager->flush();
    }
}
