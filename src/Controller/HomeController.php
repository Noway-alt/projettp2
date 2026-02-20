<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class HomeController extends AbstractController
{
    #[Route("/", name:"index")]
    public function index(): Response 
    {
        return new Response("<html><h1>bonjour</h1></html>");
    }
    
    #[Route("/about", name:"about")]
    public function about(): Response
    {
        return $this->render("about.html.twig");
    }

    #[Route("/hello/{name}", name:"hello")]
    public function hello($name): Response
    {
        return $this->render("hello.html.twig", ["name"=>ucfirst($name)]);
    }


    #[Route("/random", name:"random")]
    public function random(): Response
    {
        $quotes = [
            "follow the white rabbit",
            "may the force be with you",
            "I'll be back",
            "you shall not pass"
        ];
        $randomQuote = $quotes[random_int(0,sizeof($quotes)-1)];
        return $this->render("random.html.twig", ["quote"=>$randomQuote]);
    }

    #[Route("/menu", name:"menu")]
    public function menu(): Response
    {
        $menuItems = [
            ["title"=>"Home", "route"=>"index"],
            ["title"=>"About", "route"=>"about"],
            ["title"=>"Hello", "route"=>"hello", "params"=>["name"=>"Guest"]],
            ["title"=>"Random Quote", "route"=>"random"]
        ];
        return $this->render("menu.html.twig", ["menuItems"=>$menuItems]);
    }

    #[Route('/list', name: 'list')]
    public function list(): Response
    {
        $items = [
            [
                'title' => 'Football',
                'description' => 'Se joue aux pieds',
                'popular' => true,
                'date' => new \DateTime('2026-01-15')
            ],
            [
                'title' => 'Basketball',
                'description' => 'Se joue aux mains',
                'popular' => true,
                'date' => new \DateTime('2025-11-25')
            ],
            [
                'title' => 'Volleyball',
                'description' => 'Se joue aux mains (ça fait mal par contre)',
                'popular' => false,
                'date' => new \DateTime('2024-01-25')
            ],
        ];

        return $this->render('list.html.twig', [
            'items' => $items
        ]);
    }    
}
?> 