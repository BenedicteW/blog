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
     * @param string $slug The slugger
     *
     * @Route("/article/{slug<^[a-z0-9-]+$>}",
     *     defaults={"slug" = null},
     *     name="article_show")
     *  @return Response A response instance
     */
    public function show($slug) : Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException('No slug has been sent to find an article in article\'s table.');
        }

        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );

        $article = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);

        if (!$article) {
            throw $this->createNotFoundException(
                'No article with title, found in article\'s table.'
            );
        }

        return $this->render('blog/show.html.twig', ['article' => $article]);
    }

    /**
     * @Route("/article/add/", name="article_add")
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
            return $this->redirectToRoute('article_show', ['slug'=>$article->getTitle()]);
        }

        return $this->render('article/add.html.twig', ['add'=>$addArticle->createView()]);
    }
}