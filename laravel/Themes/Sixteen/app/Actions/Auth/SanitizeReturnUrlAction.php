<?php

declare(strict_types=1);

namespace Themes\Sixteen\Actions\Auth;

use Illuminate\Http\Request;
use Safe\Exceptions\UrlException;

use function Safe\parse_url;
use function Safe\preg_match;

final class SanitizeReturnUrlAction
{
    public function execute(Request $request, mixed $candidate, string $fallback): string
    {
        if (! is_string($candidate) || $candidate === '' || str_contains($candidate, '\\') || preg_match('/[\x00-\x1F\x7F]/', $candidate) === 1) {
            return $fallback;
        }

        if (preg_match('#^/(?!/)#', $candidate) === 1) {
            $candidate = $request->getSchemeAndHttpHost().$candidate;
        }

        try {
            $parts = parse_url($candidate);
        } catch (UrlException) {
            return $fallback;
        }
        if (! is_array($parts)
            || ($parts['scheme'] ?? null) !== $request->getScheme()
            || ($parts['host'] ?? null) !== $request->getHost()
            || ($parts['port'] ?? ($parts['scheme'] === 'https' ? 443 : 80)) !== $request->getPort()) {
            return $fallback;
        }

        return $candidate;
    }
}
