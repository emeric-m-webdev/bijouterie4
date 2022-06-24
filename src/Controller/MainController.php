<?php

namespace App\Controller; // App = src


// toutes les class doivent être IMPORTÉES !!
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController // la class MainController hérite de la class AbstractController
{


    /*
        en local ==> locahost:8000 
        en ligne ==> www.nomdedomaine.fr


    */

    /**
     * cette annotation s'écrit avec des DOUBLES QUOTES 
     * 
     * 2 arguments 
     * 1- la route (concaténée après locahost:8000-nomdedomaine.fr)
     * 2- le nom de la route (liens, redirection etc....)
     * 
     * 
     * 
     * La route apparait dans l'URL
     * Le nom de la route est utilisé pour les redirections (dans les balises <a>)
     * Lorsqu'on clique sur un lien, la route (du nom de la route) va apparaître dans l'url
     * donc cette route peut être changée à tout moment
     * 
     * @Route("/main", name="app_main")
     */

    //#[Route('/main', name: 'app_main')] PHP8
    public function index(): Response
    {
        /*

            La méthode (fonction) render() provenant de AbstractController permet de rattacher une view à sa fonction
            2 arguments
            1er (OBLIGATOIRE) : nom du fichier de la vue (avec son emplacement)
            2e (FACULTATIF) : c'est le tableau des données provenant du controller à véhiculer sur la vue

        */
  
        $toi = "toi";
        return $this->render('main/index.html.twig', [
            'controller_name' => $toi,
            
        ]);
    }





    /**
     * route pour la page principale (accueil)
     * 
     * @Route("/", name="home")
     * 
     */
    public function home()
    {
     
        $ageController = 15;

        dump($ageController); // on peut checker tout : variable / array / objet

        //dd("tesst");
        // dump("tesst");die;

        return $this->render("main/home.html.twig", [
            // key => value
            // key : nom de la variable/array/objet en TWIG 
            // value : nom de la variable/array/objet/str du CONTROLLER
            "ageTwig" => $ageController,
           

        ]);
    }















































}// fermeture de la class (rien en dessous)
