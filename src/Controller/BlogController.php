<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
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
        return $this->render('blog/index.html.twig', ['categories'=> $categories, 'resultats'=>$resultats]);
    }

    /**
     * @Route("/blog/{slug}", requirements={"slug"="[a-z0-9-]+"}, name="blog_detail", defaults={"slug"="Article Sans Titre"})
     */
    public function showArticle($slug)
    {

        $slug = str_replace('-', ' ', $slug);
        $slug = ucwords($slug);

        return $this->render('blog/article.html.twig', ['slug' => $slug]);
    }
}
