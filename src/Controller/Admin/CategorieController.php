<?php

namespace App\Controller\Admin;

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
        return $this->render('admin/categorie.html.twig', ['categorie' => $categorie]);
    }

    /**
     * @Route("admin/update/categorie/{id}", name="admin_update_categorie")
     */
    public function categorieUpdate(
        $id,
        Request $request,
        EntityManagerInterface $entityManagerInterface,
        CategorieRepository $categoryRepository
    ) {
        $categorie = $categoryRepository->find($id);

        $categorieForm = $this->createForm(CategorieType::class, $categorie);

        $categorieForm->handleRequest($request);

        if ($categorieForm->isSubmitted() && $categorieForm->isValid()) {
            $entityManagerInterface->persist($categorie);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('categories_list');
        }

        return $this->render('admin/categorie.create.html.twig', ['categorieForm' => $categorieForm->createView()]);
    }

    /**
     * @Route("admin/create/categorie/", name="admin_create_categorie")
     */
    public function categorieCreate(
        Request $request,
        EntityManagerInterface $entityManagerInterface
    ) {
        $categorie = new Categorie();

        $categorieForm = $this->createForm(CategorieType::class, $categorie);

        $categorieForm->handleRequest($request);

        if ($categorieForm->isSubmitted() && $categorieForm->isValid()) {
            $entityManagerInterface->persist($categorie);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('categories_list');
        }

        return $this->render('admin/categorie.create.html.twig', ['categorieForm' => $categorieForm->createView()]);
    }

    /**
     * @Route("admin/delete/categorie/{id}", name="admin_delete_categorie")
     */
    public function deleteCategory($id, CategorieRepository $categorieRepository, EntityManagerInterface $entityManagerInterface)
    {
        $categorie = $categorieRepository->find($id);
        $entityManagerInterface->remove($categorie);
        $entityManagerInterface->flush();

        return $this->redirectToRoute('categories_list');
    }
}