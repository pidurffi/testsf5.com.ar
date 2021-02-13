<?php

namespace App\Controller;

use App\Form\BuscadorType;
use App\Repository\CategoriaRepository;
use App\Repository\MarcadorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    public const ELEMENTOS_POR_PAGINA = 5;
    /**
     * @route(
     * "/buscar/{busqueda}", 
     * name="app_busqueda", 
     * defaults={
     *  "busqueda": ""
     * }
     *)
     */
    public function busqueda(string $busqueda, MarcadorRepository $marcadorRepository, Request $request)
    {
        $formularioBusqueda = $this->createForm(BuscadorType::class);
        $formularioBusqueda->handleRequest($request);
        $marcadores=[];
        if($formularioBusqueda->isSubmitted()) {
            if($formularioBusqueda->isValid()){
                $busqueda = $formularioBusqueda->get('busqueda')->getData();
            }
        }

        if(!empty($busqueda)){
            $marcadores = $marcadorRepository->buscarPorNombre($busqueda);
        }

        if(!empty($busqueda) || $formularioBusqueda->isSubmitted()) {
            return $this->render('index/index.html.twig',[
                'formulario_busqueda' => $formularioBusqueda->createView(),
                'marcadores' => $marcadores
            ]);
        }

        return $this->render('common/_buscador.html.twig',[
            'formulario_busqueda' => $formularioBusqueda->createView()
        ]);
    }

    /**
     * @route("/editar-favorito", name="app_editar_favorito" )
     */
    public function editarFavorito(MarcadorRepository $marcadorRepository, Request $request)
    {
        if ($request->isXmlHttpRequest()){
            $idMarcador = $request->get('id');
            $entityManager = $this->getDoctrine()->getManager();
            $actualizado = true;
            //busco el mrcador por id
            $marcador = $marcadorRepository->findOneById($idMarcador);
            $marcador->setFavorito(!$marcador->getFavorito());

            try {
                $entityManager->flush();
            } catch(\Exception $e) {
                $actualizado = false;
            }
            return $this->json([
                'actualizado' => $actualizado
            ]);
        }
        throw $this->createNotFoundException();
    }

    /**
     * @route("/favoritos", name="app_favoritos" )
     */
    public function favoritos(MarcadorRepository $marcadorRepository)
    {
        $marcadores = $marcadorRepository->findBy([
            'favorito' => true
        ]);

        return $this->render('index/index.html.twig', [
            'marcadores' => $marcadores,
        ]);
    }
    
    /**
     * @Route(
     *  "/{categoria}/{pagina}", 
     *  name="app_index",
     *  defaults={
     *      "categoria":"todas",
     *      "pagina":1
     *  },
     *  requirements={
     *      "pagina"="\d+"
     * }
     * )
     */
    public function index(string $categoria, int $pagina,  CategoriaRepository $categoriaRepository, MarcadorRepository $marcadorRepository)
    {
        $elementosPorPagina = self::ELEMENTOS_POR_PAGINA;

        $categoria = (int)$categoria > 0 ? (int)$categoria : $categoria;
        if(is_int($categoria)){
            $categoria = 'todas';
            $pagina = $categoria;
        }

        if (($categoria) && 'todas' !== $categoria) {
            if(!$categoriaRepository->findByNombre($categoria)){
                throw $this->createNotFoundException("La categoría '$categoria' no existe.");
            }
            $marcadores = $marcadorRepository->buscarPorNombreCategoria($categoria, $pagina, self::ELEMENTOS_POR_PAGINA);
        } else {
            $marcadores = $marcadorRepository->buscarTodos($pagina, self::ELEMENTOS_POR_PAGINA);
        }
        
        return $this->render('index/index.html.twig', [
            'marcadores' => $marcadores,
            'pagina' => $pagina,
            'parametros_ruta' => [
                'categoria' =>$categoria
            ],
            'elementos_por_pagina' => $elementosPorPagina
        ]);
    }
}
