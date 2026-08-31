<?php

declare(strict_types=1);

namespace Modules\Giveback\Support;

use Barryvdh\DomPDF\Facade\Pdf;

final class GenerateGivebackMockupPdfAction
{
    public function execute(): string
    {
        return Pdf::loadView('giveback::pdf.mockup-document', [
            'document' => $this->documentData(),
        ])->setPaper('a4')->output();
    }

    /**
     * @return array<string, mixed>
     */
    public function documentData(): array
    {
        return [
            'title' => 'GiveBack Platform - Mockup PDF',
            'subtitle' => 'Versione riallineata al modello fortuna, premio piattaforma e widget pre-acquisto.',
            'summary' => [
                'Importo unico: 1 euro, senza punti e senza selettore importo.',
                'CTA cliente: Oggi sono fortunato / Tenti la fortuna.',
                'Il premio e gestito dalla piattaforma, non dal merchant.',
                'Widget previsto in pre-acquisto con split payment.',
                'Donazione merchant rinviata a una fase successiva.',
            ],
            'sections' => [
                [
                    'title' => '1. Prova cliente',
                    'lead' => 'Il cliente vede un messaggio semplice, prova la fortuna con 1 euro e riceve una promo immediata.',
                    'bullets' => [
                        'Pulsante principale: Oggi sono fortunato.',
                        'Messaggio secondario: Tenti la fortuna.',
                        'Nessun riferimento a buona causa, beneficenza o punti.',
                        'Premio mensile erogato dalla piattaforma GiveBack.',
                    ],
                ],
                [
                    'title' => '2. Widget pre-acquisto',
                    'lead' => 'Il box viene mostrato prima della conferma ordine, con split payment gia previsto nel disegno funzionale.',
                    'bullets' => [
                        'Posizionamento consigliato: pre-acquisto.',
                        'Pagamento unico lato cliente.',
                        'Ripartizione automatica dietro le quinte.',
                        'Donazione merchant non inclusa nell MVP.',
                    ],
                ],
                [
                    'title' => '3. Preparazione merchant',
                    'lead' => 'Il merchant prepara lo sconto, il widget e la campagna, mentre il premio resta in carico alla piattaforma.',
                    'bullets' => [
                        'Configurazione sconto o vantaggio commerciale.',
                        'Upload codici promo e testo di supporto.',
                        'Scelta del posizionamento widget.',
                        'Nessun campo causa benefica.',
                    ],
                ],
                [
                    'title' => '4. Dashboard merchant',
                    'lead' => 'La scrivania del merchant mostra performance del widget, coupon, campagne e alert operativi.',
                    'bullets' => [
                        'Tentativi fortuna registrati.',
                        'Conversione widget e promo emesse.',
                        'Alert su materiali campagna e codici promo.',
                        'Visibilita del premio piattaforma senza gestione logistica diretta.',
                    ],
                ],
                [
                    'title' => '5. Split payment e controlli',
                    'lead' => 'La piattaforma governa la ripartizione incassi, lasciando eventuali modelli merchant donation a step successivi.',
                    'bullets' => [
                        'Checkout unico lato cliente.',
                        'Quota merchant e quota piattaforma separate.',
                        'Controlli contabilita e riconciliazione.',
                        'Merchant donation valutata solo in fase successiva.',
                    ],
                ],
                [
                    'title' => '6. Premio piattaforma e compliance',
                    'lead' => 'Il premio non e del merchant: la piattaforma lo definisce e lo governa con supporto legale dedicato.',
                    'bullets' => [
                        'Regolamento e fideiussione restano obbligatori.',
                        'Consulente giochi a premio da coinvolgere prima del go-live.',
                        'Il merchant non gestisce la logistica premio come regola base.',
                        'La pagina resta dimostrativa finche la compliance non e chiusa.',
                    ],
                ],
                [
                    'title' => '7. Roadmap commerciale',
                    'lead' => 'La fase iniziale valida il modello con pochi merchant e separa il prodotto GiveBack dai servizi CRM e campagne.',
                    'bullets' => [
                        'MVP piattaforma GiveBack separato da CRM gia in uso da Giulia.',
                        'Corso CRM e gestione campagne quotati a parte.',
                        'Bando Veneto e supporto commercialista gestiti come stream esterno.',
                        'Scalabilita multi-merchant e modelli avanzati solo dopo validazione.',
                    ],
                ],
            ],
        ];
    }
}
