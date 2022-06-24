<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Commentaire;
use App\Filter\ProduitFilter;
use App\Form\CommentaireType;
use App\Form\ProduitFilterType;
use App\Repository\CommentaireRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProduitController extends AbstractController
{



    /**
     * @Route("/catalogue", name="catalogue")
     */
    public function catalogue(ProduitRepository $repoProduit, Request $request)
    {

       //$produits = $repoProduit->findAll();
       //$produits = $repoProduit->findTout();
       //$produits = $repoProduit->findId(9);
        // $produits = $repoProduit->findOrderPrix();
        //$produits = $repoProduit->findOrder("ASC");
        //$produits = $repoProduit->findLimit();
        //$produits = $repoProduit->findCategorie(4);
        // $produits = $repoProduit->findCategories([1,5]);
        // $produits = $repoProduit->findMarques([2]);
        //$produits = $repoProduit->findMatieres([1,2]);
        // $produits = $repoProduit->findSomething("en");
        //$produits = $repoProduit->findPrix(900,1000);
       //dd($produits);


       $filter = new ProduitFilter;
        $form = $this->createForm(ProduitFilterType::class, $filter);
        $form->handleRequest($request);

        //dump($filter);


      $produits = $repoProduit->findFiltre($filter);

       $count = count($repoProduit->findFiltre($filter));


        return $this->render("produit/catalogue.html.twig", [
            "produits" => $produits,
            "form" => $form->createView(),
            "count" => $count
        ]);
    }


    /**
     * @Route("/fiche_produit/{id}", name="fiche_produit")
     */
    public function fiche_produit(Produit $produit, Request $request, EntityManagerInterface $manager, CommentaireRepository $repoCommentaire)
    {

        $commentaires = $repoCommentaire->findBy([
            // key (nom de la propriété dans l'entity) => value
            'produit' => $produit
        ]);
        /*

            findAll() ==> SELECT * FROM commentaire
            find($id) ==> SELECT * FROM commentaire WHERE id = $id
            findBy() ==> l'argument est un tableau

            SELECT * FROM commentaire WHERE produit = ..

        */


       
        //dd($commentaires);
        $commentaire = new Commentaire;
        
        $form = $this->createForm(CommentaireType::class, $commentaire);


        $form->handleRequest($request);

        if($form->isSubmitted() AND $form->isValid())
        {
            $user = $this->getUser();

            $commentaire->setProduit($produit);
            $commentaire->setUser($user);
            $commentaire->setDateAt(new \DateTimeImmutable('now'));

            // $manager->persist($commentaire);
            // $manager->flush();

            $repoCommentaire->add($commentaire, true);

            $this->addFlash("success", "Merci pour votre avis");
            return $this->redirectToRoute("fiche_produit", ["id" => $produit->getId()]);
        }


        return $this->render("produit/fiche_produit.html.twig", [
            "produit" => $produit, 
            "form" => $form->createView(),
            "commentaires" => $commentaires
        ]);
    }







    
}
