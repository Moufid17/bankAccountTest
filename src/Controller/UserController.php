<?php

namespace App\Controller;

use App\Services\UserService;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private UserService $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    #[Route('/users/{user}/accounts', name: 'app_user', methods: ['GET'])]
    public function index($userId, Request $request): JsonResponse
    {
        return $this->json([
            'response' => $this->userService->account($userId),
        ]);
    }

    #[Route('/users/{user}/accounts/credit', name: 'app_user_credit', methods: ['PUT'])]
    public function credit($user, Request $request): JsonResponse
    {   
        # Request Body content
        $jsonRequest = json_decode($request->getContent(), true);

        # Account data response.
        $response = $this->userService->credit($user, $jsonRequest["amount"]);

        return $this->json([
            'response' => $response,
        ]);
    }

    #[Route('/users/{user}/accounts/debit', name: 'app_user_debit', methods: ['PUT'])]
    public function debit($user, Request $request): Response
    {
        # Request Body content
        $jsonRequest = json_decode($request->getContent(), true);
        # Account data response.
        $response = $this->userService->debit($user, $jsonRequest["amount"]);
        
        return $this->json([
            'response' => $response,
        ]);
    }

    #[Route('/users/accounts/notif', name: 'app_user_notif', methods: ['GET'])]
    public function notif(): JsonResponse
    {
        
        return $this->json([
            'response' => $this->userService->notif(),
        ]);
    }
}
