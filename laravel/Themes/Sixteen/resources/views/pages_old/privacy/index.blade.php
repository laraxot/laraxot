<?php

declare(strict_types=1);

use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Modules\Fixcity\Actions\GetPublishedPrivacyPolicyAction;

use function Laravel\Folio\name;
use function Laravel\Folio\render;

name('fixcity.privacy');

render(function (): Response {
    $policy = app(GetPublishedPrivacyPolicyAction::class)->execute();

    return response()->view(
        'fixcity::privacy.policy-page',
        [
            'policyHtml' => $policy === null
                ? null
                : (string) Str::markdown($policy, [
                    'html_input' => 'strip',
                    'allow_unsafe_links' => false,
                ]),
        ],
        $policy === null ? Response::HTTP_SERVICE_UNAVAILABLE : Response::HTTP_OK,
    );
});
