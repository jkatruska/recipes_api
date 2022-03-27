<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\ValidationException;
use App\Service\AuthService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/auth')]
class AuthController extends AbstractController
{
    /**
     * @return JsonResponse
     */
    #[Route('/login', name: 'auth_login', methods: ['POST'])]
    public function login(): JsonResponse
    {
        return $this->json(['test']);
    }

    /**
     * @param Request $request
     * @param AuthService $authService
     * @throws ValidationException
     * @return JsonResponse
     */
    #[Route('/register', name: 'auth_register', methods: ['POST'])]
    public function register(Request $request, AuthService $authService): JsonResponse
    {
        $authService->register($request->request->all());
        return $this->json(['ok']);
    }

    /**
     * Never called, used just by security
     */
    #[Route('/logout', name: 'auth_logout', methods: ['GET'])]
    public function logout(): void
    {
    }
}
