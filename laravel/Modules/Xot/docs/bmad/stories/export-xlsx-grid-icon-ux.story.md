# Story: export-xlsx-grid-icon-ux
**Status**: ready-for-dev
**Modulo**: Xot
**Epic**: 5.222-export-xlsx-grid-icon-ux

## AC
- [ ] Sostituire `heroicon-o-document-arrow-down` in ExportXlsxAction con un SVG custom
- [ ] Creare `Modules/Xot/resources/svg/files/xlsx.svg` con icona document-grid (tabella excel)
- [ ] Verificare che l'icona sia registrata da `XotBaseServiceProvider::registerBladeIcons()`
- [ ] PHPStan Xot [OK] No errors

## Note
- Pattern esistente: `xot-files.xls`, `xot-files.pdf` in resources/svg/files/
- Non usare heroicon generico: preferire SVG dedicato per identità visiva inequivocabile