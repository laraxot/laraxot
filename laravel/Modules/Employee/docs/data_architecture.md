# Architettura Dati - Modulo Employee

## Modello di Dominio

### Entità Principali

#### 1. Employee (Dipendente)
```php
// Entità centrale del sistema
- ID univoco
- Codice dipendente (employee_code)
- Dati anagrafici (nome, cognome, email, telefono)
- Dati contrattuali (data assunzione, tipo contratto, livello)
- Relazione con User (autenticazione)
- Dipartimento e posizione aziendale
- Status (attivo, sospeso, cessato)
```

#### 2. TimeEntry (Timbratura)
```php
// Registrazione presenze
- Timestamp entrata/uscita
- Geolocalizzazione (lat/lng)
- Dispositivo utilizzato
- Note e giustificativi
- Status approvazione
```

#### 3. LeaveRequest (Richiesta Ferie/Permessi)
```php
// Gestione assenze programmate
- Tipologia (ferie, permesso, malattia)
- Periodo richiesto
- Giorni/ore richieste
- Motivazione
- Workflow approvazione
```

#### 4. Shift (Turno)
```php
// Pianificazione turni lavoro
- Nome/descrizione turno
- Orari inizio/fine
- Pause previste
- Giorni settimana
- Dipendenti assegnati
```

#### 5. ExpenseReport (Nota Spese)
```php
// Gestione rimborsi
- Titolo e descrizione
- Voci di spesa dettagliate
- Allegati (scontrini/ricevute)
- Calcolo rimborso chilometrico
- Workflow approvazione
```

## Schema Database Dettagliato

### Tabelle Core

#### employees
```sql
CREATE TABLE employees (
    id BIGINT UNSIGNED PRIMARY KEY,
    user_id BIGINT UNSIGNED UNIQUE,
    employee_code VARCHAR(50) UNIQUE NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE,
    phone VARCHAR(20),
    hire_date DATE,
    termination_date DATE NULL,
    department_id BIGINT UNSIGNED,
    position_id BIGINT UNSIGNED,
    contract_type ENUM('full_time', 'part_time', 'contractor', 'intern'),
    employment_status ENUM('active', 'suspended', 'terminated'),
    salary_info JSON, -- Dati sensibili crittografati
    emergency_contact JSON,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (department_id) REFERENCES departments(id),
    FOREIGN KEY (position_id) REFERENCES positions(id),
    
    INDEX idx_employee_code (employee_code),
    INDEX idx_status (employment_status),
    INDEX idx_department (department_id)
);
```

#### time_entries
```sql
CREATE TABLE time_entries (
    id BIGINT UNSIGNED PRIMARY KEY,
    employee_id BIGINT UNSIGNED NOT NULL,
    clock_in TIMESTAMP NOT NULL,
    clock_out TIMESTAMP NULL,
    break_start TIMESTAMP NULL,
    break_end TIMESTAMP NULL,
    total_hours DECIMAL(4,2) CALCULATED,
    location_in JSON, -- {lat, lng, address}
    location_out JSON,
    device_info JSON, -- {type, ip, user_agent}
    notes TEXT,
    status ENUM('pending', 'approved', 'rejected', 'auto_approved'),
    approved_by BIGINT UNSIGNED NULL,
    approved_at TIMESTAMP NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (employee_id) REFERENCES employees(id),
    FOREIGN KEY (approved_by) REFERENCES employees(id),
    
    INDEX idx_employee_date (employee_id, clock_in),
    INDEX idx_status (status),
    INDEX idx_approval (approved_by, approved_at)
);
```

#### leave_requests
```sql
CREATE TABLE leave_requests (
    id BIGINT UNSIGNED PRIMARY KEY,
    employee_id BIGINT UNSIGNED NOT NULL,
    leave_type ENUM('vacation', 'sick', 'personal', 'maternity', 'paternity', 'unpaid'),
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    start_time TIME NULL, -- Per permessi orari
    end_time TIME NULL,
    total_days DECIMAL(3,1) NOT NULL,
    total_hours DECIMAL(4,1) NULL,
    reason TEXT,
    status ENUM('pending', 'approved', 'rejected', 'cancelled'),
    requested_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    approved_by BIGINT UNSIGNED NULL,
    approved_at TIMESTAMP NULL,
    rejection_reason TEXT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (employee_id) REFERENCES employees(id),
    FOREIGN KEY (approved_by) REFERENCES employees(id),
    
    INDEX idx_employee_period (employee_id, start_date, end_date),
    INDEX idx_status (status),
    INDEX idx_type (leave_type)
);
```

#### leave_balances
```sql
CREATE TABLE leave_balances (
    id BIGINT UNSIGNED PRIMARY KEY,
    employee_id BIGINT UNSIGNED NOT NULL,
    year YEAR NOT NULL,
    vacation_days_total DECIMAL(4,1) DEFAULT 0,
    vacation_days_used DECIMAL(4,1) DEFAULT 0,
    vacation_days_pending DECIMAL(4,1) DEFAULT 0,
    sick_days_total DECIMAL(4,1) DEFAULT 0,
    sick_days_used DECIMAL(4,1) DEFAULT 0,
    personal_days_total DECIMAL(4,1) DEFAULT 0,
    personal_days_used DECIMAL(4,1) DEFAULT 0,
    comp_time_earned DECIMAL(4,1) DEFAULT 0,
    comp_time_used DECIMAL(4,1) DEFAULT 0,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (employee_id) REFERENCES employees(id),
    UNIQUE KEY unique_employee_year (employee_id, year),
    
    INDEX idx_year (year)
);
```

#### shifts
```sql
CREATE TABLE shifts (
    id BIGINT UNSIGNED PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    break_duration INTEGER DEFAULT 0, -- minuti
    days_of_week JSON, -- [1,2,3,4,5] per lun-ven
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    INDEX idx_active (is_active),
    INDEX idx_times (start_time, end_time)
);
```

#### shift_assignments
```sql
CREATE TABLE shift_assignments (
    id BIGINT UNSIGNED PRIMARY KEY,
    employee_id BIGINT UNSIGNED NOT NULL,
    shift_id BIGINT UNSIGNED NOT NULL,
    assigned_date DATE NOT NULL,
    status ENUM('scheduled', 'completed', 'missed', 'swapped'),
    swap_with_employee_id BIGINT UNSIGNED NULL,
    notes TEXT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (employee_id) REFERENCES employees(id),
    FOREIGN KEY (shift_id) REFERENCES shifts(id),
    FOREIGN KEY (swap_with_employee_id) REFERENCES employees(id),
    
    UNIQUE KEY unique_employee_date (employee_id, assigned_date),
    INDEX idx_date (assigned_date),
    INDEX idx_status (status)
);
```

#### expense_reports
```sql
CREATE TABLE expense_reports (
    id BIGINT UNSIGNED PRIMARY KEY,
    employee_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    total_amount DECIMAL(10,2) DEFAULT 0,
    currency VARCHAR(3) DEFAULT 'EUR',
    status ENUM('draft', 'submitted', 'approved', 'rejected', 'paid'),
    submitted_at TIMESTAMP NULL,
    approved_by BIGINT UNSIGNED NULL,
    approved_at TIMESTAMP NULL,
    rejection_reason TEXT NULL,
    payment_date DATE NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (employee_id) REFERENCES employees(id),
    FOREIGN KEY (approved_by) REFERENCES employees(id),
    
    INDEX idx_employee_status (employee_id, status),
    INDEX idx_submitted (submitted_at),
    INDEX idx_amount (total_amount)
);
```

#### expense_items
```sql
CREATE TABLE expense_items (
    id BIGINT UNSIGNED PRIMARY KEY,
    expense_report_id BIGINT UNSIGNED NOT NULL,
    category VARCHAR(100) NOT NULL,
    subcategory VARCHAR(100),
    description TEXT NOT NULL,
    amount DECIMAL(8,2) NOT NULL,
    currency VARCHAR(3) DEFAULT 'EUR',
    expense_date DATE NOT NULL,
    receipt_path VARCHAR(500),
    is_mileage BOOLEAN DEFAULT FALSE,
    mileage_data JSON, -- {from, to, distance, rate}
    tax_deductible BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (expense_report_id) REFERENCES expense_reports(id) ON DELETE CASCADE,
    
    INDEX idx_report (expense_report_id),
    INDEX idx_category (category),
    INDEX idx_date (expense_date)
);
```

### Tabelle Supporto

#### departments
```sql
CREATE TABLE departments (
    id BIGINT UNSIGNED PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    manager_id BIGINT UNSIGNED NULL,
    parent_department_id BIGINT UNSIGNED NULL,
    budget DECIMAL(12,2),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (manager_id) REFERENCES employees(id),
    FOREIGN KEY (parent_department_id) REFERENCES departments(id),
    
    INDEX idx_manager (manager_id),
    INDEX idx_active (is_active)
);
```

#### positions
```sql
CREATE TABLE positions (
    id BIGINT UNSIGNED PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    department_id BIGINT UNSIGNED,
    level INTEGER DEFAULT 1,
    min_salary DECIMAL(10,2),
    max_salary DECIMAL(10,2),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (department_id) REFERENCES departments(id),
    
    INDEX idx_department (department_id),
    INDEX idx_level (level)
);
```

#### employee_documents
```sql
CREATE TABLE employee_documents (
    id BIGINT UNSIGNED PRIMARY KEY,
    employee_id BIGINT UNSIGNED NOT NULL,
    document_type ENUM('contract', 'payslip', 'certificate', 'id_document', 'other'),
    title VARCHAR(200) NOT NULL,
    description TEXT,
    file_path VARCHAR(500) NOT NULL,
    file_size INTEGER,
    mime_type VARCHAR(100),
    is_confidential BOOLEAN DEFAULT FALSE,
    expiry_date DATE NULL,
    uploaded_by BIGINT UNSIGNED,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    accessed_at TIMESTAMP NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (employee_id) REFERENCES employees(id),
    FOREIGN KEY (uploaded_by) REFERENCES users(id),
    
    INDEX idx_employee_type (employee_id, document_type),
    INDEX idx_expiry (expiry_date),
    INDEX idx_confidential (is_confidential)
);
```

#### announcements
```sql
CREATE TABLE announcements (
    id BIGINT UNSIGNED PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    priority ENUM('low', 'normal', 'high', 'urgent') DEFAULT 'normal',
    target_audience JSON, -- {departments: [], positions: [], employees: []}
    published_at TIMESTAMP,
    expires_at TIMESTAMP NULL,
    created_by BIGINT UNSIGNED NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    requires_acknowledgment BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (created_by) REFERENCES users(id),
    
    INDEX idx_published (published_at),
    INDEX idx_priority (priority),
    INDEX idx_active (is_active)
);
```

#### announcement_reads
```sql
CREATE TABLE announcement_reads (
    id BIGINT UNSIGNED PRIMARY KEY,
    announcement_id BIGINT UNSIGNED NOT NULL,
    employee_id BIGINT UNSIGNED NOT NULL,
    read_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    acknowledged_at TIMESTAMP NULL,
    
    FOREIGN KEY (announcement_id) REFERENCES announcements(id) ON DELETE CASCADE,
    FOREIGN KEY (employee_id) REFERENCES employees(id),
    
    UNIQUE KEY unique_announcement_employee (announcement_id, employee_id),
    INDEX idx_read_date (read_at)
);
```

## Relazioni e Vincoli

### Relazioni Principali
- **Employee** → **User** (1:1) - Collegamento autenticazione
- **Employee** → **Department** (N:1) - Appartenenza dipartimento
- **Employee** → **Position** (N:1) - Ruolo aziendale
- **Employee** → **TimeEntry** (1:N) - Timbrature dipendente
- **Employee** → **LeaveRequest** (1:N) - Richieste ferie/permessi
- **Employee** → **ExpenseReport** (1:N) - Note spese
- **Employee** → **ShiftAssignment** (1:N) - Assegnazioni turni

### Vincoli di Integrità
- **Unicità codice dipendente** per azienda
- **Sovrapposizione turni** - Un dipendente non può avere turni sovrapposti
- **Validazione date** - Data fine >= data inizio per ferie/permessi
- **Saldi ferie** - Non permettere richieste oltre il saldo disponibile
- **Timbrature** - Clock-out deve essere successivo a clock-in

## Indici e Performance

### Indici Critici
```sql
-- Per query frequenti su presenze
CREATE INDEX idx_timeentry_employee_month ON time_entries (employee_id, YEAR(clock_in), MONTH(clock_in));

-- Per dashboard manager
CREATE INDEX idx_leave_pending ON leave_requests (status, start_date) WHERE status = 'pending';

-- Per report spese
CREATE INDEX idx_expense_period ON expense_reports (employee_id, submitted_at, status);

-- Per ricerche dipendenti
CREATE FULLTEXT INDEX idx_employee_search ON employees (first_name, last_name, employee_code);
```

### Ottimizzazioni
- **Partitioning** per time_entries su base mensile
- **Archiving** automatico dati oltre 7 anni
- **Caching** per saldi ferie e statistiche dashboard
- **Read replicas** per report e analytics

## Sicurezza Dati

### Dati Sensibili
- **Crittografia** per salary_info e dati bancari
- **Hashing** per documenti di identità
- **Audit trail** per accessi a dati personali
- **Retention policy** automatica per GDPR compliance

### Controlli Accesso
- **Row-level security** per dati dipendenti
- **Column-level** per informazioni salariali
- **Temporal access** per documenti con scadenza
- **Logging** completo per audit e compliance

## Backup e Disaster Recovery

### Strategia Backup
- **Full backup** giornaliero
- **Incremental** ogni 4 ore
- **Point-in-time recovery** per ultimi 30 giorni
- **Cross-region replication** per disaster recovery

### Monitoraggio
- **Performance metrics** per query critiche
- **Storage growth** monitoring
- **Index usage** analysis
- **Deadlock detection** e resolution automatica

## Collegamento alla Documentazione

Per implementazione tecnica consultare:
- [Piano Implementazione Tecnica](./technical_implementation.md)
- [Requisiti Funzionali](./functional_requirements.md)
- [Architettura Modulo](./module_structure.md)
