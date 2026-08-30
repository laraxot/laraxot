<?php

declare(strict_types=1);

namespace Modules\Intervention\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Intervention\Actions\ConfirmInterventionReportPaymentAction;
use Modules\Intervention\Actions\CreateInterventionReportCheckoutAction;
use Modules\Intervention\Actions\GenerateInterventionReportPdfAction;
use Modules\Intervention\Actions\GetInterventionReportPublicDataAction;
use Modules\Intervention\Exceptions\ReportPaymentNotAvailableException;
use Modules\Intervention\Models\Intervention;
use Modules\Intervention\Models\InterventionReportShare;
use Modules\Signature\Actions\StoreSignatureForModelAction;
use RuntimeException;

final class PublicInterventionReportController extends Controller
{
    public function show(string $token, Request $request): View
    {
        $intervention = $this->resolveIntervention($token);

        /** @var view-string $reportView */
        $reportView = 'intervention::reports.intervention-report-public';

        return view($reportView, [
            'report' => app(GetInterventionReportPublicDataAction::class)->execute($intervention),
            'pdfUrl' => route('interventions.report.public.pdf', ['token' => $token]),
            'payUrl' => route('interventions.report.public.pay', ['token' => $token]),
            'signUrl' => route('interventions.report.public.sign', ['token' => $token]),
            'paymentState' => $request->string('payment')->toString(),
            'signatureState' => $request->string('signature')->toString(),
        ]);
    }

    public function pdf(string $token): Response
    {
        $intervention = $this->resolveIntervention($token);
        $pdf = app(GenerateInterventionReportPdfAction::class)->execute($intervention);

        return response($pdf['content'], 200, [
            'Content-Type' => $pdf['mime'],
            'Content-Disposition' => 'inline; filename="'.$pdf['filename'].'"',
        ]);
    }

    public function sign(string $token, Request $request): RedirectResponse
    {
        $intervention = $this->resolveIntervention($token);
        $reportUrl = route('interventions.report.public', ['token' => $token]);
        $dataUrl = $request->string('signature')->toString();

        try {
            app(StoreSignatureForModelAction::class)->execute(
                dataUrl: $dataUrl,
                signableType: Intervention::class,
                signableId: (int) $intervention->id,
                userId: (int) $intervention->performed_by_user_id,
                payload: ['source' => 'public_report', 'signed_by' => 'customer'],
            );
        } catch (RuntimeException) {
            return redirect()->to($reportUrl.'?signature=invalid');
        }

        return redirect()->to($reportUrl.'?signature=ok');
    }

    public function pay(string $token): RedirectResponse
    {
        $intervention = $this->resolveIntervention($token);
        $reportUrl = route('interventions.report.public', ['token' => $token]);
        $returnUrl = route('interventions.report.public.return', ['token' => $token]);

        try {
            $checkoutUrl = app(CreateInterventionReportCheckoutAction::class)->execute(
                $intervention,
                $returnUrl.'?session_id={CHECKOUT_SESSION_ID}',
                $reportUrl,
            );
        } catch (ReportPaymentNotAvailableException) {
            return redirect()->to($reportUrl.'?payment=unavailable');
        }

        return redirect()->away($checkoutUrl);
    }

    public function paymentReturn(string $token, Request $request): RedirectResponse
    {
        $this->resolveIntervention($token);
        $reportUrl = route('interventions.report.public', ['token' => $token]);
        $sessionId = $request->string('session_id')->toString();

        $paid = $sessionId !== ''
            && app(ConfirmInterventionReportPaymentAction::class)->execute($sessionId);

        return redirect()->to($reportUrl.'?payment='.($paid ? 'success' : 'failed'));
    }

    private function resolveIntervention(string $token): Intervention
    {
        $share = InterventionReportShare::query()
            ->with('intervention')
            ->where('token', $token)
            ->first();

        if ($share === null || $share->isExpired() || $share->intervention === null) {
            abort(404);
        }

        return $share->intervention;
    }
}
