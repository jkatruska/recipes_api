<?php

declare(strict_types=1);

namespace App\Controller;

use App\Domain\Normalizer\Json\Storage\FileNormalizer;
use App\Exception\ValidationException;
use App\Service\StorageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/photo')]
class PhotoController extends AbstractController
{
    public function __construct(private StorageService $storageService)
    {
    }

    #[Route('/{key}', name: 'photo_detail', methods: ['GET'])]
    public function get(string $key): JsonResponse|Response
    {
        $file = $this->storageService->get($key);
        if (!$file) {
            return new Response(null, Response::HTTP_NOT_FOUND);
        }
        return $this->json(data: $file, context: [FileNormalizer::CONTEXT_DETAIL]);
    }

    /**
     * @throws ValidationException
     */
    #[Route('', name: 'photo_upload', methods: ['POST'])]
    public function upload(): JsonResponse
    {
        $file = $this->storageService->save($_FILES['file'] ?? []);
        return $this->json(data: $file, context: [FileNormalizer::CONTEXT_UPLOAD]);
    }
}
