<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Form\ArticleSearchType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog/articles", name="blog_search")
     * @return Response A response instance
     */
    public function index(Request $request) :Response
    {
        $form = $this->createForm(
            ArticleSearchType::class,
            null,
            ['method' => Request::METHOD_GET]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $data = $form->getData();
            var_dump($data);
        }

        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAll();

        if (!$articles) {
            throw $this->createNotFoundException(
                'No article found in article\'s table.'
            );
        }

        return $this->render(
            'blog/search.html.twig',
            ['articles' => $articles, 'form' => $form->createView()]
        );
    }

    /**
     * @Route("/blog", name="blog_list")
     */
    public function showAll()
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        foreach ($categories as $category){
            $articles = $this->getDoctrine()->getRepository(Article::class)->findBy(['category'=> $category->getId()]);
            foreach ($articles as $article){
                $category->addArticle($article);
            }
            $resultats[] = $category->getArticles();
        }
        return $this->render('blog/liste.html.twig', ['categories'=> $categories, 'resultats'=>$resultats]);
    }

    /**
     * @param string $slug The slugger
     *
     * @Route("/blog/{slug<^[a-z0-9-]+$>}",
     *     defaults={"slug" = null},
     *     name="blog_show")
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

}
