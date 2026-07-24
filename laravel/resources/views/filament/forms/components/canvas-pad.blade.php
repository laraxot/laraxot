@php
    /** @var \App\Filament\Forms\Components\CanvasPad $field */
@endphp

<div
    x-data="{
        heightPx: @js($field->getHeight()),
        penColor: '#111827',
        penWidth: 2,
        isDrawing: false,
        lastX: 0,
        lastY: 0,
        ratio: 1,
        init() {
            this.$nextTick(() => this.setupCanvas())
        },
        setupCanvas() {
            const canvas = this.$refs.canvas
            const ctx = canvas.getContext('2d')

            // Size canvas to container width while keeping proper resolution.
            const rect = canvas.getBoundingClientRect()
            this.ratio = (window.devicePixelRatio || 1)
            canvas.width = Math.floor(rect.width * this.ratio)
            canvas.height = Math.floor(rect.height * this.ratio)
            ctx.scale(this.ratio, this.ratio)

            ctx.lineJoin = 'round'
            ctx.lineCap = 'round'
            ctx.lineWidth = this.penWidth
            ctx.strokeStyle = this.penColor // default gray-900

            // If state already contains an image, redraw it.
            const existing = @js($getState());
            if (existing) {
                const img = new Image()
                img.onload = () => {
                    ctx.clearRect(0, 0, rect.width, rect.height)
                    ctx.drawImage(img, 0, 0, rect.width, rect.height)
                }
                img.src = existing
            }
        },
        applyPen() {
            const canvas = this.$refs.canvas
            const ctx = canvas.getContext('2d')
            ctx.lineWidth = this.penWidth
            ctx.strokeStyle = this.penColor
        },
        getPoint(event) {
            const canvas = this.$refs.canvas
            const rect = canvas.getBoundingClientRect()
            const isTouch = event.touches && event.touches.length
            const clientX = isTouch ? event.touches[0].clientX : event.clientX
            const clientY = isTouch ? event.touches[0].clientY : event.clientY
            return {
                x: clientX - rect.left,
                y: clientY - rect.top,
            }
        },
        start(event) {
            event.preventDefault()
            this.applyPen()
            const p = this.getPoint(event)
            this.isDrawing = true
            this.lastX = p.x
            this.lastY = p.y
        },
        move(event) {
            if (! this.isDrawing) return
            event.preventDefault()

            const canvas = this.$refs.canvas
            const ctx = canvas.getContext('2d')
            const p = this.getPoint(event)

            ctx.beginPath()
            ctx.moveTo(this.lastX, this.lastY)
            ctx.lineTo(p.x, p.y)
            ctx.stroke()

            this.lastX = p.x
            this.lastY = p.y
        },
        end() {
            if (! this.isDrawing) return
            this.isDrawing = false

            const canvas = this.$refs.canvas
            const dataUrl = canvas.toDataURL('image/png')
            this.$wire.set(@js($getStatePath()), dataUrl)
        },
        clear() {
            const canvas = this.$refs.canvas
            const ctx = canvas.getContext('2d')
            const rect = canvas.getBoundingClientRect()
            ctx.clearRect(0, 0, rect.width, rect.height)
            this.$wire.set(@js($getStatePath()), null)
        },
    }"
    class="space-y-2"
>
    <div class="flex items-center justify-between">
        <div class="text-sm font-medium text-gray-700 dark:text-gray-200">
            {{ $getLabel() }}
            @if ($isRequired())
                <span class="text-danger-600">*</span>
            @endif
        </div>

        <div class="flex items-center gap-3">
            <label class="text-xs text-gray-600 dark:text-gray-300">
                <span class="sr-only">Colore</span>
                <input type="color" x-model="penColor" x-on:change="applyPen()" class="h-8 w-10 rounded border border-gray-300 dark:border-gray-700" />
            </label>

            <label class="text-xs text-gray-600 dark:text-gray-300">
                <span class="sr-only">Spessore</span>
                <select x-model.number="penWidth" x-on:change="applyPen()" class="h-8 rounded border border-gray-300 bg-white px-2 text-xs dark:border-gray-700 dark:bg-gray-900">
                    <option :value="1">1</option>
                    <option :value="2">2</option>
                    <option :value="3">3</option>
                    <option :value="4">4</option>
                    <option :value="6">6</option>
                </select>
            </label>

            <button
                type="button"
                class="text-sm text-gray-600 underline hover:text-gray-900 dark:text-gray-300 dark:hover:text-white"
                x-on:click="clear()"
            >
                Cancella
            </button>
        </div>
    </div>

    <div
        class="w-full rounded-lg border border-gray-300 bg-white dark:border-gray-700 dark:bg-gray-900"
    >
        <canvas
            x-ref="canvas"
            x-bind:style="'width: 100%; height: ' + heightPx + 'px; touch-action: none;'"
            class="block"
            x-on:mousedown="start($event)"
            x-on:mousemove="move($event)"
            x-on:mouseup="end()"
            x-on:mouseleave="end()"
            x-on:touchstart="start($event)"
            x-on:touchmove="move($event)"
            x-on:touchend="end()"
            x-on:touchcancel="end()"
        ></canvas>
    </div>

    @error($getStatePath())
        <div class="text-sm text-danger-600">{{ $message }}</div>
    @enderror
</div>

