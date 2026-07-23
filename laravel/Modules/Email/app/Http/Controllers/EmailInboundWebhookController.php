<?php

declare(strict_types=1);

namespace Modules\Email\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Email\Actions\StoreIncomingEmailMessageAction;
use Modules\Email\Security\EmailWebhookSignatureVerifier;

final class EmailInboundWebhookController extends Controller
{
    public function receive(
        Request $request,
        EmailWebhookSignatureVerifier $signatureVerifier,
        StoreIncomingEmailMessageAction $storeIncoming,
    ): JsonResponse {
        $rawBody = $request->getContent();
        $signature = $request->header('X-Email-AI-Signature');

        if (! $signatureVerifier->isValid($rawBody, $signature)) {
            return response()->json(['ok' => false, 'error' => 'Firma webhook email non valida.'], 403);
        }

        $payload = $request->json()->all();
        if (! is_array($payload)) {
            return response()->json(['ok' => false, 'error' => 'Payload email non valido.'], 422);
        }

        try {
            $message = $storeIncoming->execute($payload);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['ok' => false, 'error' => $e->getMessage()], 422);
        }

        return response()->json([
            'ok' => true,
            'stored_message_id' => $message->id,
        ]);
    }
}
