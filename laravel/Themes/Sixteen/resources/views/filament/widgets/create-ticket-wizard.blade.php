<x-filament-widgets::widget>
{{-- Design Comuni wrapper --}}
<div class="cmp-wizard-widget">
    {{-- Heading - max-width 1166px centered --}}
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <div class="cmp-heading pb-3 pb-lg-4">
                    <h1 class="title-xxxlarge">{{ $pageTitle }}</h1>
                    @if($pageDescription !== '')
                        <p class="text-paragraph mb-0">{{ $pageDescription }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Wizard Form - stepper full row; step content constrained by neutral Design Comuni CSS hooks --}}
    <div class="container wizard-dc-form-shell">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="wizard-dc-form-shell p-4 bg-white rounded-lg shadow-sm">
                    @if (isset($errors) && ($errors->has('data.submit') || $errors->has('submit')))
                        <div class="alert alert-danger mb-4" role="alert">
                            {{ $errors->first('data.submit') ?: $errors->first('submit') }}
                        </div>
                    @endif
                    {{ $this->form }}
                </div>
            </div>
        </div>
    </div>
</div>
<x-filament-actions::modals />
</x-filament-widgets::widget>
