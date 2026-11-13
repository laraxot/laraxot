{{--
    Fascia contatti in fondo alla pagina. `contacts.layout`: offset (colonna 6 con offset 3) o narrow (colonna 5 centrata): sono le due
    varianti del riferimento per queste pagine; il partial condiviso dc-contacts ha solo offset e colonna 6.
    DCP-289: senza gruppi (nessuna voce con il suo dato) la fascia non si stampa; i gruppi li risolve ResolveContactGroupsAction e li passa al riquadro.
--}}
@php
    $contactGroups = app(\Themes\Sixteen\Actions\Contacts\ResolveContactGroupsAction::class)->execute(
        (array) $d('contacts', []),
        app(\Modules\Tenant\Actions\Ente\GetEnteDataAction::class)->execute(),
        request()->segment(2) !== 'tests',
    );
@endphp
@if ($contactGroups !== [])
<div class="bg-grey-card shadow-contacts">
    <div class="container">
        @php($offset = $d('contacts.layout', 'offset') === 'offset')
        <div @class(['row', 'd-flex justify-content-center p-contacts' => ! $offset])>
            <div @class(['col-12', 'col-lg-6 offset-lg-3 p-contacts' => $offset, 'col-lg-5' => ! $offset])>
                @include('pub_theme::components.blocks.report-partials.contacts-card')
            </div>
        </div>
    </div>
</div>
@endif
