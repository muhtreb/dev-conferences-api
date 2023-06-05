<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\AccessToken\AccessTokenExtractorInterface;

readonly class AdminTokenExtractor implements AccessTokenExtractorInterface
{
    public function extractAccessToken(Request $request): ?string
    {
        return $request->headers->get('X-AUTH-TOKEN');
    }
}
