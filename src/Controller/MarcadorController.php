<?php

namespace App\Controller;

use App\Entity\Marcador;
use App\Form\MarcadorType;
use App\Repository\MarcadorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/marcador")
 */
class MarcadorController extends AbstractController
{
    /**
     * @Route("/", name="marcador_index", methods={"GET"})
     */
    public function index(MarcadorRepository $marcadorRepository): Response
    {
        return $this->render('marcador/index.html.twig', [
            'marcadors' => $marcadorRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="marcador_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $marcador = new Marcador();
        $form = $this->createForm(MarcadorType::class, $marcador);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($marcador);
            $entityManager->flush();

            return $this->redirectToRoute('marcador_index');
        }

        return $this->render('marcador/new.html.twig', [
            'marcador' => $marcador,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="marcador_show", methods={"GET"})
     */
    public function show(Marcador $marcador): Response
    {
        return $this->render('marcador/show.html.twig', [
            'marcador' => $marcador,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="marcador_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Marcador $marcador): Response
    {
        $form = $this->createForm(MarcadorType::class, $marcador);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('marcador_index');
        }

        return $this->render('marcador/edit.html.twig', [
            'marcador' => $marcador,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="marcador_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Marcador $marcador): Response
    {
        if ($this->isCsrfTokenValid('delete'.$marcador->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($marcador);
            $entityManager->flush();
        }

        return $this->redirectToRoute('marcador_index');
    }
}
