<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{

    /**
     * @Route("/products/" , name="admin_list_product")
     */

    public function adminisProduct(ProductRepository $productRepository)
    {
        $product = $productRepository->findAll();
        return $this->render('admin/products.html.twig' , ['products' => $product]);
   
    }
/**                     //Wildcard
      * @Route("/product/{id}", name="admin_product_show")
      */
      public function showProduc($id, ProductRepository $productRepository)
      {
          // find permet de trouver le produit dans la base de données qui a l'id correspondant
          $product = $productRepository->find($id);
          return $this->render('admin/product.html.twig', ['product' => $product]);
      }

      // fonction qui créer un nouveau produit

/**
     * @Route("admin/create/product/", name="admin_create_product")
     */
    public function productCreate(Request $request, EntityManagerInterface $entityManagerInterface ){

        $product = new Product();

        $productForm = $this->createForm(ProductType::class, $product);
        $productForm->handleRequest($request);

        if ($productForm->isSubmitted() && $productForm->isValid()) 
        {
            // persist préenregistre les données
            $entityManagerInterface->persist($product);
            // flush enregistre dans la base de données.
            $entityManagerInterface->flush();

            return $this->redirectToRoute("admin_list_product");
        };

        return $this->render('admin/product.create.html.twig', ['productForm' => $productForm->createView() ]);
    }
       /**
 * @Route("admin/update/product/{id}", name="admin_update_product")
 */
    public function productUpdate($id, Request $request, productRepository $productRepository, EntityManagerInterface $entityManagerInterface)
    {

        $product = $productRepository->find($id);
        $productForm = $this->createForm(ProductType::class, $product);
        $productForm->handleRequest($request);

        if ($productForm->isSubmitted() && $productForm->isValid()) {
            // persist préenregistre les données
            $entityManagerInterface->persist($product);
            // flush enregistre dans la base de données.
            $entityManagerInterface->flush();

            return $this->redirectToRoute("admin_list_product");
        };

        return $this->render('admin/product.create.html.twig', ['productForm' => $productForm->createView() ]);
    }

    /**
     * @Route ("/admin/delete/product/{id}", name= "admin_delete_product")
     */
    public function adminDeleteProduct($id, ProductRepository $productRepository, EntityManagerInterface $entityManagerInterface)
    {
        $product = $productRepository->find($id);

        $entityManagerInterface->remove($product);
        $entityManagerInterface->flush();
        $this->addFlash('notice', 'Votre produit a été supprimé');

        return $this->redirectToRoute('admin_list_product');
    }

}