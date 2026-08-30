# Signage

Preventivi di insegne a partire dal file grafico del cliente.

## Il problema che risolve

Un'insegna arriva come disegno .ai (che è un contenitore PDF). Dentro ci sono le
misure, i materiali e le quantità, scritti come testo sparso sul foglio. Preventivare
a mano vuol dire leggere il disegno, riconoscere le sigle dei materiali, cercarle a
listino e sommare. Il modulo legge il testo del disegno, lo rimette in righe
(raggruppando i frammenti per linea di base), riconosce i componenti e li prezza.

## Come si arriva al prezzo

1. **Estrazione** — dal .ai si ricavano i frammenti di testo con le loro coordinate.
   Un file malformato produce meno testo, non un errore: l'estrazione va avanti.
2. **Riconoscimento** — le sigle dei tipi insegna vengono decodificate in famiglia,
   materiale e finitura.
3. **Prezzo** — ogni componente prende il costo dal listino fornitori e il ricarico
   da `Costing\Support\MarkupResolver`: voce → centro di lavoro → listino → default.
4. **Preventivo** — la stima diventa un preventivo del modulo Quotation.

## Catalogo

Le insegne ricorrenti stanno a catalogo con la loro distinta di componenti: si
prezzano senza ripartire dal disegno ogni volta.

## Nota sulla consegna del disegno

L'anteprima passa da una rotta del modulo e non da `/storage`: i disegni dei clienti
non devono essere raggiungibili da chi non è autenticato. La rotta risponde 403 e non
redirige al login, perché ogni pannello ha la propria pagina di accesso e una rotta
di login globale non esiste.
