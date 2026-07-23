{{-- Cookiebar Component (Bootstrap Italia friendly)
Usage:
<x-pub_theme::components.feedback.cookiebar />
--}}
<div x-data="cookiebar()" x-init="init()" x-show="visible" class="cookiebar" role="region" aria-label="Cookie policy" style="display:none">
  <div class="container mx-auto">
    <div class="flex flex-col md:flex-row md:items-center gap-4">
      <div class="flex-1">
        <p class="m-0 text-sm">
          Questo sito utilizza cookie tecnici e, previo consenso, cookie di profilazione o altri tracciamenti per finalità come specificato nella <a href="/privacy" class="underline">cookie policy</a>.
        </p>
      </div>
      <div class="btn-group">
        <button type="button" class="btn btn-sm btn-outline-primary" x-on:click="reject()">Rifiuta</button>
        <button type="button" class="btn btn-sm btn-primary" x-on:click="accept()">Accetta</button>
      </div>
      <button type="button" class="btn-close" aria-label="Chiudi" x-on:click="close()"></button>
    </div>
  </div>
</div>

<script>
function cookiebar(){
  return {
    key: 'cookiebar_consent',
    visible: false,
    init(){
      try{
        const v = localStorage.getItem(this.key);
        this.visible = v !== 'accepted' && v !== 'rejected';
      }catch(e){ this.visible = true; }
    },
    accept(){ this.store('accepted'); },
    reject(){ this.store('rejected'); },
    close(){ this.visible = false; },
    store(v){ try{ localStorage.setItem(this.key, v); }catch(e){} this.visible = false; }
  }
}
</script>
