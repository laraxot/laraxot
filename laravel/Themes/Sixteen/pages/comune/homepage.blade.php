@extends('sixteen::layouts.app')

@section('title', 'Homepage - Comune di ' . config('comune.nome', 'Nome Comune'))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="it-page-section">
                <h1>Benvenuti nel Comune di {{ config('comune.nome', 'Nome Comune') }}</h1>
                <p class="lead">Il portale ufficiale per i servizi e le informazioni del comune</p>
            </div>
        </div>
    </div>
    
    <!-- Servizi Principali -->
    <div class="row">
        <div class="col-md-4">
            <div class="card-wrapper">
                <div class="card">
                    <div class="card-body">
                        <div class="it-header-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <h5 class="card-title">Segnalazioni</h5>
                        <p class="card-text">Segnala problemi e disservizi nel territorio comunale</p>
                        <a href="{{ route('fixcity.tickets.create') }}" class="btn btn-primary">Segnala</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card-wrapper">
                <div class="card">
                    <div class="card-body">
                        <div class="it-header-icon">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <h5 class="card-title">Servizi</h5>
                        <p class="card-text">Accedi ai servizi online del comune</p>
                        <a href="{{ route('comune.servizi') }}" class="btn btn-primary">Vai ai Servizi</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card-wrapper">
                <div class="card">
                    <div class="card-body">
                        <div class="it-header-icon">
                            <i class="fas fa-newspaper"></i>
                        </div>
                        <h5 class="card-title">Novità</h5>
                        <p class="card-text">Scopri le ultime notizie e comunicati</p>
                        <a href="{{ route('comune.novita') }}" class="btn btn-primary">Leggi le Novità</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Ultime Segnalazioni -->
    @if(isset($recentTickets) && $recentTickets->count() > 0)
    <div class="row mt-5">
        <div class="col-12">
            <div class="it-page-section">
                <h2>Ultime Segnalazioni</h2>
                <div class="row">
                    @foreach($recentTickets as $ticket)
                    <div class="col-md-6 col-lg-4">
                        <div class="card-wrapper">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">{{ $ticket->name }}</h6>
                                    <p class="card-text">{{ Str::limit($ticket->description, 100) }}</p>
                                    <div class="card-meta">
                                        <span class="badge badge-{{ $ticket->priority }}">{{ $ticket->priority_label }}</span>
                                        <span class="badge badge-{{ $ticket->status }}">{{ $ticket->status_label }}</span>
                                    </div>
                                    <a href="{{ route('fixcity.tickets.show', $ticket) }}" class="btn btn-outline-primary btn-sm">Dettagli</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Ultime Novità -->
    @if(isset($recentNews) && $recentNews->count() > 0)
    <div class="row mt-5">
        <div class="col-12">
            <div class="it-page-section">
                <h2>Ultime Novità</h2>
                <div class="row">
                    @foreach($recentNews as $news)
                    <div class="col-md-6 col-lg-4">
                        <div class="card-wrapper">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">{{ $news->title }}</h6>
                                    <p class="card-text">{{ Str::limit($news->excerpt, 100) }}</p>
                                    <div class="card-meta">
                                        <small class="text-muted">{{ $news->created_at->format('d/m/Y') }}</small>
                                    </div>
                                    <a href="{{ route('comune.novita.show', $news) }}" class="btn btn-outline-primary btn-sm">Leggi</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection


