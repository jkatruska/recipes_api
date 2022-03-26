<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/auth')]
class AuthController extends AbstractController
{
    /**
     * @return JsonResponse
     */
    #[Route('/login', name: 'auth_login')]
    public function login(): JsonResponse
    {
        return $this->json(['test']);
    }
}