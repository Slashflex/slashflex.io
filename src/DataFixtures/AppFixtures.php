<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Article;
use App\Entity\Project;
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

        setlocale(LC_TIME, 'en_US.utf8');
        $currentDate = strftime("%A %d %B %Y") . ' at ' . strftime("%H:%M");

        $src = __DIR__ . "/../../public/uploads/images/error-404.gif";

        $file = new UploadedFile(
            $src,
            'error-404.gif',
            'image/gif',
            false,
            true //  Set test mode true !!! " Local files are used in test mode hence the code should not enforce HTTP uploads."
        );
        $file2 = new File(__DIR__ . "/../../public/uploads/images/error-404.gif");

        // Setup an admin
        $admin = new User();

        // Create an admin role
        $roleAdmin = new Role();
        $roleAdmin->setName('ROLE_ADMIN');

        $password = $_ENV['DB_PASSWORD'];
        $email = $_ENV['DB_EMAIL'];
        $firstname = $_ENV['DB_FIRSTNAME'];
        $lastname = $_ENV['DB_LASTNAME'];

        // $admin->setImageName($file);
        // $admin->setImageFile($file2);

        $admin
            ->setFirstname($firstname)
            ->setLastname($lastname)
            ->setEmail($email)
            ->setPassword($this->passwordEncoder->encodePassword($admin, $password))
            ->setDescription('Creator of https://slashflex.io')
            ->setLogin('Slashflex')
            ->addRoleUser($roleAdmin)
            ->setCreatedAt($currentDate)
            ->initializeSlug();

        $manager->persist($admin);

        // Populate the database with fake project, fake images and fake paragraphs
        for ($i = 1; $i <= 6; $i++) {
            $project = new Project();

            $title = $faker->word(3);

            $src = __DIR__ . "/../../public/uploads/images/error-404.gif";

            $file = new UploadedFile(
                $src,
                'error-404.gif',
                'image/gif',
                false,
                true //  Set test mode true !!! " Local files are used in test mode hence the code should not enforce HTTP uploads."
            );
            $project->setImageName($file);
            $file2 = new File(__DIR__ . "/../../public/uploads/images/error-404.gif");
            $project->setImageFile($file2);
            $project
                ->setTitle($title)
                ->setIntroduction($faker->sentence(6))
                ->setContent($faker->sentence(18))
                ->setUsers($admin)
                ->setCreatedAt($currentDate)
                ->initializeSlug($title);

            $manager->persist($project);
        }

        for ($m = 1; $m <= 6; $m++) {
            $article = new Article();

            $title = $faker->word(3);

            $article->setImageName($file);
            $article->setImageFile($file2);
            $article
                ->setTitle($title)
                ->setIntroduction($faker->sentence(9))
                ->setContent($faker->sentence(18))
                ->setUsers($admin)
                ->setCreatedAt($currentDate)
                ->initializeSlug($title);

            $manager->persist($article);
        }

        // Create a user role
        $roleUser = new Role();
        $roleUser->setName('ROLE_USER');

        $manager->persist($roleAdmin);
        $manager->persist($roleUser);

        for ($l = 1; $l < mt_rand(1, 12); $l++) {
            $user = new User();

            // $user->setImageName($file);
            // $user->setImageFile($file2);
            $user
                ->setFirstname($faker->firstName())
                ->setLastname($faker->lastname())
                ->setDescription($faker->sentence())
                ->setEmail($faker->email())
                ->setPassword($this->passwordEncoder->encodePassword($user, 'mdp'))
                ->setLogin($faker->userName())
                ->setCreatedAt($currentDate)
                ->addRoleUser($roleUser)
                ->initializeSlug();

            $manager->persist($user);
        }

        $manager->flush();
    }
}
