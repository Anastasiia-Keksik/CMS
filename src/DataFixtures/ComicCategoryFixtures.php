<?php

namespace App\DataFixtures;

use App\Entity\ComicCategories;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ComicCategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $categories = ['FANTASY', 'COMEDY', 'ACTION', 'SLICE OF LIFE', 'ROMANCE', 'SUPERHERO', 'SCI-FI', 'THRILLER',
            'SUPERNATURAL', 'MYSTERY', 'SPORTS', 'HISTORICAL', 'HEARTHWARMING', 'HORROR', 'INFORAMTIVE', 'MARTIAL-ARTS'];

        for ($i=0;$i<=count($categories)-1;$i++){
            $category = new ComicCategories();
            $category->setName($categories[$i]);

            $manager->persist($category);
        }

        $manager->flush();
    }
}
