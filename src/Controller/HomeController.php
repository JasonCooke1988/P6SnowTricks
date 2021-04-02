<?php


namespace App\Controller;


use App\Entity\Trick;
use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    /**
     * @Route("/", name="home")
     */
    public function index(TrickRepository $trickRepository): Response
    {

        $tricks = $trickRepository->findAll();

        return $this->render('layout/home.html.twig', [
            'header' => 'fullheight',
            'tricks' => $tricks
        ]);
    }

}