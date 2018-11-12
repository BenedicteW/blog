<?php
/**
 * Created by PhpStorm.
 * User: Benedicte
 * Date: 12/11/2018
 * Time: 10:55
 */

namespace App\Controller;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class CategoryController extends AbstractController
{
    /**
     * @Route("/category/{id}", name="category_detail")
     */
    public function show(Category $category) :Response
    {
        return $this->render('category/category.html.twig', ['category' => $category]);
    }

}