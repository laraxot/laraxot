{{--
  Header Base Component - Authentication State Detection
  
  Detects user auth state and renders appropriate header variant.
  Follows Comuni Italiani Design System (Bootstrap Italia).
  
  @see https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-area-personale.html
  @see .planning/stories/5.0-header-logged-in-state.story.md
--}}

@if(auth()->check())
    @include('components.header.authenticated', ['user' => auth()->user()])
@else
    @include('components.header.guest')
@endif
