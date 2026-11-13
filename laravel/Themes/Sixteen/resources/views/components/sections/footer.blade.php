{{--
    Bootstrap Italia Footer - Section Component
    Supports: full (default), slim templates
    
    Usage:
    <x-section slug="footer" />           ← Full footer
    <x-section slug="footer" tpl="slim" /> ← Slim footer
--}}

@props(['blocks' => [], 'tpl' => 'full'])

@if($tpl === 'slim')
    @include('pub_theme::components.sections.footer.v1', ['blocks' => $blocks])
@else
    @include('pub_theme::components.sections.footer.full', ['blocks' => $blocks])
@endif
