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

    #[Route('/users/{user}/accounts', name: 'app_user')]
    public function index(Request $request): JsonResponse
    {
        $response = $this->userService->account(1);
/*        return $this->render('user/index.html.twig', [
            'response' => $response,
            'request' => $request
        ]);*/
        return $this->json([
            'response' => $response,
            'request' => $request
        ]);
    }

    #[Route('/users/{user}/accounts/credit', name: 'app_user_credit')]
    public function credit($user, Request $request): JsonResponse
    {
        $jsonRequest = json_decode($request->getContent(), true);
        $response = $this->userService->credit($user, $jsonRequest["amount"]);
        return $this->json([
            'response' => $response,
            'request' => $jsonRequest
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
