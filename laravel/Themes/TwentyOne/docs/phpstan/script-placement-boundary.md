# Script Placement Boundary

## Regola architetturale

La cartella del tema contiene solo runtime del tema: viste, asset, componenti e documentazione tecnica del tema.

Gli script standalone di supporto operativo non appartengono al tema e non appartengono ai moduli applicativi: vanno collocati sotto `bashscripts/`.

## Conseguenza pratica

Se una doc del tema cita uno script operativo, deve puntare al path sotto `bashscripts/`, non normalizzare la presenza dello script dentro `Themes/*` o `Modules/*`.
