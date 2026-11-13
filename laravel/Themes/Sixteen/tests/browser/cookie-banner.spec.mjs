/**
 * DCP-153: banner dei cookie (Modules/Gdpr/resources/views/cookie-consent), pagina /cookie-policy e link «Gestisci cookie» del footer.
 * Verifica in un browser vero, nelle 4 lingue: la barra compare al primo accesso, non blocca la pagina, la scelta e' un cookie con
 * scadenza di 180 giorni, le preferenze si riaprono dal footer, gli stili non vengono azzerati dal CSS Design Comuni, axe non trova
 * violazioni e la tabella della cookie policy elenca esattamente i cookie che il server imposta.
 * Il modo "consenso" (cookie non tecnici) non e' raggiungibile da un'istanza reale senza modificarne la configurazione: si prova sul
 * markup reso da Blade con un cookie analitico dichiarato (COOKIE_CONSENT_FIXTURE, generato da tests/browser/fixtures/render-cookie-consent-mode.php)
 * e inserito al posto del banner di una pagina VERA; senza il file la parte e' saltata.
 *
 * Run: cd laravel/Themes/Sixteen && FIXCITY_BASE_URL=http://127.0.0.1:8091 npx playwright test tests/browser/cookie-banner.spec.mjs --reporter=list
 */
import { existsSync, readFileSync } from 'node:fs';
import { test, expect } from '@playwright/test';
import AxeBuilder from '@axe-core/playwright';

const BASE = process.env.FIXCITY_BASE_URL || 'http://127.0.0.1:8000';
const FIXTURE = process.env.COOKIE_CONSENT_FIXTURE || '';
const KEY = '__cookie_consent';
const exe = [process.env.PLAYWRIGHT_CHROMIUM_EXECUTABLE_PATH, ...[1248, 1247, 1243].map((n) => `${process.env.HOME}/.cache/ms-playwright/chromium-${n}/chrome-linux64/chrome`)].find((p) => p && existsSync(p));
test.use({ launchOptions: { executablePath: exe, args: ['--no-sandbox', '--disable-dev-shm-usage'] } });

const LANGS = {
  it: { ok: 'Ho capito', policy: 'Cookie policy', manage: 'Gestisci cookie', title: 'Preferenze sui cookie' },
  en: { ok: 'Got it', policy: 'Cookie policy', manage: 'Manage cookies', title: 'Cookie preferences' },
  de: { ok: 'Verstanden', policy: 'Cookie-Richtlinie', manage: 'Cookies verwalten', title: 'Cookie-Einstellungen' },
  es: { ok: 'Entendido', policy: 'Política de cookies', manage: 'Gestionar cookies', title: 'Preferencias de cookies' },
};

for (const [lang, t] of Object.entries(LANGS)) {
  test.describe(`banner in ${lang}`, () => {
    test('compare al primo accesso, non blocca la pagina e la scelta diventa un cookie di 180 giorni', async ({ page, context }) => {
      await page.goto(`${BASE}/${lang}`, { waitUntil: 'networkidle' });
      const bar = page.locator('.gdpr-cookiebar');
      await expect(bar).toBeVisible();
      await expect(bar).toHaveAttribute('role', 'region');
      await expect(bar).toHaveAttribute('data-cookiebar', 'info');
      expect(await page.evaluate(() => document.body.firstElementChild?.classList.contains('gdpr-cookiebar'))).toBe(true);

      // non e' un modale: nessun elemento inerte e la pagina risponde ai clic
      expect(await page.evaluate(() => document.querySelectorAll('[inert]').length)).toBe(0);
      const header = page.locator('header a, nav a').first();
      await expect(header).toBeEnabled();

      const ok = bar.getByRole('button', { name: t.ok });
      await expect(ok).toBeVisible();
      const box = await ok.boundingBox();
      expect(box.height).toBeGreaterThanOrEqual(44);
      const bg = await ok.evaluate((el) => getComputedStyle(el).backgroundColor);
      expect(bg, 'il reset dei button delle pagine DC non deve azzerare lo sfondo').not.toBe('rgba(0, 0, 0, 0)');
      const pad = await page.evaluate(() => parseFloat(getComputedStyle(document.body).paddingBottom));
      expect(pad, 'la barra non deve coprire il fondo della pagina').toBeGreaterThanOrEqual(box.height);

      await ok.click();
      await expect(bar).toBeHidden();
      const cookie = (await context.cookies()).find((c) => c.name === KEY);
      expect(cookie?.value).toBe('false');
      expect(cookie?.sameSite).toBe('Lax');
      const days = (cookie.expires * 1000 - Date.now()) / 864e5;
      expect(days).toBeGreaterThan(179);
      expect(days).toBeLessThan(181);

      await page.reload({ waitUntil: 'networkidle' });
      await expect(page.locator('.gdpr-cookiebar')).toBeHidden();
    });

    test('«Gestisci cookie» nel footer riapre le preferenze, Esc le chiude, le categorie non usate sono spente', async ({ page }) => {
      await page.goto(`${BASE}/${lang}`, { waitUntil: 'networkidle' });
      await page.locator('.gdpr-cookiebar').getByRole('button', { name: t.ok }).click();
      const manage = page.locator('footer a[data-cookie-preferences]');
      await expect(manage).toHaveText(t.manage);
      await expect(manage).toHaveAttribute('href', new RegExp(`/${lang}/cookie-policy$`));
      await manage.click();
      const dialog = page.locator('#gdpr-cookie-dialog');
      await expect(dialog).toBeVisible();
      await expect(dialog.getByRole('heading', { name: t.title })).toBeVisible();
      await expect(page).toHaveURL(new RegExp(`/${lang}$`)); // il clic non e' diventato una navigazione
      await expect(dialog.locator('#gdpr-cookie-necessary')).toBeChecked();
      await expect(dialog.locator('#gdpr-cookie-necessary')).toBeDisabled();
      const necessary = await page.evaluate(() => { const c = document.querySelector('#gdpr-cookie-necessary'); const st = getComputedStyle(c); return { w: Math.round(c.getBoundingClientRect().width), border: st.borderTopWidth, tick: st.backgroundImage.includes('svg') }; });
      expect(necessary).toEqual({ w: 24, border: '2px', tick: true }); // visibile sulla pagina reale: il CSS del tema azzera `appearance`
      for (const id of ['analytics', 'marketing']) {
        await expect(dialog.locator(`#gdpr-cookie-${id}`)).toBeDisabled();
        await expect(dialog.locator(`#gdpr-cookie-${id}`)).not.toBeChecked();
      }
      await page.keyboard.press('Escape');
      await expect(dialog).toBeHidden();
    });

    test('axe non trova violazioni nella barra e nelle preferenze', async ({ page }) => {
      await page.goto(`${BASE}/${lang}`, { waitUntil: 'networkidle' });
      const bar = await new AxeBuilder({ page }).include('.gdpr-cookiebar').withTags(['wcag2a', 'wcag2aa', 'wcag21a', 'wcag21aa']).analyze();
      expect(bar.violations.map((v) => `${v.id}: ${v.help}`)).toEqual([]);
      await page.locator('footer a[data-cookie-preferences]').click(); // raggiungibile anche con la barra visibile: la pagina lascia lo spazio sotto
      const dialog = await new AxeBuilder({ page }).include('#gdpr-cookie-dialog').withTags(['wcag2a', 'wcag2aa', 'wcag21a', 'wcag21aa']).analyze();
      expect(dialog.violations.map((v) => `${v.id}: ${v.help}`)).toEqual([]);
    });
  });
}

test('la tabella della cookie policy elenca esattamente i cookie impostati dal server', async ({ page, context }) => {
  await page.goto(`${BASE}/it/cookie-policy`, { waitUntil: 'networkidle' });
  await expect(page.getByRole('heading', { level: 1, name: 'Cookie policy' })).toBeVisible();
  // `remember_web_<hash>` ("Ricordami" del login) e' elencato ma si imposta solo al login con la casella spuntata: non e' nel giro dell'ospite
  const listed = (await page.locator('[data-element="cookie-inventory"] tbody th code').allTextContents()).filter((n) => !n.startsWith('remember_')).sort();
  await page.locator('.gdpr-cookiebar').getByRole('button', { name: 'Ho capito' }).click();
  // cookie impostati dal server su una pagina qualsiasi, piu' quello scritto dal banner; il debugbar e' solo locale e non e' un cookie del sito
  await page.goto(`${BASE}/it`, { waitUntil: 'networkidle' });
  const set = (await context.cookies()).map((c) => c.name).filter((n) => n !== 'PHPDEBUGBAR_STACK_DATA').sort();
  expect(listed).toEqual(set);
  await page.locator('[data-cookie-preferences][data-element="cookie-preferences-open"]').count();
});

test('il pulsante della cookie policy apre le preferenze', async ({ page }) => {
  await page.goto(`${BASE}/it/cookie-policy`, { waitUntil: 'networkidle' });
  await page.locator('.gdpr-cookiebar').getByRole('button', { name: 'Ho capito' }).click();
  await page.locator('[data-element="cookie-preferences-open"]').click();
  await expect(page.locator('#gdpr-cookie-dialog')).toBeVisible();
});

test.describe('modo consenso (markup reso con un cookie analitico dichiarato)', () => {
  test.skip(!FIXTURE || !existsSync(FIXTURE), 'COOKIE_CONSENT_FIXTURE non impostato: vedi la story DCP-153 per generarlo');
  // Pagina VERA del sito (con il CSS del tema), con il blocco del banner sostituito da quello reso in modo consenso: il CSS del tema
  // azzerava `appearance` delle caselle e le rendeva invisibili, difetto che una pagina vuota non mostra.
  const open = async (page) => {
    await page.route(`${BASE}/it/privacy`, async (route) => {
      const response = await route.fetch();
      const html = (await response.text()).replace(/<style>\s*\.gdpr-cookiebar, \.gdpr-cookiedialog[\s\S]*?<\/script>/, () => readFileSync(FIXTURE, 'utf8'));
      await route.fulfill({ response, body: html });
    });
    await page.goto(`${BASE}/it/privacy`, { waitUntil: 'networkidle' });
  };

  test('Accetta e Rifiuta hanno lo stesso peso e scrivono i valori del pacchetto', async ({ page, context }) => {
    await open(page);
    const bar = page.locator('.gdpr-cookiebar');
    await expect(bar).toHaveAttribute('data-cookiebar', 'consent');
    const accept = bar.getByRole('button', { name: /Accetta tutti/ });
    const reject = bar.getByRole('button', { name: /Rifiuta tutti/ });
    const [a, r] = [await accept.boundingBox(), await reject.boundingBox()];
    expect(Math.abs(a.width - r.width)).toBeLessThan(40);
    expect(a.height).toBe(r.height);
    expect(await accept.evaluate((e) => getComputedStyle(e).backgroundColor)).toBe(await reject.evaluate((e) => getComputedStyle(e).backgroundColor));
    await reject.click();
    expect((await context.cookies()).find((c) => c.name === KEY)?.value).toBe('false');
  });

  test('la X chiude senza accettare, Personalizza salva solo le categorie scelte', async ({ page, context }) => {
    await open(page);
    await page.locator('.gdpr-cookiebar').getByRole('button', { name: 'Chiudi senza accettare' }).click();
    expect((await context.cookies()).find((c) => c.name === KEY)?.value).toBe('false');
    await context.clearCookies();
    await open(page);
    await page.locator('.gdpr-cookiebar').getByRole('button', { name: 'Personalizza' }).click();
    // le caselle si vedono: bordo di 2 px, 24 px di lato, spunta disegnata quando sono attive (anche quella dei cookie tecnici)
    const looks = await page.evaluate(() => [...document.querySelectorAll('#gdpr-cookie-dialog input.gdpr-check')].map((c) => { const st = getComputedStyle(c); const r = c.getBoundingClientRect(); return { id: c.id, w: Math.round(r.width), border: st.borderTopWidth, tick: c.checked ? st.backgroundImage.includes('svg') : null }; }));
    for (const c of looks) { expect(c.w, c.id).toBe(24); expect(c.border, c.id).toBe('2px'); if (c.tick !== null) expect(c.tick, `${c.id} spunta`).toBe(true); }
    expect(await page.evaluate(() => document.activeElement?.id)).toBe('gdpr-cookie-dialog-title');
    const analytics = page.locator('#gdpr-cookie-analytics');
    await expect(analytics).not.toBeChecked(); // nessuna casella preselezionata
    await analytics.check();
    await page.getByRole('button', { name: 'Salva le mie scelte' }).click();
    expect((await context.cookies()).find((c) => c.name === KEY)?.value).toBe('2');
  });

  test('Accetta tutti scrive true e avvisa chi ascolta', async ({ page, context }) => {
    await open(page);
    await page.evaluate(() => { window.__granted = null; document.addEventListener('cookie-consent:changed', (e) => { window.__granted = e.detail; }); });
    await page.locator('.gdpr-cookiebar').getByRole('button', { name: /Accetta tutti/ }).click();
    expect((await context.cookies()).find((c) => c.name === KEY)?.value).toBe('true');
    expect(await page.evaluate(() => window.__granted)).toMatchObject({ analytics: true, marketing: true });
  });
});
