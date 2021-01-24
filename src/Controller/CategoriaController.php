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
            //Variables
            $nombre = $request->request->get('nombre',null);
            $color = $request->request->get('color',null);
            $categoria->setNombre($nombre);
            $categoria->setColor($color);

            if($nombre && $color){
                $entityManager->persist($categoria);
                $entityManager->flush();
                $this->addFlash('success','Categoría creada correctamente');
                return $this->redirectToRoute('app_listado_categoria');
                
            } else {
                //die('uno vino vacío');
                if(!$nombre){
                    //die('nombre vino vacío');
                    $this->addFlash('danger', 'El nombre es obligatorio');
                }
                if(!$color){
                    $this->addFlash('danger', 'El color es obligatorio');
                    }
            }
        } 
        return $this->render('categoria/nueva.html.twig', ['categoria' => $categoria]);
    }

    /**
     * @Route("/{id}/editar", name="app_editar_categoria")
     */
    public function editar(Categoria $categoria, EntityManagerInterface $entityManager, Request $request): Response
    {
             
        if($this->isCsrfTokenValid('categoria', $request->request->get('_token'))){
            //Variables
            $nombre = $request->request->get('nombre',null);
            //die($nombre);
            $color = $request->request->get('color',null);
            $categoria->setNombre($nombre);
            $categoria->setColor($color);

            if($nombre && $color){
                $entityManager->persist($categoria);
                $entityManager->flush();
                $this->addFlash('success','Categoría editada correctamente');
                return $this->redirectToRoute('app_listado_categoria');
                
            } else {
                //die('uno vino vacío');
                if(!$nombre){
                    //die('nombre vino vacío');
                    $this->addFlash('danger', 'El nombre es obligatorio');
                }
                if(!$color){
                    $this->addFlash('danger', 'El color es obligatorio');
                    }
            }
        } 
        return $this->render('categoria/editar.html.twig', ['categoria' => $categoria]);
    }

     /**
     * @Route("/{id}/eliminar", name="app_eliminar_categoria")
     */
    public function eliminar(Categoria $categoria, Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($categoria);
        $entityManager->flush();
        $this->addFlash('success','Categoría eliminada correctamente');
        return $this->redirectToRoute('app_listado_categoria');

    }
}
