<?php

namespace App\Controller;

use App\Entity\Revision;
use App\Form\RevisionType;
use App\Repository\RevisionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/revision")
 */
class RevisionController extends AbstractController
{
    /**
     * @Route("/", name="revision_index", methods={"GET"})
     */
    public function index(RevisionRepository $revisionRepository): Response
    {
        return $this->render('revision/index.html.twig', [
            'revisions' => $revisionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="revision_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $revision = new Revision();
        $form = $this->createForm(RevisionType::class, $revision);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($revision);
            $entityManager->flush();

            return $this->redirectToRoute('revision_index');
        }

        return $this->render('revision/new.html.twig', [
            'revision' => $revision,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="revision_show", methods={"GET"})
     */
    public function show(Revision $revision): Response
    {
        return $this->render('revision/show.html.twig', [
            'revision' => $revision,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="revision_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Revision $revision): Response
    {
        $form = $this->createForm(RevisionType::class, $revision);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('revision_index');
        }

        return $this->render('revision/edit.html.twig', [
            'revision' => $revision,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="revision_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Revision $revision): Response
    {
        if ($this->isCsrfTokenValid('delete'.$revision->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($revision);
            $entityManager->flush();
        }

        return $this->redirectToRoute('revision_index');
    }
}
