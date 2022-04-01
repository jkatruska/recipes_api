<?php

declare(strict_types=1);

namespace App\Domain\Normalizer\Json;

use App\Exception\DomainExceptionInterface;
use App\Exception\PayloadExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class DomainExceptionNormalizer implements NormalizerInterface
{
    /**
     * @param DomainExceptionInterface $object
     * @param string|null $format
     * @param array $context
     * @return array
     */
    public function normalize(mixed $object, string $format = null, array $context = []): array
    {
        $out = [
            'title' => $object->getTitle(),
        ];
        if ($object instanceof PayloadExceptionInterface) {
            $out['data'] = $object->getData();
        }
        return $out;
    }

    /**
     * @param mixed $data
     * @param string|null $format
     * @return bool
     */
    public function supportsNormalization(mixed $data, string $format = null): bool
    {
        return $data instanceof DomainExceptionInterface && $format === 'json';
    }
}
