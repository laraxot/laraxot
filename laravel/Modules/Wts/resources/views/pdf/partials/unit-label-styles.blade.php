<style>
    @page { margin: 0; }
    * { box-sizing: border-box; }
    body {
        font-family: DejaVu Sans, sans-serif;
        color: #000;
        margin: 0;
    }

    /* Etichetta compatta orizzontale (es. 140x50 mm su Honeywell PM42). */
    .label {
        width: 100%;
        padding: 4px 6px;
        border: 1px solid #000;
    }

    .label-grid { width: 100%; }
    .label-grid td { vertical-align: top; }

    .qr-cell { width: 150px; text-align: left; padding-right: 8px; }
    .qr { width: 145px; height: 145px; }

    .info { width: 100%; }

    .row-top { width: 100%; }
    .sheet-code {
        font-size: 15px;
        font-weight: 700;
        letter-spacing: 0.5px;
    }
    .cer {
        font-size: 20px;
        font-weight: 700;
        text-align: right;
        white-space: nowrap;
    }

    .producer {
        font-size: 11px;
        font-weight: 700;
        margin: 2px 0 0;
    }
    .address {
        font-size: 9px;
        margin: 0;
    }
    .description {
        font-size: 9px;
        line-height: 1.2;
        margin: 2px 0 0;
    }

    .fields {
        width: 100%;
        font-size: 10px;
        margin-top: 3px;
        border-top: 1px solid #000;
        padding-top: 2px;
    }
    .fields td { padding: 0 6px 0 0; }
    .fields .k { color: #333; }
    .fields .v { font-weight: 700; }

    .footer {
        font-size: 8px;
        color: #333;
        margin-top: 2px;
    }
</style>
