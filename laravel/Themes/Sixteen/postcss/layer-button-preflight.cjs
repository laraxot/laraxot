/**
 * DCP-195: il preflight button/submit con `background-color:#0000` esce senza @layer
 * (direttive Tailwind v3 su toolchain v4) e, a parita' di specificita', batte le utility
 * `bg-*` compilate dopo. Portare quella regola in `@layer base` fa vincere le utility
 * (layered o unlayered) senza override locali per pulsante.
 *
 * Deve girare PRIMA di isolate-legacy-important: cosi' il guard DC viene applicato
 * anche al selettore gia' dentro @layer base.
 *
 * @see docs/bmad/stories/dcp-195-pulsante-cerca-invisibile-e-reset-button-senza-layer.story.md
 */
const postcss = require('postcss');

const isTransparentBg = (value) => {
    const v = String(value).trim().toLowerCase();

    return v === '#0000'
        || v === '#00000000'
        || v === 'transparent'
        || v === 'rgba(0,0,0,0)'
        || v === 'rgb(0 0 0 / 0)'
        || v === 'rgb(0,0,0,0)';
};

const isButtonishSelector = (selector) => {
    const s = selector.toLowerCase();

    return s.includes('button')
        || s.includes('[type=submit]')
        || s.includes('[type="submit"]')
        || s.includes('[type=button]')
        || s.includes('[type="button"]')
        || s.includes('[type=reset]')
        || s.includes('[type="reset"]');
};

const isUnlayeredButtonPreflight = (rule) => {
    if (rule.parent?.type === 'atrule' && rule.parent.name === 'layer') {
        return false;
    }

    if (rule.parent?.type === 'atrule' && /keyframes$/i.test(rule.parent.name)) {
        return false;
    }

    if (!rule.selectors?.some(isButtonishSelector)) {
        return false;
    }

    let hasAppearanceButton = false;
    let hasTransparentBackground = false;

    rule.walkDecls((decl) => {
        if (decl.prop === 'background-color' && isTransparentBg(decl.value)) {
            hasTransparentBackground = true;
        }
        if ((decl.prop === 'appearance' || decl.prop === '-webkit-appearance' || decl.prop === '-moz-appearance')
            && String(decl.value).includes('button')) {
            hasAppearanceButton = true;
        }
    });

    return hasAppearanceButton && hasTransparentBackground;
};

module.exports = () => ({
    postcssPlugin: 'layer-button-preflight',
    Once(root) {
        const toWrap = [];

        root.walkRules((rule) => {
            if (isUnlayeredButtonPreflight(rule)) {
                toWrap.push(rule);
            }
        });

        for (const rule of toWrap) {
            const layer = postcss.atRule({ name: 'layer', params: 'base' });
            rule.replaceWith(layer);
            layer.append(rule);
        }
    },
});

module.exports.postcss = true;
