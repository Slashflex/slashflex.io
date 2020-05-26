<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Field;
use App\Entity\Image;
use App\Entity\Project;
use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
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

        setlocale(LC_TIME, 'fr_FR.utf8', 'fra');
        $currentDate = 'Le '  . strftime("%A %d %B %Y") . ' à ' . strftime("%H:%M");

        // Populate the database with fake project, fake images and fake paragraphs
        for ($i = 1; $i <= 6; $i++) {
            $randomImage = 'https://i.picsum.photos/id/' . mt_rand(1, 689) . '/800/400.jpg';
            $project = new Project();

            $title = $faker->word(3);

            $project
                ->setTitle($title)
                ->setIntroduction($faker->sentence(9))
                ->setContent1($faker->sentence(18))
                ->setContent2($faker->sentence(22))
                ->setContent3($faker->sentence(44))
                ->setMainImage($randomImage)
                ->setCreatedAt($currentDate)
                ->initializeSlug($title);

            for ($j = 1; $j <= mt_rand(1, 10); $j++) {
                $randomImage = 'https://i.picsum.photos/id/' . mt_rand(1, 689) . '/800/400.jpg';

                $image = new Image();
                $image
                    ->setUrl($randomImage)
                    ->addProject($project);

                $manager->persist($image);
            }

            for ($k = 1; $k <= mt_rand(1, 15); $k++) {
                $paragraphs = $faker->paragraphs(mt_rand(1, 10), true);

                $field = new Field();
                $field
                    ->setContent($paragraphs)
                    ->addProject($project);

                $manager->persist($field);
            }

            $manager->persist($project);
        }

        // Create an admin role
        $roleAdmin = new Role();
        $roleAdmin->setName('ROLE_ADMIN');

        // Create a user role
        $roleUser = new Role();
        $roleUser->setName('ROLE_USER');

        $manager->persist($roleAdmin);
        $manager->persist($roleUser);

        for ($l = 1; $l < mt_rand(1, 12); $l++) {
            $user = new User();

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

        // Setup an admin
        $admin = new User();

        $password = $_ENV['DB_PASSWORD'];
        $email = $_ENV['DB_EMAIL'];
        $firstname = $_ENV['DB_FIRSTNAME'];
        $lastname = $_ENV['DB_LASTNAME'];

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

        $manager->flush();
    }
}
