<?php

namespace App\Controller;

use App\Services\UserService;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private $userService;
    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    #[Route('/users/{user}/accounts', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [       
        ]);
    }

    #[Route('/users/{user}/accounts/credit/{amount}', name: 'app_user_credit')]
    public function credit($user, $amount): Response
    {
        $response = $this->userService->credit($user, $amount);
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'response' => $response
        ]);
    }

    #[Route('/users/{user}/accounts/debit/{amount}', name: 'app_user_debit')]
    public function debit($user, $amount): Response
    {
        $response = $this->userService->debit($user, $amount);
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'response' => $response
        ]);
    }
}
