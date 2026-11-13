---
title: "Glossario e scelte di traduzione it, en, de, es"
type: reference
theme: Sixteen
story: dcp-120
created: 2026-10-13
updated: 2026-10-13
qmd: "glossario traduzione de es segnalazione Meldung incidencia comune Gemeinde Ayuntamiento pratica Vorgang tramite registro tu Sie"
status: draft
tags: [docs, sixteen, i18n]
---

# Glossario e scelte di traduzione

Vale per i contenuti di header, footer, modale di ricerca e home (JSON del tenant e del modulo Fixcity) e per i file lang nuovi. Il punto di partenza sono i termini gia' presenti nei file `Themes/Sixteen/lang/{de,es}/*.php`, per non avere due parole diverse per la stessa cosa nello stesso sito. Il test `Modules/Fixcity/tests/Unit/ContentLocaleParityTest.php` fa rispettare la copertura, non la qualita': le scelte qui sotto vanno riviste da un madrelingua prima di un rilascio.

## Registro

| Lingua | Registro | Perche' |
|---|---|---|
| it | tu informale gia' usato nei testi (`Racconta`, `Segui`) | e' la fonte |
| en | neutro | e' la fonte esistente |
| de | Sie (forma di cortesia) | gia' usato in `lang/de/home.php` ("Melden Sie ein Problem"); atteso nella PA di lingua tedesca |
| es | tu (tuteo) | gia' usato in `lang/es/home.php` ("Envía", "Sigue", "Consulta") |

## Termini principali

| Italiano | Deutsch | Español | Note |
|---|---|---|---|
| Comune (ente) | Gemeinde | Ayuntamiento | per il territorio, es usa anche "municipio" ("Vivir el municipio", come in `lang/es/navigation.php`) |
| segnalazione | Meldung | incidencia | come in `lang/de/home.php` e `lang/es/home.php`; il verbo "inviare una segnalazione" diventa "Meldung senden" e "Enviar una incidencia" |
| disservizio | Störung | incidencia (di servizio) | "Segnalazione disservizio" = "Störungsmeldung" / "Comunicar una incidencia" |
| pratica | Vorgang | trámite | come in `lang/*/home.php` ("Meine Vorgänge", "Mis trámites") |
| area personale | persönlicher Bereich | área personal | |
| Amministrazione trasparente | Transparente Verwaltung | Administración transparente | sezione prevista per legge in Italia; la traduzione e' descrittiva, il nome ufficiale resta quello italiano |
| Novità | Neuigkeiten | Novedades | titolo di gruppo; le singole "Notizie" sono Nachrichten / Noticias |
| Comunicati | Pressemitteilungen | Comunicados | |
| Avvisi | Bekanntmachungen | Avisos | |
| Anagrafe e stato civile | Einwohnermeldeamt und Standesamt | Padrón y registro civil | |
| Ufficio Relazioni con il Pubblico | Bürgerbüro | Oficina de Relaciones con el Público | in de si usa il termine funzionale; l'acronimo URP non si traduce |
| Posta Elettronica Certificata | Zertifizierte E-Mail (PEC) | Correo electrónico certificado (PEC) | l'acronimo PEC resta: e' l'istituto italiano |
| Dichiarazione di accessibilità | Erklärung zur Barrierefreiheit | Declaración de accesibilidad | |
| Informativa privacy | Datenschutzerklärung | Política de privacidad | |
| Politici | Politikerinnen und Politiker | Cargos electos | forma inclusiva in de, termine d'uso in es |
| Polizia locale | Ortspolizei | Policía local | |
| Media policy | Medienrichtlinie | Política de medios | in en resta "Media policy" (gia' inglese) |

## Cosa non si traduce

- Marchi e nomi propri: `Twitter`, `Facebook`, `YouTube`, `Telegram`, `Whatsapp`, `WhatsApp`, `RSS`, `FixCity`, `NextGenerationEU`.
- Codici lingua del selettore: `ITA`, `ENG`, `DEU`, `SPA` (ISO 639-2/3, tre lettere, come gia' `ITA` ed `ENG`).
- Dati propri del Comune: numeri di telefono, partita IVA, codice fiscale, indirizzo postale (`Via Roma 123 - 00100 Comune`), nome segnaposto (`Nome Comune`). Si traducono solo le etichette ("Steuernummer / USt-IdNr.", "Código fiscal / IVA").
- Omografi veri: `Turismo` e' uguale in italiano e in spagnolo; `Media policy` e' gia' inglese in en.
- Chiavi di traduzione dentro il JSON (`pub_theme::home.list.map_aria`): si risolvono dai file lang.
- URL: lo slug e' uno solo per pagina (vedi `00-comportamento-multilingua.md`).

Le eccezioni sono nell'elenco `$contentParityIdenticalAllowed` del test; aggiungerne una richiede di motivarla qui.

## Segnaposto del template

I testi `Nome del Comune`, `Nome della Regione`, `Il mio Comune`, `Un comune da vivere` sono segnaposto del riferimento Design Comuni, non contenuti del cliente. Sono tradotti come segnaposto ("Name der Gemeinde", "Nombre del Ayuntamiento"); il cliente li sostituira' per ogni lingua nel proprio tenant. Slogan ed ente di `header.php` riusano i valori gia' presenti nei lang ("Meine Gemeinde", "Mi Ayuntamiento", "Eine Gemeinde zum Leben", "Una ciudad para vivir").

## Aggiungere una lingua

1. Aggiungerla a `laravel/config/laravellocalization.php`.
2. Il test `ContentLocaleParityTest` diventa rosso finche' header, footer, modale di ricerca e home non hanno il blocco nella nuova lingua e la voce nel selettore.
3. Copiare i file `lang/<lingua>/` dal tema e dai moduli; l'audit `audit-locales.py` trova le chiavi rimaste in italiano.
4. Decidere registro e termini e registrarli qui.
