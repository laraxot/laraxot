<?php

namespace Themes\Sixteen\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Fixcity\App\Models\News;
use Modules\Fixcity\App\Models\Ticket;

class ComuneController extends Controller
{
    /**
     * Homepage del comune
     */
    public function homepage(): View
    {
        $recentTickets = Ticket::with(['user', 'status', 'priority'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentNews = News::orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        return view('sixteen::pages.comune.homepage', compact('recentTickets', 'recentNews'));
    }

    /**
     * Pagina servizi
     */
    public function servizi(): View
    {
        $services = [
            [
                'nome' => 'Segnalazioni',
                'descrizione' => 'Segnala problemi e disservizi',
                'url' => route('fixcity.tickets.create'),
                'icona' => 'exclamation-triangle',
            ],
            [
                'nome' => 'Prenotazione Appuntamenti',
                'descrizione' => 'Prenota un appuntamento con gli uffici',
                'url' => route('comune.prenotazioni'),
                'icona' => 'calendar',
            ],
            [
                'nome' => 'Documenti e Moduli',
                'descrizione' => 'Scarica moduli e documenti',
                'url' => route('comune.documenti'),
                'icona' => 'file',
            ],
            [
                'nome' => 'Anagrafe',
                'descrizione' => 'Servizi anagrafici e stato civile',
                'url' => route('comune.anagrafe'),
                'icona' => 'user',
            ],
            [
                'nome' => 'Tributi',
                'descrizione' => 'Pagamento tasse e tributi comunali',
                'url' => route('comune.tributi'),
                'icona' => 'credit-card',
            ],
            [
                'nome' => 'Urbanistica',
                'descrizione' => 'Pratiche edilizie e urbanistiche',
                'url' => route('comune.urbanistica'),
                'icona' => 'building',
            ],
        ];

        return view('sixteen::pages.comune.servizi', compact('services'));
    }

    /**
     * Pagina novità
     */
    public function novita(): View
    {
        $news = News::orderBy('created_at', 'desc')
            ->paginate(10);

        return view('sixteen::pages.comune.novita', compact('news'));
    }

    /**
     * Dettaglio notizia
     */
    public function showNews(News $news): View
    {
        return view('sixteen::pages.comune.novita-detail', compact('news'));
    }

    /**
     * Pagina contatti
     */
    public function contatti(): View
    {
        return view('sixteen::pages.comune.contatti');
    }

    /**
     * Invia messaggio di contatto
     */
    public function sendContact(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telefono' => 'nullable|string|max:20',
            'oggetto' => 'required|string|max:255',
            'messaggio' => 'required|string|max:1000',
        ]);

        // Qui implementeresti l'invio dell'email
        // Mail::to(config('comune.email'))->send(new ContactMessage($request->all()));

        return redirect()->back()->with('success', 'Messaggio inviato con successo!');
    }

    /**
     * Pagina documenti
     */
    public function documenti(): View
    {
        $documenti = [
            [
                'titolo' => 'Regolamento Comunale',
                'descrizione' => 'Regolamento generale del comune',
                'tipo' => 'PDF',
                'dimensione' => '2.5 MB',
                'data' => '2024-01-15',
                'url' => '#',
            ],
            [
                'titolo' => 'Bilancio 2024',
                'descrizione' => 'Bilancio preventivo e consuntivo 2024',
                'tipo' => 'PDF',
                'dimensione' => '1.8 MB',
                'data' => '2024-01-10',
                'url' => '#',
            ],
            [
                'titolo' => 'Modulo Richiesta Carta d\'Identità',
                'descrizione' => 'Modulo per la richiesta di carta d\'identità',
                'tipo' => 'PDF',
                'dimensione' => '150 KB',
                'data' => '2024-01-05',
                'url' => '#',
            ],
        ];

        return view('sixteen::pages.comune.documenti', compact('documenti'));
    }

    /**
     * Pagina eventi
     */
    public function eventi(): View
    {
        $eventi = [
            [
                'titolo' => 'Festa del Patrono',
                'descrizione' => 'Celebrazione del santo patrono del comune',
                'data' => '2024-06-15',
                'ora' => '18:00',
                'luogo' => 'Piazza del Comune',
                'immagine' => 'evento1.jpg',
            ],
            [
                'titolo' => 'Mercato Contadino',
                'descrizione' => 'Mercato settimanale con prodotti locali',
                'data' => '2024-06-20',
                'ora' => '08:00',
                'luogo' => 'Via Roma',
                'immagine' => 'evento2.jpg',
            ],
            [
                'titolo' => 'Consiglio Comunale',
                'descrizione' => 'Seduta pubblica del consiglio comunale',
                'data' => '2024-06-25',
                'ora' => '20:30',
                'luogo' => 'Sala Consiliare',
                'immagine' => 'evento3.jpg',
            ],
        ];

        return view('sixteen::pages.comune.eventi', compact('eventi'));
    }

    /**
     * Pagina anagrafe
     */
    public function anagrafe(): View
    {
        return view('sixteen::pages.comune.anagrafe');
    }

    /**
     * Pagina tributi
     */
    public function tributi(): View
    {
        return view('sixteen::pages.comune.tributi');
    }

    /**
     * Pagina urbanistica
     */
    public function urbanistica(): View
    {
        return view('sixteen::pages.comune.urbanistica');
    }

    /**
     * Pagina prenotazioni
     */
    public function prenotazioni(): View
    {
        return view('sixteen::pages.comune.prenotazioni');
    }
}
