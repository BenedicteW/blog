<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/", name="blog_index")
     * @return Response A response instance
     */
    public function index() :Response
    {
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAll();

        if (!$articles) {
            throw $this->createNotFoundException(
                'No article found in article\'s table.'
            );
        }

        return $this->render(
            'blog/index.html.twig',
            ['articles' => $articles]
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
     * Getting a article with a formatted slug for title
     *
     * @param string $slug The slugger
     *
     * @Route("/{slug<^[a-z0-9-]+$>}",
     *     defaults={"slug" = null},
     *     name="blog_show")
     *  @return Response A response instance
     */
    public function show($slug) : Response
    {
        if (!slug) {
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
                'No article with '.$slug.' title, found in article\'s table.'
            );
        }

        return $this->render('blog/show.html.twig', ['article' => $article]);
    }

    /**
     * @Route("/category/{category}", name="blog_show_category)
     */
    public function showByCategory(string $category)
    {
        $categoryCollection = $this->getDoctrine()->getRepository(Category::class)->findOneByName();

        if (!$categoryCollection) {
            throw $this->createNotFoundException('No articles in '.$category.' category.');
        }

        return $this->render('blog/category.html.twig', ['categoryCollection' => $categoryCollection]);
    }
}
