<?php
/**
 * Created by PhpStorm.
 * User: Benedicte
 * Date: 05/11/2018
 * Time: 14:34
 */

namespace App\DataFixtures;
use Faker;
use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{


    public function load(ObjectManager $manager){

        for ($i=0; $i < 50; $i++){
            $article = new Article();
            $faker =  Faker\Factory::create('fr_FR');
            $article->setTitle($faker->sentence(4));
            $article->setContent($faker->text(400));
            $article->setCategory($this->getReference('categorie_' . rand(0, 4)));
            $manager->persist($article);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [CategoryFixtures::class];
    }
}