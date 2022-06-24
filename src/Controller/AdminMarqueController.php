<?php

namespace App\Controller;

use App\Entity\Marque;
use App\Form\MarqueType;
use App\Repository\MarqueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/marque")
 */
class AdminMarqueController extends AbstractController
{
    /**
     * @Route("/", name="app_admin_marque_index", methods={"GET"})
     */
    public function index(MarqueRepository $marqueRepository): Response
    {
        return $this->render('admin_marque/index.html.twig', [
            'marques' => $marqueRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_admin_marque_new", methods={"GET", "POST"})
     */
    public function new(Request $request, MarqueRepository $marqueRepository): Response
    {
        $marque = new Marque();
        $form = $this->createForm(MarqueType::class, $marque);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $marqueRepository->add($marque, true);

            $this->addFlash("success", "La marque " . $marque->getNom() . " a bien été ajoutée");
            return $this->redirectToRoute('app_admin_marque_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_marque/new.html.twig', [
            'marque' => $marque,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_marque_show", methods={"GET"})
     */
    public function show(Marque $marque): Response
    {
        return $this->render('admin_marque/show.html.twig', [
            'marque' => $marque,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_admin_marque_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Marque $marque, MarqueRepository $marqueRepository): Response
    {
        $form = $this->createForm(MarqueType::class, $marque);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $marqueRepository->add($marque, true);

            return $this->redirectToRoute('app_admin_marque_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_marque/edit.html.twig', [
            'marque' => $marque,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_marque_delete", methods={"POST"})
     */
    public function delete(Request $request, Marque $marque, MarqueRepository $marqueRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$marque->getId(), $request->request->get('_token'))) {
            $marqueRepository->remove($marque, true);
        }

        return $this->redirectToRoute('app_admin_marque_index', [], Response::HTTP_SEE_OTHER);
    }
}
