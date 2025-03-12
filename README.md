# 📅 Sistema di Prenotazione Appuntamenti con Laravel + FullCalendar

Benvenuto nel sistema di gestione **prenotazioni appuntamenti** realizzato con **Laravel 12**, **FullCalendarJS** e **Axios**. Questa applicazione permette la gestione delle disponibilità orarie e la prenotazione di appuntamenti da parte di clienti, oltre a garantire all’amministratore il pieno controllo tramite un’interfaccia semplice e intuitiva.

---

## 🚀 Funzionalità principali

### ✅ Autenticazione e Ruoli
- Autenticazione utenti tramite **Laravel Breeze** (Fortify incluso).
- Ruoli definiti:
  - **Admin**: Gestione completa di appuntamenti, slot, orari di apertura, giorni festivi.
  - **Cliente**: Visualizza slot disponibili e prenota appuntamenti.

### ✅ Prenotazione e Gestione Appuntamenti
- I **clienti** vedono solo gli **slot disponibili** generati dinamicamente.
- I **clienti** possono prenotare direttamente cliccando sugli slot liberi.
- Gli **admin** possono:
  - Creare appuntamenti manualmente.
  - Modificare/spostare appuntamenti tramite **drag & drop** o **resize**.
  - Eliminare appuntamenti.

### ✅ Gestione Orari di Apertura e Giorni Festivi
- Gli **admin** gestiscono orari di apertura tramite CRUD `OpeningHours`.
- Esclusione automatica dei giorni festivi tramite CRUD `Holidays`.

### ✅ Generazione automatica Slot Disponibili
- Gli slot si basano su:
  - Orari di apertura (`OpeningHours`).
  - Esclusione dei festivi (`Holidays`).
  - Appuntamenti già prenotati.
- Gli slot si aggiornano **in tempo reale** al momento di prenotazione.

### ✅ Interfaccia utente
- Interfaccia responsive e dinamica con **FullCalendarJS**.
- Differenziazione grafica tra:
  - Slot **disponibili** (verdi)
  - Appuntamenti **prenotati** (colore personalizzabile)
- Modalità **Admin** e **Cliente** gestite nella stessa vista `calendar.blade.php`.

### ✅ Sicurezza
- Autenticazione e protezione delle rotte tramite **Laravel Sanctum**.
- Validazione dei dati server-side.
- CSRF protection abilitata.

---

## 📦 Struttura delle tabelle principali

| Tabella        | Descrizione                                       |
|----------------|---------------------------------------------------|
| `users`        | Utenti registrati con ruoli `admin` o `cliente`.  |
| `categories`   | (Opzionale) Tipologie di appuntamenti definite da admin. |
| `appointments` | Appuntamenti prenotati da utenti (collegati a `users`). |
| `opening_hours`| Orari di apertura ricorrenti (giorno/ora).       |
| `holidays`     | Giorni festivi in cui non si accettano prenotazioni. |

---

## ⚙️ Come installare il progetto

### 1. Clona la repo
```bash
git clone https://github.com/tuo-user/nome-repo.git
cd nome-repo
```

### 2. Installa le dipendenze backend
```bash
composer install
```

### 3. Installa le dipendenze frontend
```bash
npm install && npm run dev
```

### 4. Configura il file `.env`
```bash
cp .env.example .env
php artisan key:generate
```
Modifica la connessione al database:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nome_database
DB_USERNAME=utente
DB_PASSWORD=password
```

### 5. Migrazioni + Seeders
```bash
php artisan migrate --seed
```

---

## 👤 Ruoli e Accesso

- **Admin**
  - Può creare/modificare/eliminare appuntamenti.
  - Può gestire orari di apertura (`OpeningHours`).
  - Può gestire festività (`Holidays`).
  - Può vedere **tutti** gli appuntamenti di tutti i clienti.

- **Cliente**
  - Può vedere **solo** slot liberi.
  - Può prenotare **solo** slot disponibili.
  - Non vede descrizioni degli appuntamenti altrui.

---

## 📅 Funzionalità future (To-Do)

- [ ] Integrazione **Google Calendar** (per sincronizzazione appuntamenti admin).
- [ ] Invio **email di reminder** automatiche prima dell’appuntamento.
- [ ] Statistiche e report per admin (appuntamenti, fasce orarie più prenotate, ecc.).
- [ ] Migliorie UX: modal personalizzati, notifiche toast, conferme animate.
- [ ] Supporto multilingua.

---

## 🛠️ Tecnologie usate

| Backend       | Frontend           |
|---------------|--------------------|
| Laravel 12    | FullCalendarJS 6.x |
| Breeze + Fortify | Axios (per chiamate API) |
| MySQL / MariaDB | Tailwind CSS (da Breeze) |
| Sanctum (API auth) | |

---

## ✨ Autore
**Marco De Vito**  
LinkedIn: [linkedin.com/in/marcodevitodevolperbackend](https://linkedin.com/in/marcodevitodevolperbackend)


