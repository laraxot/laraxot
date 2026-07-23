{{--
    Bootstrap Italia Footer - Section Component
    Supports: full (default), slim templates
    
    Usage:
    <x-section slug="footer" />           ← Full footer
    <x-section slug="footer" tpl="slim" /> ← Slim footer
--}}

@props(['tpl' => 'full'])

@if($tpl === 'slim')
@else
@endif
