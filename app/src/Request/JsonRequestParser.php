<?php

declare(strict_types=1);

namespace App\Request;

use App\Exception\RequestParseException;
use Symfony\Component\HttpFoundation\Request;

class JsonRequestParser implements RequestParserInterface
{
    /**
     * @param Request $request
     * @throws RequestParseException
     */
    public function parse(Request $request): void
    {
        $content = (string) $request->getContent();
        $content = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RequestParseException();
        }
        foreach ($content as $key => $value) {
            $request->request->set($key, $value);
        }
    }
}
