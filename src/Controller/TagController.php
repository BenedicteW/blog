<?php
/**
 * Created by PhpStorm.
 * User: Benedicte
 * Date: 19/11/2018
 * Time: 15:36
 */

namespace App\Controller;


use App\Entity\Article;
use App\Entity\Tag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class TagController extends AbstractController
{
    /**
     * @param string $name the slugger
     * @Route("/tag/{name}", name="tag_show_articles")
     * @return Response A response instance
     */
    public function showByTag(string $name) :Response
    {

        $name = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($name)), "-"));

        $tag = $this->getDoctrine()->getRepository(Tag::class)->findOneByName($name);
        $articles = $tag->getArticles();

        return $this->render('tag/articlesByTag.html.twig', ['articles'=>$articles]);

    }
}