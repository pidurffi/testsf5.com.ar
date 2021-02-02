<?php

namespace App\Controller;

use App\Repository\CategoriaRepository;
use App\Repository\MarcadorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    
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
     *  "/{categoria}", 
     *  name="app_index",
     *  defaults={"categoria":""}
     * )
     */
    public function index(string $categoria, CategoriaRepository $categoriaRepository, MarcadorRepository $marcadorRepository)
    {
        if (!empty($categoria)) {
            if(!$categoriaRepository->findByNombre($categoria)){
                throw $this->createNotFoundException("La categoría '$categoria' no existe.");
            }
            $marcadores = $marcadorRepository->buscarPorNombreCategoria($categoria);
        } else {
            $marcadores = $marcadorRepository->findAll();
        }
        
        return $this->render('index/index.html.twig', [
            'marcadores' => $marcadores,
        ]);
    }
}
