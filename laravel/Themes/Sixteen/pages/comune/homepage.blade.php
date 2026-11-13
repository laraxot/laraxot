@extends('sixteen::layouts.app')

@section('title', __('pub_theme::comune_pages.homepage.page_title.title', ['name' => config('comune.nome', 'Nome Comune')]))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="it-page-section">
                <h1>{{ __('pub_theme::comune_pages.homepage.welcome_to_municipality.label', ['name' => config('comune.nome', 'Nome Comune')]) }}</h1>
                <p class="lead">{{ __('pub_theme::comune_pages.homepage.the_official_portal_for_the_municipality.message') }}</p>
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
                        <h5 class="card-title">{{ __('pub_theme::blocks_common.shared.reports.label') }}</h5>
                        <p class="card-text">{{ __('pub_theme::comune_pages.homepage.report_problems_and_service_issues_in.label') }}</p>
                        <a href="{{ route('segnalazioni') }}" class="btn btn-primary">{{ __('pub_theme::comune_pages.homepage.report.label') }}</a>
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
                        <h5 class="card-title">{{ __('pub_theme::blocks_common.shared.services.label') }}</h5>
                        <p class="card-text">{{ __('pub_theme::comune_pages.homepage.access_the_municipality_s_online_services.label') }}</p>
                        <a href="{{ route('services.categories') }}" class="btn btn-primary">{{ __('pub_theme::comune_pages.homepage.go_to_services.label') }}</a>
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
                        <h5 class="card-title">{{ __('pub_theme::blocks_common.shared.news.label') }}</h5>
                        <p class="card-text">{{ __('pub_theme::comune_pages.homepage.discover_the_latest_news_and_announcements.label') }}</p>
                        <a href="{{ route('news') }}" class="btn btn-primary">{{ __('pub_theme::comune_pages.homepage.read_the_news.label') }}</a>
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
                <h2>{{ __('pub_theme::comune_pages.homepage.latest_reports.label') }}</h2>
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
                                    <a href="{{ route('container0.view', ['container0' => 'tickets', 'slug0' => $ticket->getKey()]) }}" class="btn btn-outline-primary btn-sm">{{ __('pub_theme::blocks_common.shared.details.label') }}</a>
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
                <h2>{{ __('pub_theme::comune_pages.homepage.latest_news.label') }}</h2>
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
                                    <a href="{{ route('events.show', $news) }}" class="btn btn-outline-primary btn-sm">{{ __('pub_theme::blocks_common.shared.read.label') }}</a>
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


