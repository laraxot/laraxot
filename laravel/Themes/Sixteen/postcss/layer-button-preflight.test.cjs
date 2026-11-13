/**
 * Smoke: il preflight button/submit trasparente deve finire in @layer base (DCP-195).
 * Esegui: node postcss/layer-button-preflight.test.cjs
 */
const assert = require('node:assert/strict');
const postcss = require('postcss');
const plugin = require('./layer-button-preflight.cjs');

async function run() {
    const input = [
        'button,[type=submit]{-webkit-appearance:button;appearance:button;background-color:#0000;background-image:none}',
        '.bg-sky-800{background-color:#075985}',
        '@layer base{button{background-color:#0000}}',
    ].join('');

    const { css } = await postcss([plugin()]).process(input, { from: undefined });

    assert.match(css, /@layer base\{button,\[type=submit\]\{/);
    assert.ok(css.includes('.bg-sky-800{background-color:#075985}'));
    // la regola gia' in @layer base non viene avvolta di nuovo
    assert.equal((css.match(/@layer base/g) || []).length, 2);

    console.log('PASS layer-button-preflight');
}

run().catch((error) => {
    console.error(error);
    process.exit(1);
});
