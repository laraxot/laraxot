@props([
    'targetDate' => null,
    'class' => '',
    'showLabels' => true
])

@php
$targetDate = $targetDate ?? now()->addDays(30);
$countdownClasses = implode(' ', [
    'grid',
    'grid-cols-2',
    'md:grid-cols-4',
    'gap-4',
    'md:gap-6',
    'my-12',
    $class
]);
@endphp

<div x-data="countdownTimer('{{ $targetDate->toISOString() }}')" 
     x-init="startCountdown()"
     class="{{ $countdownClasses }}">
    
    <template x-for="unit in units" :key="unit.key">
        <div class="bg-gradient-countdown text-white p-6 rounded-2xl min-w-24 shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-xl">
            <span class="block text-3xl md:text-4xl font-bold tabular-nums" 
                  x-text="timer[unit.key].toString().padStart(2, '0')"></span>
            <span class="block text-xs md:text-sm uppercase opacity-90 mt-2 font-medium" 
                  x-text="unit.label" x-show="showLabels"></span>
        </div>
    </template>
</div>

<script>
function countdownTimer(targetDate) {
    return {
        timer: { days: 0, hours: 0, minutes: 0, seconds: 0 },
        targetDate: new Date(targetDate),
        interval: null,
        units: [
            { key: 'days', label: "{{ __('Days') }}" },
            { key: 'hours', label: "{{ __('Hours') }}" },
            { key: 'minutes', label: "{{ __('Minutes') }}" },
            { key: 'seconds', label: "{{ __('Seconds') }}" }
        ],
        
        startCountdown() {
            this.interval = setInterval(() => {
                const now = new Date().getTime();
                const distance = this.targetDate.getTime() - now;
                
                if (distance < 0) {
                    clearInterval(this.interval);
                    this.timer = { days: 0, hours: 0, minutes: 0, seconds: 0 };
                    this.$dispatch('countdown-complete');
                    return;
                }
                
                this.timer.days = Math.floor(distance / (1000 * 60 * 60 * 24));
                this.timer.hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                this.timer.minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                this.timer.seconds = Math.floor((distance % (1000 * 60)) / 1000);
            }, 1000);
        },
        
        destroy() {
            if (this.interval) {
                clearInterval(this.interval);
            }
        }
    }
}
</script>