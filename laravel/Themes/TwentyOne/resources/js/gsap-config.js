/**
 * GSAP Core Configuration
 * 
 * GreenSock Animation Platform - Configurazione centrale per animazioni performanti
 * 
 * @see https://greensock.com/gsap/
 * @see https://greensock.com/docs/
 * 
 * @package TwentyOne Theme
 * @author Base Predict Fila5
 * @license MIT
 */

import { gsap } from 'gsap';

// ============================================
// GSAP Global Configuration
// ============================================

/**
 * Configura le impostazioni globali di GSAP
 * 
 * - force3D: true per performance GPU accelerate
 * - nullTargetWarn: false per ridurre warning in console
 * - trialWarn: false per production
 */
gsap.config({
    force3D: true, // Usa trasformazioni 3D per performance (GPU acceleration)
    nullTargetWarn: false, // Silenzia warning per target null
    trialWarn: false, // Silenzia warning per trial license (GSAP Core è gratuito)
});

/**
 * Imposta la tolleranza per prefers-reduced-motion
 * 
 * Rispetta le preferenze di accessibilità dell'utente
 * @see https://developer.mozilla.org/en-US/docs/Web/CSS/@prefers-reduced-motion
 */
gsap.matchMedia().add('(prefers-reduced-motion: reduce)', () => {
    // Quando l'utente preferisce moto ridotto, disabilita animazioni
    return {
        init: () => {
            gsap.globalTimeline.pause();
        }
    };
});

// ============================================
// Utility Functions
// ============================================

/**
 * Verifica se GSAP è disponibile
 * 
 * @returns {boolean} true se GSAP è caricato
 */
export function isGSAPAvailable() {
    return typeof gsap !== 'undefined';
}

/**
 * Verifica se le animazioni sono abilitate
 * 
 * @returns {boolean} true se animazioni sono permesse
 */
export function areAnimationsEnabled() {
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    return !prefersReducedMotion && isGSAPAvailable();
}

/**
 * Crea un'animazione count-up per elementi numerici
 * 
 * @param {string} selector - Selettore CSS dell'elemento
 * @param {number} endValue - Valore finale
 * @param {object} options - Opzioni (duration, ease, delay, etc.)
 * @returns {gsap.core.Tween} Animazione GSAP
 */
export function createCountUpAnimation(selector, endValue, options = {}) {
    if (!areAnimationsEnabled()) {
        // Se animazioni disabilitate, imposta valore finale immediatamente
        const el = document.querySelector(selector);
        if (el) {
            el.innerText = endValue;
        }
        return null;
    }

    const {
        duration = 2,
        ease = 'power2.out',
        delay = 0,
        snap = true,
        onUpdate = null,
    } = options;

    return gsap.to({ value: 0 }, {
        value: endValue,
        duration,
        ease,
        delay,
        snap: snap ? { value: 1 } : undefined,
        onUpdate: function() {
            const el = document.querySelector(selector);
            if (el) {
                const currentValue = Math.ceil(this.targets()[0].value);
                if (onUpdate) {
                    onUpdate(currentValue);
                } else {
                    el.innerText = currentValue.toLocaleString();
                }
            }
        }
    });
}

// ============================================
// Export
// ============================================

export { gsap };
export default gsap;
