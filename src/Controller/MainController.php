<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    private array $tours = [
        'askold' => [
            'title' => 'о. Аскольд',
            'full_title' => 'Путешествие на о. Аскольд',
            'price' => '6500',
            'duration' => '7-8 часов',
            'is_hit' => true,
            'image' => 'askold.jpg',
            'description' => '150 км. морских приключений за один день! Вас ждут уникальные маяки, стада пятнистых оленей и заброшенные военные объекты.',
        ],
        'rikorda' => [
            'title' => 'о. Рикорда',
            'full_title' => 'Маршрут вокруг о. Рикорда',
            'price' => '6500',
            'duration' => '7-8 часов',
            'is_hit' => true,
            'image' => 'rikorda.jpg',
            'description' => 'Чистейшая вода и необитаемые пляжи.',
        ],
    ];

    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'tours' => $this->tours,
        ]);
    }

    #[Route('/tour/{slug}', name: 'tour_detail')]
    public function detail(string $slug): Response
    {
        $tour = $this->tours[$slug] ?? throw $this->createNotFoundException();

        return $this->render('main/tour.html.twig', [
            'tour' => $tour,
        ]);
    }
}
