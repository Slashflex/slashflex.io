<?php

namespace App\DataFixtures;

use App\Entity\Field;
use App\Entity\Project;
use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr-Fr');

        setlocale(LC_TIME, 'fr_FR.utf8', 'fra');
        $currentDate = 'Le '  . strftime("%A %d %B %Y") . ' à ' . strftime("%H:%M");

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
        $manager->flush();
    }
}
