<?php

namespace App\Controller;

use App\Repository\CommentaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profil")
 */
class ProfilController extends AbstractController
{
    /**
     * @Route("/", name="app_profil")
     */
    public function profil(CommentaireRepository $repoCommentaire): Response
    {
        dump($this->getUser());
        $user = $this->getUser();

        $commentaires = $repoCommentaire->findBy([
            // key (nom de la propriété dans l'entity) => value
            'user' => $user
        ]);

        //dd($commentaires);

        //$this->getUser()->getNom();
        // $user->getNom()

        /*
            quand un utilisateur est connecté :

            en twig app.user retourne l'objet issu de la class (Entity) User
            en php (controller) $this->getUser() (provenant de AbstractController) permet de retourner l'objet issu de la class (Entity) User

            Quand un utilisateur n'est pas connecté :

            app.user et $this->getUser() retourne NULL

        */
        return $this->render('profil/index.html.twig', [
           "user" => $user
        ]);
    }




}
