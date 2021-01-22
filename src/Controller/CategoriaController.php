<?php

namespace App\Controller;

use App\Repository\CategoriaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface; 
use App\Entity\Categoria; 

 /**
    * @Route("/categoria")
    */
class CategoriaController extends AbstractController
{
    /**
    * @Route("/listado", name="app_listado_categoria")
    */
    public function index(CategoriaRepository $categoriaRepository): Response
        {
        $categorias = $categoriaRepository->findAll();
        return $this->render('categoria/index.html.twig', [
            'categorias' => $categorias,
        ]);
    }

    /**
     * @Route("/nueva", name="app_nueva_categoria")
     */
    public function nueva(CategoriaRepository $categoriaRepository, EntityManagerInterface $entityManager, Request $request): Response
    {
        $categoria = new Categoria();
       
        if($this->isCsrfTokenValid('categoria', $request->request->get('_token'))){
            $nombre = $request->request->get('nombre',null);
            $categoria->setNombre($nombre);
            if($nombre){
                $entityManager->persist($categoria);
                $entityManager->flush();
                $this->addFlash('success','Categoría creada correctamente');
                return $this->redirectToRoute('app_listado_categoria');
                
            } else {
                if(!$nombre){
                $this->addFlash('danger', 'El nombre es obligatorio');
                }
            }
        } 
        return $this->render('categoria/nueva.html.twig', ['categoria' => $categoria]);
    }
}
