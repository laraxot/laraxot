<p>Segnalazione non conformita WTS.</p>

<p><strong>Formulario:</strong> {{ $report->form_number ?? 'N/D' }}</p>
<p><strong>Lotto:</strong> {{ $report->lot?->lot_code ?? 'N/D' }}</p>
<p><strong>Nota:</strong> {{ $report->note }}</p>

<p>La segnalazione e stata registrata nel sistema WTS.</p>
