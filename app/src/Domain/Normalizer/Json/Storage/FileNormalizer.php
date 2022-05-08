<?php

declare(strict_types=1);

namespace App\Domain\Normalizer\Json\Storage;

use App\Domain\Storage\File;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class FileNormalizer implements NormalizerInterface
{
    public const CONTEXT_DETAIL = 'detail';
    public const CONTEXT_UPLOAD = 'upload';

    /**
     * @param File $object
     * @param array<int, string> $context
     */
    public function normalize(mixed $object, string $format = null, array $context = []): array
    {
        return match ($context[0]) {
            self::CONTEXT_DETAIL => $this->formatDetail($object),
            self::CONTEXT_UPLOAD => $this->formatUploadData($object),
            default => []
        };
    }

    public function supportsNormalization(mixed $data, string $format = null): bool
    {
        return $data instanceof File && $format === 'json';
    }

    private function formatDetail(File $file): array
    {
        return [
            'key' => $file->getKey(),
            'url' => $file->getUrl()
        ];
    }

    private function formatUploadData(File $file): array
    {
        return [
            'key' => $file->getKey()
        ];
    }
}
