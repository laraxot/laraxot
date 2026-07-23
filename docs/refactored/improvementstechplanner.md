1-All'inserimento di un nuovo appuntamento si creano dei problemi con il salvataggio dell'orario.
2-Implementazione mappa interattiva tramite API Maps o OpenStreetMap
  Possiamo usare: Google Maps Javascript API( piano gratuito) o Leaflet.js (open source,gratuito,lavora bene con OpenStreetMap)
  Se utilizzassimo Leaflet.js, è disponibile una libreria(Leaflet.markercluster) per visualizzare gruppi di clienti.
  Con Leaflet possiamo:

                        - Mostrare una mappa (usando di solito OpenStreetMap come base gratuita)
                        - Aggiungere marker (ping) per indicare punti come clienti o tecnici
                        - Mostrare popup cliccando sui marker,quindi mostrare i dati del cliente
                        - Tracciare percorsi, cerchi, aree 
                        - Aggiungere cluster se ci sono molti punti vicini così da raggruppare quelli vicini tra loro.

