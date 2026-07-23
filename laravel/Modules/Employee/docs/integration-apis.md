# Employee Module - Integration APIs Documentation

> **Documento generato**: 2024-09-03  
> **Versione**: 1.0  
> **Compliance**: Laraxot Philosophy, API-First Design  

## API Endpoints Overview

### 1. Time Tracking APIs

#### POST /api/employee/time-entries
**Descrizione**: Crea una nuova timbratura  
**Autenticazione**: Bearer Token (Employee)

```php
/**
 * Request Body
 */
{
    "type": "clock_in|clock_out|break_start|break_end",
    "timestamp": "2024-09-03T08:30:00Z",
    "location": {
        "lat": 45.4642,
        "lng": 9.1900,
        "name": "Ufficio Milano"
    },
    "device_info": {
        "platform": "mobile",
        "os": "iOS 17.0",
        "app_version": "1.2.0"
    },
    "photo_path": "/storage/timecard_photos/employee_123_20240903_083000.jpg",
    "notes": "Inizio turno mattutino"
}

/**
 * Response Success (201)
 */
{
    "success": true,
    "data": {
        "id": 456,
        "employee_id": 123,
        "type": "clock_in",
        "timestamp": "2024-09-03T08:30:00Z",
        "status": "pending",
        "next_action": "break_start",
        "worked_hours_today": 0.0
    },
    "message": "Timbratura registrata con successo"
}

/**
 * Response Error (422)
 */
{
    "success": false,
    "error": {
        "code": "INVALID_SEQUENCE",
        "message": "Sequenza timbratura non valida",
        "details": {
            "expected": "break_start",
            "received": "clock_in",
            "last_entry": {
                "type": "clock_in",
                "timestamp": "2024-09-03T08:30:00Z"
            }
        }
    }
}
```

#### GET /api/employee/time-entries
**Descrizione**: Recupera timbrature dipendente  
**Parametri**: date_from, date_to, type, status

```php
/**
 * Query Parameters
 */
GET /api/employee/time-entries?date_from=2024-09-01&date_to=2024-09-03&type=clock_in

/**
 * Response (200)
 */
{
    "success": true,
    "data": [
        {
            "id": 456,
            "type": "clock_in",
            "timestamp": "2024-09-03T08:30:00Z",
            "location": {
                "lat": 45.4642,
                "lng": 9.1900,
                "name": "Ufficio Milano"
            },
            "status": "approved",
            "approved_by": {
                "id": 789,
                "name": "Mario Rossi"
            },
            "approved_at": "2024-09-03T10:00:00Z"
        }
    ],
    "meta": {
        "total": 45,
        "per_page": 20,
        "current_page": 1,
        "last_page": 3
    }
}
```

#### GET /api/employee/status
**Descrizione**: Stato corrente sessione dipendente

```php
/**
 * Response (200)
 */
{
    "success": true,
    "data": {
        "current_status": "clocked_in",
        "last_entry": {
            "id": 456,
            "type": "clock_in",
            "timestamp": "2024-09-03T08:30:00Z"
        },
        "next_action": "break_start",
        "worked_hours_today": 3.5,
        "session_duration": "03:30:00",
        "today_entries": [
            {
                "type": "clock_in",
                "timestamp": "2024-09-03T08:30:00Z",
                "formatted_time": "08:30"
            }
        ]
    }
}
```

### 2. Reporting APIs

#### GET /api/employee/reports/worked-hours
**Descrizione**: Report ore lavorate per periodo

```php
/**
 * Query Parameters
 */
GET /api/employee/reports/worked-hours?period=month&year=2024&month=9

/**
 * Response (200)
 */
{
    "success": true,
    "data": {
        "period": {
            "type": "month",
            "year": 2024,
            "month": 9,
            "days_in_period": 30,
            "working_days": 22
        },
        "summary": {
            "total_hours": 176.5,
            "average_hours_per_day": 8.02,
            "overtime_hours": 8.5,
            "break_time_total": 22.0
        },
        "daily_breakdown": [
            {
                "date": "2024-09-01",
                "total_hours": 8.0,
                "entries_count": 4,
                "first_entry": "08:30:00",
                "last_entry": "17:30:00",
                "status": "complete"
            }
        ]
    }
}
```

#### GET /api/employee/reports/attendance
**Descrizione**: Report presenza per periodo

```php
/**
 * Response (200)
 */
{
    "success": true,
    "data": {
        "attendance_rate": 95.5,
        "days_present": 21,
        "days_absent": 1,
        "days_partial": 0,
        "punctuality_score": 92.3,
        "average_arrival_time": "08:25:00",
        "late_arrivals": 2,
        "early_departures": 0
    }
}
```

### 3. Manager APIs

#### GET /api/manager/employees/{employeeId}/time-entries
**Descrizione**: Visualizza timbrature dipendente (Manager only)

#### PUT /api/manager/time-entries/{entryId}/approve
**Descrizione**: Approva/Rifiuta timbratura

```php
/**
 * Request Body
 */
{
    "status": "approved|rejected",
    "notes": "Approvato - orario corretto"
}

/**
 * Response (200)
 */
{
    "success": true,
    "data": {
        "id": 456,
        "status": "approved",
        "approved_by": 789,
        "approved_at": "2024-09-03T10:00:00Z",
        "approval_notes": "Approvato - orario corretto"
    },
    "message": "Timbratura approvata con successo"
}
```

#### GET /api/manager/dashboard/stats
**Descrizione**: Statistiche dashboard manager

```php
/**
 * Response (200)
 */
{
    "success": true,
    "data": {
        "team_size": 15,
        "employees_present": 12,
        "employees_on_break": 3,
        "pending_approvals": 8,
        "overtime_alerts": 2,
        "attendance_rate": 94.2,
        "recent_activities": [
            {
                "employee_name": "Giuseppe Verdi",
                "action": "clock_in",
                "timestamp": "2024-09-03T08:45:00Z",
                "needs_approval": true
            }
        ]
    }
}
```

## External System Integrations

### 1. Payroll System Integration

#### Endpoint: POST /api/integrations/payroll/export
**Descrizione**: Esporta dati per sistema paghe

```php
/**
 * Request Body
 */
{
    "period": {
        "start_date": "2024-09-01",
        "end_date": "2024-09-30"
    },
    "employees": [123, 124, 125], // Optional: specific employees
    "format": "json|csv|xml",
    "include_overtime": true,
    "include_breaks": false
}

/**
 * Response (200)
 */
{
    "success": true,
    "data": {
        "export_id": "EXP_202409_001",
        "format": "json",
        "records_count": 450,
        "employees_count": 15,
        "period": {
            "start_date": "2024-09-01",
            "end_date": "2024-09-30"
        },
        "payroll_data": [
            {
                "employee_id": 123,
                "employee_code": "EMP001",
                "full_name": "Giuseppe Verdi",
                "regular_hours": 160.0,
                "overtime_hours": 8.5,
                "total_hours": 168.5,
                "days_worked": 22,
                "days_absent": 0,
                "hourly_rate": 25.00,
                "gross_pay": 4212.50
            }
        ]
    }
}
```

### 2. Calendar System Integration

#### Webhook: POST /webhooks/calendar/schedule-changed
**Descrizione**: Riceve notifiche modifiche calendario

```php
/**
 * Webhook Payload
 */
{
    "event_type": "schedule_updated",
    "employee_id": 123,
    "schedule_date": "2024-09-03",
    "changes": {
        "start_time": {
            "old": "09:00:00",
            "new": "08:30:00"
        },
        "end_time": {
            "old": "18:00:00", 
            "new": "17:30:00"
        }
    },
    "reason": "Client meeting rescheduled"
}
```

### 3. LDAP/Active Directory Integration

#### Background Job: SyncEmployeesFromLDAP
**Descrizione**: Sincronizzazione automatica dipendenti

```php
/**
 * LDAP Sync Configuration
 */
{
    "ldap_server": "ldap://company.local",
    "base_dn": "ou=employees,dc=company,dc=local",
    "sync_schedule": "daily_at_02:00",
    "attributes_mapping": {
        "cn": "name",
        "mail": "email",
        "employeeID": "employee_code",
        "department": "department_name",
        "title": "position_title"
    },
    "auto_create_employees": true,
    "auto_disable_removed": true
}
```

## Webhook Events

### 1. Time Entry Events

```php
/**
 * Event: time_entry.created
 */
{
    "event": "time_entry.created",
    "timestamp": "2024-09-03T08:30:00Z",
    "data": {
        "entry_id": 456,
        "employee_id": 123,
        "type": "clock_in",
        "requires_approval": true
    }
}

/**
 * Event: time_entry.approved
 */
{
    "event": "time_entry.approved",
    "timestamp": "2024-09-03T10:00:00Z",
    "data": {
        "entry_id": 456,
        "employee_id": 123,
        "approved_by": 789,
        "status": "approved"
    }
}
```

### 2. Daily Summary Events

```php
/**
 * Event: daily_summary.completed
 */
{
    "event": "daily_summary.completed",
    "timestamp": "2024-09-03T23:59:59Z",
    "data": {
        "employee_id": 123,
        "work_date": "2024-09-03",
        "total_hours": 8.5,
        "entries_count": 4,
        "overtime_hours": 0.5,
        "all_approved": true
    }
}
```

## API Authentication & Security

### 1. Token-Based Authentication

```php
/**
 * Request Headers
 */
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...
Accept: application/json
Content-Type: application/json

/**
 * Token Payload
 */
{
    "sub": 123, // Employee ID
    "role": "employee|manager|admin",
    "permissions": ["time_tracking", "view_reports"],
    "exp": 1725356400,
    "tenant_id": "company_001"
}
```

### 2. Rate Limiting

```php
/**
 * Rate Limits per Endpoint
 */
- Time Entry Creation: 10 requests/minute per employee
- Status Check: 60 requests/minute per employee  
- Reports: 20 requests/hour per user
- Manager APIs: 100 requests/hour per manager

/**
 * Rate Limit Headers
 */
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 45
X-RateLimit-Reset: 1725356400
```

### 3. API Versioning

```php
/**
 * Version Headers
 */
Accept: application/vnd.api+json
API-Version: v1

/**
 * Versioned Endpoints
 */
/api/v1/employee/time-entries
/api/v2/employee/time-entries // Future version with breaking changes
```

## Error Handling Standards

### 1. Standard Error Response

```php
/**
 * Error Response Format
 */
{
    "success": false,
    "error": {
        "code": "VALIDATION_ERROR",
        "message": "I dati forniti non sono validi",
        "details": {
            "timestamp": ["Il campo timestamp è obbligatorio"],
            "type": ["Il tipo deve essere uno di: clock_in, clock_out, break_start, break_end"]
        },
        "trace_id": "req_123456789"
    }
}
```

### 2. Business Logic Errors

```php
/**
 * Common Error Codes
 */
- INVALID_SEQUENCE: Sequenza timbratura non valida
- DUPLICATE_ENTRY: Timbratura duplicata
- OUTSIDE_SCHEDULE: Timbratura fuori orario lavorativo
- LOCATION_REQUIRED: Geolocalizzazione obbligatoria
- PHOTO_REQUIRED: Foto verifica obbligatoria
- APPROVAL_REQUIRED: Approvazione richiesta
- INSUFFICIENT_PERMISSIONS: Permessi insufficienti
```

## Client SDK Examples

### 1. JavaScript/TypeScript SDK

```typescript
/**
 * Employee Time Tracking SDK
 */
class EmployeeTimeAPI {
    constructor(private apiToken: string, private baseUrl: string) {}
    
    async clockIn(location?: Location, photo?: File): Promise<TimeEntry> {
        const formData = new FormData();
        formData.append('type', 'clock_in');
        formData.append('timestamp', new Date().toISOString());
        
        if (location) {
            formData.append('location[lat]', location.lat.toString());
            formData.append('location[lng]', location.lng.toString());
        }
        
        if (photo) {
            formData.append('photo', photo);
        }
        
        const response = await fetch(`${this.baseUrl}/api/employee/time-entries`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${this.apiToken}`,
            },
            body: formData
        });
        
        return response.json();
    }
    
    async getCurrentStatus(): Promise<EmployeeStatus> {
        const response = await fetch(`${this.baseUrl}/api/employee/status`, {
            headers: {
                'Authorization': `Bearer ${this.apiToken}`,
                'Accept': 'application/json'
            }
        });
        
        return response.json();
    }
}
```

### 2. Mobile App Integration

```swift
/**
 * iOS Swift SDK Example
 */
class EmployeeTimeAPI {
    private let apiToken: String
    private let baseURL: URL
    
    init(apiToken: String, baseURL: URL) {
        self.apiToken = apiToken
        self.baseURL = baseURL
    }
    
    func clockIn(location: CLLocation?, photo: UIImage?) async throws -> TimeEntry {
        var request = URLRequest(url: baseURL.appendingPathComponent("/api/employee/time-entries"))
        request.httpMethod = "POST"
        request.setValue("Bearer \(apiToken)", forHTTPHeaderField: "Authorization")
        
        let boundary = UUID().uuidString
        request.setValue("multipart/form-data; boundary=\(boundary)", forHTTPHeaderField: "Content-Type")
        
        var body = Data()
        
        // Add form fields
        body.append("--\(boundary)\r\n".data(using: .utf8)!)
        body.append("Content-Disposition: form-data; name=\"type\"\r\n\r\n".data(using: .utf8)!)
        body.append("clock_in\r\n".data(using: .utf8)!)
        
        // Add location if available
        if let location = location {
            body.append("--\(boundary)\r\n".data(using: .utf8)!)
            body.append("Content-Disposition: form-data; name=\"location[lat]\"\r\n\r\n".data(using: .utf8)!)
            body.append("\(location.coordinate.latitude)\r\n".data(using: .utf8)!)
        }
        
        request.httpBody = body
        
        let (data, _) = try await URLSession.shared.data(for: request)
        return try JSONDecoder().decode(TimeEntry.self, from: data)
    }
}
```

## Performance Considerations

### 1. Caching Strategy
- Employee status: 30 seconds cache
- Daily reports: 5 minutes cache
- Monthly reports: 1 hour cache
- Manager dashboard: 2 minutes cache

### 2. Database Optimization
- Composite indexes on (employee_id, timestamp)
- Partitioning by date for large datasets
- Read replicas for reporting queries

### 3. API Response Optimization
- Pagination for list endpoints
- Field selection via query parameters
- Compressed responses (gzip)
- CDN caching for static data

---

*Documento tecnico per sviluppatori API - Compliance Laraxot Philosophy*
