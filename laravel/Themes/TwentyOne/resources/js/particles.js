/**
 * Cinematic Interactive Particle Layer — Predict Market
 *
 * Philosophy: See docs/cinematic-particles-philosophy.md
 *
 * Architecture (4 layers):
 *   Layer 1  bg gradient     CSS, body/section                z-index: 0
 *   Layer 2  ambient dots    x-ui.particles Blade component   z-index: 1-10
 *   Layer 3  antigravity     .antigravity-field + JS pointer  z-index: auto
 *   Layer 4  interactive     THIS FILE — canvas               z-index: 9999
 *
 * Anti-gravity contract: particles always drift UPWARD (negative vy acceleration).
 * Markets defy gravity. Contrarian predictions rise.
 *
 * For other AI agents — extension API:
 *   window.dispatchEvent(new CustomEvent('predict:market-win'))
 *   window.dispatchEvent(new CustomEvent('predict:market-voted', { detail: { x, y } }))
 *   window.PredictCinematic.burst(x, y, count, colorPrefix)
 *   window.PredictCinematic.celebrate()
 */

const COLOR_EMERALD = 'rgba(16,185,129,';   // brand accent — bullish
const COLOR_SLATE   = 'rgba(148,163,184,';  // neutral — uncertainty
const COLOR_GOLD    = 'rgba(245,158,11,';   // celebration — reward

class CinematicCanvas {
    #canvas;
    #ctx;
    #particles = [];
    #mouse = { x: 0, y: 0 };
    #prevMouse = { x: 0, y: 0 };
    #animId = null;

    constructor() {
        this.#canvas = document.createElement('canvas');
        this.#canvas.style.cssText =
            'position:fixed;top:0;left:0;width:100%;height:100%;' +
            'z-index:9999;pointer-events:none;';
        this.#canvas.setAttribute('aria-hidden', 'true');
        document.body.appendChild(this.#canvas);
        this.#ctx = this.#canvas.getContext('2d');

        this.#resize();
        this.#bindEvents();
        this.#loop();
    }

    #resize() {
        this.#canvas.width  = window.innerWidth;
        this.#canvas.height = window.innerHeight;
    }

    #bindEvents() {
        window.addEventListener('resize', () => this.#resize(), { passive: true });

        window.addEventListener('mousemove', (e) => {
            const vx = e.clientX - this.#prevMouse.x;
            const vy = e.clientY - this.#prevMouse.y;
            this.#prevMouse.x = this.#mouse.x = e.clientX;
            this.#prevMouse.y = this.#mouse.y = e.clientY;

            const speed = Math.sqrt(vx * vx + vy * vy);
            if (speed > 10) {
                // Fast cursor movement → anti-gravity trail
                const count = Math.min(Math.floor(speed / 10), 3);
                for (let i = 0; i < count; i++) {
                    this.#spawnTrail(e.clientX, e.clientY, vx, vy);
                }
            }
        }, { passive: true });

        window.addEventListener('predict:market-win',    () => this.#celebrate());
        window.addEventListener('predict:market-voted',  (e) => {
            const detail = e.detail ?? {};
            this.burst(
                detail.x ?? window.innerWidth  / 2,
                detail.y ?? window.innerHeight / 2,
                12,
                COLOR_EMERALD,
            );
        });
    }

    #spawnTrail(x, y, cursorVx, cursorVy) {
        const angle = Math.random() * Math.PI * 2;
        const speed = Math.random() * 1.5 + 0.5;
        this.#particles.push({
            x,
            y,
            // Slight opposite momentum to cursor creates a "wake" effect
            vx: Math.cos(angle) * speed - cursorVx * 0.08,
            vy: Math.sin(angle) * speed - cursorVy * 0.08,
            radius:  Math.random() * 1.5 + 0.5,
            alpha:   0.6,
            color:   Math.random() < 0.6 ? COLOR_EMERALD : COLOR_SLATE,
            decay:   0.035 + Math.random() * 0.025,
            lift:    0.04 + Math.random() * 0.02, // anti-gravity upward force
        });
    }

    burst(x, y, count = 20, color = COLOR_EMERALD) {
        for (let i = 0; i < count; i++) {
            const angle = (Math.PI * 2 / count) * i + Math.random() * 0.4;
            const speed = Math.random() * 5 + 2;
            this.#particles.push({
                x,
                y,
                vx: Math.cos(angle) * speed,
                vy: Math.sin(angle) * speed - 4, // upward bias
                radius:  Math.random() * 3 + 1,
                alpha:   1,
                color,
                decay:   0.018 + Math.random() * 0.012,
                lift:    0.06, // stronger anti-gravity on bursts
            });
        }
    }

    #celebrate() {
        const cx = window.innerWidth  / 2;
        const cy = window.innerHeight / 2;
        this.burst(cx, cy, 30, COLOR_EMERALD);
        setTimeout(() => this.burst(cx - 120, cy + 60, 18, COLOR_GOLD),    220);
        setTimeout(() => this.burst(cx + 120, cy + 60, 18, COLOR_SLATE),   440);
        setTimeout(() => this.burst(cx, cy - 80,         22, COLOR_EMERALD), 660);
    }

    #update() {
        this.#particles = this.#particles.filter((p) => p.alpha > 0.01);
        for (const p of this.#particles) {
            p.x   += p.vx;
            p.y   += p.vy;
            p.vx  *= 0.96;
            p.vy  -= p.lift; // anti-gravity: vy decreases → particle moves upward
            p.vy  *= 0.96;
            p.alpha -= p.decay;
        }
    }

    #draw() {
        this.#ctx.clearRect(0, 0, this.#canvas.width, this.#canvas.height);
        for (const p of this.#particles) {
            this.#ctx.beginPath();
            this.#ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
            this.#ctx.fillStyle = `${p.color}${p.alpha.toFixed(2)})`;
            this.#ctx.fill();
        }
    }

    #loop() {
        this.#update();
        this.#draw();
        this.#animId = requestAnimationFrame(() => this.#loop());
    }

    destroy() {
        if (this.#animId !== null) {
            cancelAnimationFrame(this.#animId);
        }
        this.#canvas.remove();
    }
}

let instance = null;

export function initCinematicParticles() {
    if (instance !== null) {
        return;
    }

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return;
    }

    instance = new CinematicCanvas();

    // Global API — other scripts and Livewire components can use this
    window.PredictCinematic = {
        burst:     (x, y, count, color) => instance.burst(x, y, count, color),
        celebrate: ()                   => window.dispatchEvent(new CustomEvent('predict:market-win')),
    };
}
