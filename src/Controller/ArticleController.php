<?php
/**
 * Created by PhpStorm.
 * User: Benedicte
 * Date: 19/11/2018
 * Time: 12:01
 */

namespace App\Controller;
use App\Entity\Category;
use App\Entity\Article;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ArticleController extends AbstractController
{
    /**
     * @Route("/article/add", name="article_add")
     */
    public function add(Request $request) :Response
    {
        $article = new Article();
        $addArticle = $this->createForm(ArticleType::class, $article);
        $addArticle->handleRequest($request);
        if ($addArticle->isSubmitted() && $addArticle->isValid()){
            $articleEntity = $this->getDoctrine()->getManager();
            $articleEntity->persist($article);
            $articleEntity->flush();
            return $this->redirectToRoute('blog_list');
        }

        return $this->render('article/add.html.twig', ['add'=>$addArticle->createView()]);
    }
}