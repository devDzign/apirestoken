<?php
/**
 * Created by PhpStorm.
 * User: mc
 * Date: 03/11/2016
 * Time: 10:53
 */

namespace TvBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use France\TvBundle\Entity\Article;

class LoadArticleData implements FixtureInterface
{
    
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        
        for ($i = 0; $i < 10; $i++) {

            $article = "article" . $i;
            $article = new Article();
 
            $article
                ->setLeading($article)
                ->setTitle("titleTest")
                ->setBody("body test cool")
                ->setCreatedBy("user".$i);
            $manager->persist($article);
        }
        $manager->flush();
    }
}