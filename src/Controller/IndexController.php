<?php

namespace App\Controller;

use App\Repository\MarcadorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="app_index")
     */
    public function index(MarcadorRepository $marcadorRepository)
    {
        $marcadores = $marcadorRepository->findAll();
        return $this->render('index/index.html.twig', [
            'marcadores' => $marcadores,
        ]);
    }
}
