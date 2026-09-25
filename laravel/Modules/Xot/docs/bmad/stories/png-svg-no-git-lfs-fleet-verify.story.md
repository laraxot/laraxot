# Story: png-svg-no-git-lfs-fleet-verify
**Status**: ready-for-dev
**Modulo**: Xot (coordinatore)

## AC
- [ ] Verificare `.gitattributes` in tutti i 18 moduli + 3 temi: nessuna riga attiva `filter=lfs` (solo commenti/placeholder)
- [ ] Controllare `git lfs ls-files` su tutti i 18 moduli: 0 file LFS
- [ ] Rimuovere hook LFS residui (`filter.lfs.*`) da `.git/config` locali (se presenti)
- [ ] Propagare prototipo `.gitattributes` su moduli divergenti (solo se versioni migliori non presenti)
- [ ] Verificare `check-no-git-lfs.sh` exit 0

## Note
- Non sovrascrivere `.gitattributes` pari o migliori (es. Pdnd con varianti corrette)
- Non toccare moduli in scrittura concorrente (lock git-status-fleet attivi)
- Non usare `git reset --hard`, solo commit nuovi
