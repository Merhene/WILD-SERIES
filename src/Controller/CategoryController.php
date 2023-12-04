<?php 

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProgramRepository;

#[Route('/category', name: 'category_')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('Category/index.html.twig', [
            'categories' => $categories]
        );
    }

    #[Route('/{categoryName}', methods: ['GET'], name: 'show')]
    public function show(string $categoryName, CategoryRepository $categoryRepository, programRepository $programRepository): Response
    {
        $category = $categoryRepository->findOneBy(['name' => $categoryName]);

        $programs = $programRepository->findby(
            ['category' => $category],
            ['id' => 'DESC'],
            limit:3 
        );

        // var_dump($category);
        // die();

        if (!$category) {
            throw $this->createNotFoundException(
                'No category with name : '.$categoryName . ' found in categories table' 
            );
        }
        return $this->render('category/show.html.twig', ['category' => $category, 'programs' => $programs]);
    }

}