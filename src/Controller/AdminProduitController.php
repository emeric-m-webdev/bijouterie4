<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/produit")
 */
class AdminProduitController extends AbstractController
{

    /*
        CRUD DE PRODUIT

                ROUTE                       NOM DE ROUTE / functions                  view

        /admin/produit/afficher             produit_afficher                     produit_afficher.html.twig
        /admin/produit/ajouter              produit_ajouter                      produit_ajouter.html.twig
        /admin/produit/voir/{id}            produit_voir                         produit_voir.html.twig
        /admin/produit/modifier             produit_modifier
        /admin/produit/supprimer            produit_supprimer

    */



    /**
     * 
     * @Route("/afficher", name="produit_afficher")
     * 
     * PHP 8 ====> #[Route('/afficher', name:'produit_afficher')]
     */

    public function produit_afficher(ProduitRepository $repoProduit) : Response
    {
        /*
            Dans les parenthèses de la fonction, on instancie des objets issus de class
            syntaxe  :      class $objet

            c'est ce qu'on appelle une DEPENDANCE
        */


        $produits = $repoProduit->findAll(); // SELECT * FROM produit

        dump($produits);


        return $this->render("admin_produit/produit_afficher.html.twig", [
            "produits" => $produits
        ]);
    }




    /**
     * 
     * @Route("/ajouter", name="produit_ajouter")
     */
    public function produit_ajouter(Request $request, EntityManagerInterface $manager, ProduitRepository $repoProduit)
    {


        $produit = new Produit;
        //$produit->setTitre("bague en or");
        dump($produit); // on observe que l'objet contient les propriétés de l'entity et qu'elles sont toutes null


        $form = $this->createForm(ProduitType::class, $produit, ['ajouter' => true]);
        /*
            pour créer un formulaire
            on utilise la méthode createForm() provenant de AbstractController 

            arguments :
                1e (obligatoire) : le nom de la class contenant le builder
                2e (obligatoire) : l'objet issu de la Class(Entity) dont la class ..Type est issue

                3e (facultatif) : tableau des options


            ==> résultat : $form est un objet

        */

        /*
            Traitement du formulaire
            $request est un objet de la class Request
            On y trouve les superglobales
                $request->request ($_POST)
                $request->query ($_GET)

        */
        $form->handleRequest($request);

        /*
            Si le formulaire est sousmis (clic sur le bouton submit)
            et si le formulaire est valide (contraintes, les conditions)
        */
        if($form->isSubmitted() AND $form->isValid())
        {
            dump($produit);

            // on met un anti slash devant les class qui n'appartiennent pas à Symfony
            $produit->setDateAt(new \DateTimeImmutable('now'));

            dump($produit);

            /*
                la class EntityManagerInterface permet les requêtes : INSERT INTO UPDATE DELETE

                la méthode persist() permet d'ajouter ou modifier
                la différence est l'id
                id null => ajouter
                id int => modifier

                la méthode remove() permet de supprimer un objet en bdd


                la méthode flush() permet l'éxecution 

            */
            



            // la méthode get() de l'objet $form a pour argument le nom d'un élement du formulaire (dans le builder)
            $imageFile = $form->get('image')->getData();

            //dd($imageFile);
            /*
                Dans notre projet, l'image n'est pas obligatoire
                Observation : 
                    $imageFile peut être NULL (ça veut dire aucun upload)
                    $imageFile est un objet issu de la class UploadedFile (ça veut dire upload)

            */

            // s'il y a une image upload, le traitement se situe dans la condition
            if($imageFile)
            {
                // 1e étape : Définir le nom de l'image 
                $nomImage = date("YmdHis") . "-" . uniqid() . "." . $imageFile->getClientOriginalExtension();
                // 20220608145004-62a09afc38ee9.jpg
                // 20220608145105-62a09b39c02a2.jpg
                //dd($nomImage);

                // 2e étape : Déplacer le fichier image dans le dossier public
                $imageFile->move(
                    $this->getParameter("imageProduit"),
                    $nomImage
                );
                /*
                    La méthode move() permet de déplacer un fichier dans le projet
                    2 argument :
                    emplacement
                    le nom attribué au fichier

                    L'emplacement :
                    La méthode getParameter() permet d'aller rechercher dans le fichier config/services.yaml le nom du paramètre placé comme argument de la fonction
                    (fichier services.yaml) ====>
                        parameters:
                            imageProduit: '%kernel.project_dir%/public/images/produit'

                            %kernel.project_dir% ==> le nom du dossier (ici c'est bijouterie)
                */

                // 3e étape : Enregistrer dans l'objet $produit à la propriété image le nom du fichier
                $produit->setImage($nomImage);
            }








            $manager->persist($produit);
            $manager->flush();

            //dd($produit);

            // $repoProduit->add($produit);


            // notification
            /*
                La méthode addFlash() provenant de la  class AbstractController permet d'afficher un message sur le navigateur (twig) crée depuis le controller

                2 arguments :
                    1- le nom du flash
                    2- le message
            */
            $this->addFlash("success", "Le produit N°" .  $produit->getId()  .  " a bien été ajouté");



           // Redirection
           /*
                La méthode redirectToRoute() provenant de la  class AbstractController permet de rediriger sur une autre route
                1 argument obligatoire : le nom de la route
                2e (facultatif) : tableau des paramètres

                redirectToRoute et la fonction twig path() permettent de rediriger (une en twig et une en php)
           */
            return $this->redirectToRoute('produit_afficher');
        }




        return $this->render("admin_produit/produit_ajouter.html.twig", [
            "formProduit" => $form->createView()
        ]);
    }




    /**
     * @Route("/voir/{id}", name="produit_voir")
     */
    public function produit_voir(Produit $produit)
    {                           // $id, ProduitRepository $repoProduit
        // SELECT * FROM produit ==> findAll()
        // SELECT * FROM produit WHERE id = $id ==> find($id)


        //$produit = $repoProduit->find($id);

        //dd($produit);


        /*

            Le paramètre id (dans l'url) est injecté dans l'objet, à la propriété portant la même dénomination

        */


        return $this->render("admin_produit/produit_voir.html.twig", [
            "produit" => $produit
        ]);
    }



    /**
     * @Route("/modifier/{id}", name="produit_modifier")
     */
    public function produit_modifier(Produit $produit, Request $request, EntityManagerInterface $manager)
    {
        
        
        $form = $this->createForm(ProduitType::class, $produit, ['modifier' => true]);


        $form->handleRequest($request);

        if($form->isSubmitted() AND $form->isValid())
        {
            
            $imageFile = $form->get('imageUpdate')->getData();

            //dd($imageFile);

            if($imageFile)
            {
                $nomImage = date("YmdHis") . "-" . uniqid() . "." . $imageFile->getClientOriginalExtension();

                $imageFile->move(
                    $this->getParameter("imageProduit"),
                    $nomImage
                );

                /*
                 Lorsqu'on upload une image sur un produit
                 2 cas possibles :
                 soit le produit n'a pas d'image avant
                 soit le produit a une image
                 */
                if($produit->getImage())
                {
                    // unlink() permet de supprimer un fichier d'un dossier
                    unlink($this->getParameter("imageProduit") . "/" . $produit->getImage() );
                    //dossier/dossier/dossier/fichier.ext
                }

                $produit->setImage($nomImage);
            }



            $manager->persist($produit);
            $manager->flush();

            $this->addFlash("success", "Le produit N" . $produit->getId() . " a bien été modifié");
            return $this->redirectToRoute("produit_afficher");
        }

        return $this->render("admin_produit/produit_modifier.html.twig", [
            "produit" => $produit,
            "formProduit" => $form->createView()
        ]);
    }



    /**
     * @Route("/supprimer/{id}", name="produit_supprimer")
     */
    public function produit_supprimer(Produit $produit, EntityManagerInterface $manager)
    {
        //dump($produit);
        $idProduit = $produit->getId();

        if($produit->getImage())
        {
            // unlink() permet de supprimer un fichier d'un dossier
            unlink($this->getParameter("imageProduit") . "/" . $produit->getImage() );
            //dossier/dossier/dossier/fichier.ext
        }


        $manager->remove($produit);
        $manager->flush();
        //dd($produit);

        $this->addFlash("success", "Le produit N°$idProduit a bien été supprimé");

        return $this->redirectToRoute("produit_afficher");
    }



    /**
     * @Route("/image/supprimer/{id}", name="produit_image_supprimer")
     */
    public function produit_image_supprimer(Produit $produit, EntityManagerInterface $manager)
    {
        unlink($this->getParameter("imageProduit") . "/" . $produit->getImage() );
        $produit->setImage(NULL);
        $manager->persist($produit);
        $manager->flush();

        $this->addFlash("success", "L'image du produit N°" . $produit->getId() . " a bien été supprimée");

        return $this->redirectToRoute("produit_modifier", ["id" => $produit->getId()]);
    }

    


}
