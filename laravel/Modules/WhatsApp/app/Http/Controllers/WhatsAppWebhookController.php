<?php

declare(strict_types=1);

namespace Modules\WhatsApp\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\WhatsApp\Actions\StoreIncomingWhatsAppMessageAction;
use Modules\WhatsApp\Security\MetaWebhookSignatureVerifier;
use Modules\WhatsApp\Security\YCloudWebhookSignatureVerifier;
use Modules\WhatsApp\Settings\WhatsAppSettings;

final class WhatsAppWebhookController extends Controller
{
    public function verify(Request $request, WhatsAppSettings $settings): Response
    {
        $mode = (string) $request->query('hub_mode', $request->query('hub.mode', ''));
        $token = (string) $request->query('hub_verify_token', $request->query('hub.verify_token', ''));
        $challenge = (string) $request->query('hub_challenge', $request->query('hub.challenge', ''));

        if ($mode === 'subscribe' && $token !== '' && hash_equals($settings->verifyToken(), $token)) {
            return response($challenge, 200)->header('Content-Type', 'text/plain');
        }

        return response('Forbidden', 403)->header('Content-Type', 'text/plain');
    }

    public function receive(
        Request $request,
        MetaWebhookSignatureVerifier $signatureVerifier,
        StoreIncomingWhatsAppMessageAction $storeIncoming,
    ): JsonResponse {
        $rawBody = $request->getContent();
        $signature = $request->header('X-Hub-Signature-256');

        if (! $signatureVerifier->isValid($rawBody, $signature)) {
            return response()->json(['ok' => false, 'error' => 'Firma webhook non valida.'], 403);
        }

        $payload = $request->json()->all();
        if (! is_array($payload)) {
            return response()->json(['ok' => false, 'error' => 'Payload webhook non valido.'], 422);
        }

        $messages = $storeIncoming->execute($payload);

        return response()->json([
            'ok' => true,
            'stored_messages' => count($messages),
        ]);
    }

    public function receiveYCloud(
        Request $request,
        YCloudWebhookSignatureVerifier $signatureVerifier,
        StoreIncomingWhatsAppMessageAction $storeIncoming,
    ): JsonResponse {
        $rawBody = $request->getContent();
        $signature = $request->header('YCloud-Signature');

        if (! $signatureVerifier->isValid($rawBody, $signature)) {
            return response()->json(['ok' => false, 'error' => 'Firma webhook YCloud non valida.'], 403);
        }

        $payload = $request->json()->all();
        if (! is_array($payload)) {
            return response()->json(['ok' => false, 'error' => 'Payload webhook YCloud non valido.'], 422);
        }

        $messages = $storeIncoming->execute($payload);

        return response()->json([
            'ok' => true,
            'provider' => 'ycloud',
            'stored_messages' => count($messages),
        ]);
    }
}
