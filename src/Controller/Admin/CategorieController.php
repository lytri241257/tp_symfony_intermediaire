<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    
    /**
     * @Route("/categories/", name="categories_list")
     */
    public function categoriesList(CategorieRepository $categorieRepository)
    {
        $categories = $categorieRepository->findAll();
        return $this->render('admin/categories.html.twig', ['categories' => $categories]);
    }

    /**                       // Wildcard
     * @Route("/categorie/{id}", name="categorie_show")
     */
    public function categorieShow($id, CategorieRepository $categorieRepository)
    {
        $categorie = $categorieRepository->find($id);
        return $this->render('admin/category.html.twig', ['categorie' => $categorie]);
    }

    /**
     * @Route("/update/category/{id}", name="category_update")
     */
    public function categorieUpdate(
        $id,
        Request $request,
        EntityManagerInterface $entityManagerInterface,
        CategorieRepository $categoryRepository
    ) {
        $categorie = $categoryRepository->find($id);

        $categorieForm = $this->createForm(CategorrieType::class, $categorie);

        $categorieForm->handleRequest($request);

        if ($categorieForm->isSubmitted() && $categorieForm->isValid()) {
            $entityManagerInterface->persist($categorie);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('categories_list');
        }

        return $this->render('admin/update_categorie.html.twig', ['categoryForm' => $categorieForm->createView()]);
    }

    /**
     * @Route("/create/category", name="category_create")
     */
    public function categorieCreate(
        Request $request,
        EntityManagerInterface $entityManagerInterface
    ) {
        $categorie = new Categorie();

        $categorieForm = $this->createForm(CategoryType::class, $categorie);

        $categorieForm->handleRequest($request);

        if ($categorieForm->isSubmitted() && $categorieForm->isValid()) {
            $entityManagerInterface->persist($categorie);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('categories_list');
        }

        return $this->render('admin/update_category.html.twig', ['categoryForm' => $categorieForm->createView()]);
    }

    /**
     * @Route("delete/category/{id}", name="delete_category")
     */
    public function deleteCategory($id, CategorieRepository $categorieRepository, EntityManagerInterface $entityManagerInterface)
    {
        $categorie = $categorieRepository->find($id);
        $entityManagerInterface->remove($categorie);
        $entityManagerInterface->flush();

        return $this->redirectToRoute('categories_list');
    }
}