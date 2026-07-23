{{--
    Particles background effect - canvas-based, lightweight.
    Per pannelli auth (login, register) con sfondo gradient scuro.
    Uso: @include('pub_theme::components.ui.particles')
    Oppure: <x-pub_theme::ui.particles />
--}}
<div
    class="absolute inset-0 overflow-hidden pointer-events-none"
    aria-hidden="true"
    x-data="{
        init() {
            const canvas = this.$refs.canvas;
            const ctx = canvas.getContext('2d');
            let particles = [];
            const count = 50;
            const colors = ['rgba(255,255,255,0.15)', 'rgba(255,255,255,0.1)', 'rgba(255,255,255,0.08)'];

            function resize() {
                canvas.width = canvas.offsetWidth;
                canvas.height = canvas.offsetHeight;
                particles = [];
                for (let i = 0; i < count; i++) {
                    particles.push({
                        x: Math.random() * canvas.width,
                        y: Math.random() * canvas.height,
                        r: Math.random() * 2 + 0.5,
                        vx: (Math.random() - 0.5) * 0.3,
                        vy: (Math.random() - 0.5) * 0.3,
                        color: colors[Math.floor(Math.random() * colors.length)]
                    });
                }
            }

            function animate() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                particles.forEach(p => {
                    p.x += p.vx;
                    p.y += p.vy;
                    if (p.x < 0 || p.x > canvas.width) p.vx *= -1;
                    if (p.y < 0 || p.y > canvas.height) p.vy *= -1;
                    ctx.beginPath();
                    ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
                    ctx.fillStyle = p.color;
                    ctx.fill();
                });
                requestAnimationFrame(animate);
            }

            resize();
            window.addEventListener('resize', resize);
            animate();
        }
    }"
>
    <canvas
        x-ref="canvas"
        class="w-full h-full"
        style="width: 100%; height: 100%;"
    ></canvas>
</div>
