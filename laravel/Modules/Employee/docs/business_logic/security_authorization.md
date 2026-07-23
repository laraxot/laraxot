# Security & Authorization Business Logic

## 🔐 Role-Based Access Control (RBAC)

### Core Role Definitions

```php
enum EmployeeRoleEnum: string
{
    case EMPLOYEE = 'employee';              // Basic employee access
    case TEAM_LEAD = 'team_lead';            // Team management
    case DEPARTMENT_MANAGER = 'dept_manager'; // Department management
    case HR_ASSISTANT = 'hr_assistant';      // HR support staff
    case HR_MANAGER = 'hr_manager';          // Full HR access
    case EXECUTIVE = 'executive';            // Executive oversight
    case SYSTEM_ADMIN = 'system_admin';      // Technical administration
}

class CheckRoleAccessAction extends QueueableAction
{
    public function handle(User $user, string $requiredRole): bool
    {
        $roleHierarchy = [
            EmployeeRoleEnum::EMPLOYEE->value => 1,
            EmployeeRoleEnum::TEAM_LEAD->value => 2,
            EmployeeRoleEnum::DEPARTMENT_MANAGER->value => 3,
            EmployeeRoleEnum::HR_ASSISTANT->value => 4,
            EmployeeRoleEnum::HR_MANAGER->value => 5,
            EmployeeRoleEnum::EXECUTIVE->value => 6,
            EmployeeRoleEnum::SYSTEM_ADMIN->value => 7,
        ];
        
        $userRoleLevel = $roleHierarchy[$user->primary_role] ?? 0;
        $requiredRoleLevel = $roleHierarchy[$requiredRole] ?? 0;
        
        return $userRoleLevel >= $requiredRoleLevel;
    }
}
```

### Permission Matrix

```php
class EmployeePermissionMatrix
{
    private const PERMISSIONS = [
        EmployeeRoleEnum::EMPLOYEE->value => [
            'view_own_profile',
            'edit_own_contact_info',
            'view_own_time_entries',
            'request_time_off',
            'view_company_directory',
        ],
        EmployeeRoleEnum::TEAM_LEAD->value => [
            'view_team_profiles',
            'approve_team_time_off',
            'view_team_time_entries',
            'manage_team_schedules',
            'generate_team_reports',
        ],
        EmployeeRoleEnum::DEPARTMENT_MANAGER->value => [
            'view_department_profiles',
            'approve_department_time_off',
            'manage_department_budget',
            'create_department_reports',
            'manage_department_positions',
        ],
        EmployeeRoleEnum::HR_MANAGER->value => [
            'view_all_profiles',
            'edit_employee_data',
            'manage_organizational_structure',
            'access_sensitive_data',
            'run_hr_analytics',
            'manage_system_configuration',
        ],
    ];
    
    public static function getPermissions(string $role): array
    {
        $permissions = self::PERMISSIONS[$role] ?? [];
        
        // Inherit permissions from lower roles
        $roleHierarchy = array_keys(self::PERMISSIONS);
        $roleIndex = array_search($role, $roleHierarchy);
        
        if ($roleIndex > 0) {
            for ($i = 0; $i < $roleIndex; $i++) {
                $permissions = array_merge($permissions, self::PERMISSIONS[$roleHierarchy[$i]]);
            }
        }
        
        return array_unique($permissions);
    }
}
```

## 👥 Data Visibility Rules

### Employee Data Access Control

```php
class CheckEmployeeDataAccessAction extends QueueableAction
{
    public function handle(User $requestor, Employee $targetEmployee): bool
    {
        // Always allow access to own data
        if ($requestor->id === $targetEmployee->user_id) {
            return true;
        }
        
        // HR managers have full access
        if ($requestor->hasRole(EmployeeRoleEnum::HR_MANAGER)) {
            return true;
        }
        
        // Team leads can access team members
        if ($requestor->hasRole(EmployeeRoleEnum::TEAM_LEAD)) {
            return $this->isTeamMember($requestor, $targetEmployee);
        }
        
        // Department managers can access department members
        if ($requestor->hasRole(EmployeeRoleEnum::DEPARTMENT_MANAGER)) {
            return $requestor->department_id === $targetEmployee->department_id;
        }
        
        // Executives can access all data
        if ($requestor->hasRole(EmployeeRoleEnum::EXECUTIVE)) {
            return true;
        }
        
        return false;
    }
}
```

### Sensitive Data Masking

```php
class MaskSensitiveDataAction extends QueueableAction
{
    public function handle(array $employeeData, User $requestor): array
    {
        $maskedData = $employeeData;
        
        // Mask personal identification numbers
        if (isset($maskedData['personal_identification'])) {
            $maskedData['personal_identification'] = $this->maskString(
                $maskedData['personal_identification']
            );
        }
        
        // Mask bank account information
        if (isset($maskedData['bank_account'])) {
            $maskedData['bank_account'] = $this->maskBankAccount(
                $maskedData['bank_account']
            );
        }
        
        // Hide salary data for non-HR roles
        if (!$requestor->hasRole(EmployeeRoleEnum::HR_MANAGER)) {
            unset($maskedData['salary_data']);
            unset($maskedData['compensation_history']);
            
            // Show only salary band for managers
            if ($requestor->hasRole(EmployeeRoleEnum::DEPARTMENT_MANAGER)) {
                $maskedData['salary_band'] = $this->getSalaryBand($employeeData['position_id']);
            }
        }
        
        // Hide medical information
        if (isset($maskedData['medical_info'])) {
            unset($maskedData['medical_info']);
        }
        
        return $maskedData;
    }
    
    private function maskString(string $value): string
    {
        if (strlen($value) <= 4) {
            return str_repeat('*', strlen($value));
        }
        
        return substr($value, 0, 2) . str_repeat('*', strlen($value) - 4) . substr($value, -2);
    }
}
```

## 📋 GDPR Compliance Rules

### Data Processing Consent

```php
class CheckGDPRConsentAction extends QueueableAction
{
    public function handle(Employee $employee, string $processingPurpose): bool
    {
        $requiredConsents = [
            'payroll_processing' => ['data_processing', 'salary_management'],
            'performance_review' => ['data_processing', 'performance_management'],
            'health_insurance' => ['data_processing', 'health_data', 'insurance_processing'],
            'company_directory' => ['data_processing', 'contact_sharing'],
        ];
        
        if (!isset($requiredConsents[$processingPurpose])) {
            return false;
        }
        
        $consents = $employee->gdpr_consents;
        
        foreach ($requiredConsents[$processingPurpose] as $requiredConsent) {
            if (!($consents[$requiredConsent] ?? false)) {
                return false;
            }
        }
        
        return true;
    }
}
```

### Right to Be Forgotten

```php
class ProcessRightToBeForgottenAction extends QueueableAction
{
    public function handle(Employee $employee): void
    {
        // 1. Anonymize personal data
        $this->anonymizePersonalData($employee);
        
        // 2. Archive business data (keep for legal requirements)
        $this->archiveBusinessRecords($employee);
        
        // 3. Remove from active systems
        $this->deactivateSystemAccess($employee);
        
        // 4. Log the deletion process
        $this->logDeletionProcess($employee);
        
        // 5. Send confirmation
        $this->sendDeletionConfirmation($employee);
    }
    
    private function anonymizePersonalData(Employee $employee): void
    {
        $anonymousData = [
            'first_name' => 'Anonymous',
            'last_name' => 'User',
            'email' => 'anonymous@example.com',
            'phone' => null,
            'address' => null,
            'personal_identification' => null,
            'bank_account' => null,
            'photo_url' => null,
        ];
        
        $employee->update($anonymousData);
    }
}
```

## 🔐 Authentication & Session Management

### Multi-Factor Authentication

```php
class EnforceMFAAction extends QueueableAction
{
    public function handle(User $user): void
    {
        $mfaRequired = match($user->primary_role) {
            EmployeeRoleEnum::HR_MANAGER->value => true,
            EmployeeRoleEnum::SYSTEM_ADMIN->value => true,
            EmployeeRoleEnum::EXECUTIVE->value => true,
            default => $user->access_sensitive_data
        };
        
        if ($mfaRequired && !$user->mfa_enabled) {
            // Force MFA setup on next login
            $user->update(['mfa_required' => true]);
            
            // Notify user to setup MFA
            $this->sendMFASetupNotification($user);
        }
    }
}
```

### Session Security Rules

```php
class ValidateSessionAction extends QueueableAction
{
    public function handle(User $user, string $ipAddress, string $userAgent): bool
    {
        // Check for suspicious IP changes
        if ($user->last_login_ip && $user->last_login_ip !== $ipAddress) {
            $ipChangeRisk = $this->calculateIPChangeRisk($user->last_login_ip, $ipAddress);
            if ($ipChangeRisk > 0.7) {
                $this->logSuspiciousActivity($user, 'IP change risk: ' . $ipChangeRisk);
                return false;
            }
        }
        
        // Check user agent consistency
        if ($user->last_user_agent && $user->last_user_agent !== $userAgent) {
            $this->logSuspiciousActivity($user, 'User agent changed');
        }
        
        // Check session duration (max 8 hours for sensitive roles)
        if ($user->hasSensitiveRole() && $this->getSessionDuration() > 28800) {
            $this->forceReauthentication($user);
            return false;
        }
        
        return true;
    }
}
```

## 📊 Audit Logging Rules

### Comprehensive Activity Logging

```php
class LogEmployeeActivityAction extends QueueableAction
{
    public function handle(
        User $actor,
        Employee $subject,
        string $action,
        ?array $oldData = null,
        ?array $newData = null
    ): void {
        $logEntry = [
            'timestamp' => now()->toISOString(),
            'actor_id' => $actor->id,
            'actor_role' => $actor->primary_role,
            'subject_id' => $subject->id,
            'action' => $action,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ];
        
        if ($oldData) {
            $logEntry['old_data'] = $this->sanitizeForLogging($oldData);
        }
        
        if ($newData) {
            $logEntry['new_data'] = $this->sanitizeForLogging($newData);
        }
        
        // Store in audit log
        AuditLog::create($logEntry);
        
        // Real-time alert for sensitive actions
        if ($this->isSensitiveAction($action)) {
            $this->sendSecurityAlert($logEntry);
        }
    }
    
    private function sanitizeForLogging(array $data): array
    {
        $sensitiveFields = ['password', 'token', 'secret', 'key', 'bank', 'credit'];
        
        return array_map(function ($value, $key) use ($sensitiveFields) {
            if (in_array(strtolower($key), $sensitiveFields)) {
                return '***REDACTED***';
            }
            return $value;
        }, $data, array_keys($data));
    }
}
```

### Retention Policy Enforcement

```php
class EnforceAuditRetentionAction extends QueueableAction
{
    public function handle(): void
    {
        $retentionPeriods = [
            'login_attempts' => 90, // days
            'password_changes' => 365,
            'profile_updates' => 365,
            'sensitive_operations' => 730,
            'compliance_events' => 1825, // 5 years
        ];
        
        foreach ($retentionPeriods as $eventType => $days) {
            $cutoffDate = now()->subDays($days);
            
            AuditLog::where('event_type', $eventType)
                ->where('created_at', '<', $cutoffDate)
                ->delete();
        }
    }
}
```

## 🚨 Security Incident Response

### Breach Detection Rules

```php
class DetectSecurityBreachAction extends QueueableAction
{
    public function handle(): void
    {
        // Check for multiple failed logins from same IP
        $failedLogins = FailedLoginAttempt::where('created_at', '>', now()->subHour())
            ->groupBy('ip_address')
            ->havingRaw('COUNT(*) > 5')
            ->get();
        
        foreach ($failedLogins as $attempt) {
            $this->triggerBreachProtocol($attempt->ip_address, 'multiple_failed_logins');
        }
        
        // Check for unusual access patterns
        $unusualAccess = $this->detectUnusualAccessPatterns();
        if ($unusualAccess) {
            $this->triggerBreachProtocol($unusualAccess['ip'], 'unusual_access_pattern');
        }
    }
    
    private function triggerBreachProtocol(string $ipAddress, string $reason): void
    {
        // 1. Block IP temporarily
        BlockedIP::create(['ip_address' => $ipAddress, 'reason' => $reason]);
        
        // 2. Notify security team
        $this->notifySecurityTeam($ipAddress, $reason);
        
        // 3. Increase logging for related accounts
        $this->increaseMonitoring($ipAddress);
        
        // 4. Force password reset for potentially compromised accounts
        $this->forcePasswordResets($ipAddress);
    }
}
```

### Incident Response Workflow

```php
class HandleSecurityIncidentAction extends QueueableAction
{
    public function handle(SecurityIncident $incident): void
    {
        $severity = $this->calculateSeverity($incident);
        
        match($severity) {
            'low' => $this->handleLowSeverityIncident($incident),
            'medium' => $this->handleMediumSeverityIncident($incident),
            'high' => $this->handleHighSeverityIncident($incident),
            'critical' => $this->handleCriticalIncident($incident),
        };
        
        // Log incident response
        $this->logIncidentResponse($incident, $severity);
        
        // Update incident status
        $incident->update(['status' => 'handled', 'severity' => $severity]);
    }
    
    private function handleCriticalIncident(SecurityIncident $incident): void
    {
        // 1. Immediate system lockdown
        $this->initiateSystemLockdown();
        
        // 2. Notify executive team
        $this->notifyExecutiveTeam($incident);
        
        // 3. Engage external security partners
        $this->engageExternalSecurity();
        
        // 4. Preserve evidence for investigation
        $this->preserveEvidence($incident);
        
        // 5. Regulatory reporting if required
        if ($this->requiresRegulatoryReporting($incident)) {
            $this->fileRegulatoryReports($incident);
        }
    }
}
```

## 🔗 Integration Security

### API Access Control

```php
class ValidateAPIAccessAction extends QueueableAction
{
    public function handle(string $apiKey, string $endpoint): bool
    {
        $apiClient = APIClient::where('api_key', $apiKey)->first();
        
        if (!$apiClient || !$apiClient->is_active) {
            return false;
        }
        
        // Check rate limiting
        if ($this->exceedsRateLimit($apiClient)) {
            return false;
        }
        
        // Check endpoint permissions
        if (!$this->hasEndpointAccess($apiClient, $endpoint)) {
            return false;
        }
        
        // Check IP whitelist
        if (!$this->validateIPAddress($apiClient)) {
            return false;
        }
        
        return true;
    }
}
```

### Data Encryption Rules

```php
class EncryptSensitiveDataAction extends QueueableAction
{
    public function handle(array $data): array
    {
        $encryptedData = [];
        $sensitiveFields = ['password', 'token', 'secret', 'personal_id', 'bank_account'];
        
        foreach ($data as $key => $value) {
            if (in_array($key, $sensitiveFields) && !empty($value)) {
                $encryptedData[$key] = encrypt($value);
            } else {
                $encryptedData[$key] = $value;
            }
        }
        
        return $encryptedData;
    }
}
```

---

*This security and authorization business logic ensures compliance with GDPR, internal policies, and industry best practices. All security-related operations must use Queueable Actions and follow the principle of least privilege.*