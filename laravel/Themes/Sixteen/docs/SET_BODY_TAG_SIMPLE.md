Motivazione

La pagina sorgente di design-comuni usa un tag <body> senza classi. Per garantire compatibilità e ridurre differenze stilistiche indesiderate, il body del tema Sixteen è stato reso semplicemente <body>.

Cosa è stato fatto

- Rimosse le classi utility dal tag <body> in resources/views/layouts/main.blade.php
- Documentata la modifica qui

Note

Se sono necessarie classi globali (es. dark mode), implementare il supporto tramite file JS/CSS del tema (resources/js o resources/css) e non tramite attributi inline sul body.