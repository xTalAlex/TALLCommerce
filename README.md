# TALLCommerce
Una soluzione di ecommerce per chi vuole portare la proprià attività italiana online.

## Funzionalità
* Ottimizzazione **SEO**.
* Layout completamente **responsive**.
* Prodotti suddivisibili e ricercabili tramite **categorie** e **sottocategorie**.
* **Watermark** applicabile alle immagini dei prodotti.
* **Ricerca istantanea** con tracciamento delle parole chiave tramite **Algolia** (strumenti di ricerca utile per UX e Marketing)
* **Ricerca vocale** e con **cronologia** delle ultime ricerche.
* **Slider** delle immagini dei prodotti.
* **Email automatizzate** di servizio per gestione account e stato degli ordini.
* **Email personalizzate** da inviare a un singolo utente.
* **Chat di supporto**.
* Prodotti organizzabili tramite **Collezioni** e **Tag**.
* **Wishlist** e **Carrello** persistenti per gli utenti registrati.
* **Varianti** degli articoli.
* **Articoli in evidenza**.
* Sistema di **recensioni**.
* Inserimento data disponibilità dei prodotti per le **prevendite**.
* **Coupon** (con sconti fissi o in percentuale, senza scadenza, con data di scadenza o con numero di riscatti limitati) applicabili sia dal carrello che durante il checkout.
* Funzionalità di autenticazione a 2 fattori (**2FA**).
* Inserimento di **indirizzi di spedizione e fatturazione** da parte dell'utente. 
* Nel caso B2C: possibilità di **acquisto come Ospite** utilizzando un email e **recuperare lo storico degli acquisti** iscrivendosi tramite quest'ultima.
* **Ricevuta non fiscale** generata per ogni ordine.
* **IVA venditore** di default assegnabile al singolo prodotto (per B2C o B2B a prezzo fisso).
* **IVA acquirente** assengabile al singolo utente (per B2B).

## Gestione da Admin
Dal pannello di controllo, oltre che ad avere la possibilità di visualizzare e interagire con alcuni **widget** che forniscono riepiloghi e informazioni flash, è possibile gestire:
* **UTENTI** con i loro Ordini, Recensioni e Indirizzi associati.
* **ORDINI** di cui si può cambiare lo Stato (es. segnare come rimborsato o in lavorazione/spedizione nel caso non ci sia un sistema automatico),
o assegnare  un codice di spedizione (sempre nel caso non ci sia un servizio di spedizione configurato).
* **PRODOTTI** di cui si possono impostare tutte le informazioni necessarie. I prodotti possono essere assegnati in massa a una Collezione.
* **COLLEZIONE** (es dolci di natale)
* **COUPON**
* **RECENSIONI** che possono essere moderate
* **BRAND**  (es mulino bianco)
* **CATEGORIE** (es biscotti)
* **ATTRIBUTI** dei prodotti (es peso)
* **TAG** (es dolci natalizi)

*E' possibile implementare se necessario anche altre entità che descrivono il prodotto: ad esempio l'entità per gli **INGREDIENTI** da assegnare a ciascun prodotto anzichè semplicemente elencarli nella descrizione, in modo da ottimizzare anche la ricerca per ingrediente.*

## Pagamenti e Spedizioni

### Pagamenti
Avvengono tramite **Stripe** che prevede metodi di pagamento multipli ( carte e wallet (es Google Pay, Apple Pay) compatibili con il paese
dell'acquirente ) tranne PayPal.
L'integrazione con **PayPal** si può valutare dopo aver discusso dei pro e contro.
Si possono includere i pagamenti tramite **contrassegno** e **bonifico**.

### Spedizioni
Sono sempre possibili le opzioni di spedizioni a **prezzo fisso**, **gratuite** e il **ritiro in loco**.
E' possibile integrare altri metodi di spedizione a **prezzo variabile** se il metodo o servizio scelto dal venditore è integrabile via API o calcolabile automaticamente.

## Multilingua
L'interfaccia è predisposta per il multilingua e di default è tradotta in **italiano e inglese**, ma è possibile aggiungere altre lingue su richiesta.

*Nota: le informazioni inserite dal pannello di amministrazione (es descrizione prodotti) non fanno parte dell'interfaccia. Nel caso di un ecommerce multilingua, bisogna inserire le traduzioni di questi elementi per ciascuna lingua che si vuole supportare. Oppure si può valutare l'integrazione di servizi terzi a pagamento di traduzione automatica.*

## Cookie, Privacy, Termini e Condizioni
Su cookie, privacy policy, termini e condizioni e politiche sui resi **la responsabilità è del venditore**, che deve deciderne il contenuto (idealmente con l'aiuto di un legale).
Nella pratica, almeno per quanto riguarda cookie e privacy policy, propongo l'ultilizzo di strumenti come Cookiebot o Ibuenda come punto di partenza.

## Servizi Collegati
Per sviluppare e mettere in produzione il sito web, ho bisogno dei seguenti servizi, che posso gestire per conto o insieme al cliente.
* Dominio: posso guidare all'acquisto.
* Gmail: da usare come email di servizio (posso utilizzarne uno esistente o creare un account nuovo).
* [DigitalOcean](https://www.digitalocean.com/): il prezzo dell'hosting si aggira sui 20€ al mese.
* [Stripe](https://stripe.com/it): per gestire i pagamenti.
* [Algolia](https://www.algolia.com/pricing/): gratis fino a 10.000 ricerche al mese, poi 1$ ogni 1.000 richieste.
* [Google Search Console](https://search.google.com/): per il posizionamento su google.
* [Google Analytics](https://analytics.google.com/): per le statistiche 
* [GitHub](https://github.com/): per gestire il codice sorgente.

*Fornisco l'assistenza necessaria per imparare le basi di utilizzo di ciascuno di questi servizi, in particolare per Stripe che è l'unico servizio che il cliente deve obbligatoriamente imparare a gestire autonomamente.*

## Funzionalità minori

* Immagine di default dei prodotti
* Gestione stock prodotti
* Validazione carrello (check quantità e prezzi correnti)
* Annullamento periodico ordini non pagati
* Cronologia cambio di stato degli ordini
* Slug personalizzabili
* Ricerca globale da admin panel
* URL sincronizzato con filtri di ricerca prodotti
* Login con google
* Sitemap

<!--
filament e media library > medialibrary header CacheControl no-cache
prodotti con varianti > nella ricerca vengono mostrati prezzo, quantità e foto della variante principale
non aggiungere piú valori dello stesso attributo a un singolo prodotto
non aggiungere sottovarianti alle varianti

prima applicare price*quantity e poi calcolare tax

quantitá di prodotti e utilizzi coupon vengono validati tra cart/checkout ma non da checkout/paid
-->