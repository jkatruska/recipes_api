<?php

declare(strict_types=1);

namespace App\Request;

use App\Exception\RequestParseException;
use Symfony\Component\HttpFoundation\Request;

interface RequestParserInterface
{
    /**
     * @param Request $request
     * @throws RequestParseException
     */
    public function parse(Request $request): void;
}
