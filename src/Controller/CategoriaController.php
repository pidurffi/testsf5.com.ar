<?php

namespace App\Controller;

use App\Repository\CategoriaRepository; /* ESTO TUVE QUE AGREGARLO A MANO !!!!!!! */
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
* @Route("categoria/listado", name="app_listado_categoria")
*/
class CategoriaController extends AbstractController
{
    /**
    * @Route("/listado", name="app_listado_categoria")
    */
    public function index(CategoriaRepository $categoriaRepository): Response
        {
        $categorias = $categoriaRepository->findAll();
        dump($categorias); die();
        return $this->render('categoria/index.html.twig', [
            'controller_name' => 'CategoriaController',
        ]);
    }

    /**
     * @Route("/nueva", name="app_nueva_categoria")
     */
    public function nueva(CategoriaRepository $categoriaRepository): Response
        {
        
        return $this->render('categoria/nueva.html.twig', []);
    }
}
