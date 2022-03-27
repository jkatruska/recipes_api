<?php

declare(strict_types=1);

namespace App\Request;

use Symfony\Component\HttpFoundation\Request;

class RequestParserFactory
{
    public static function getParser(Request $request): ?RequestParserInterface
    {
        $contentType = $request->headers->get('Content-Type');
        if (!$contentType || empty($request->getContent())) {
            return null;
        }

        return match ($contentType) {
            'application/json' => new JsonRequestParser(),
            default => null
        };
    }
}
