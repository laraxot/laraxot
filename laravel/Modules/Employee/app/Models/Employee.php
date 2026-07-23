<?php

declare(strict_types=1);

namespace Modules\Employee\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Support\Carbon;
use Laravel\Passport\Client;
use Laravel\Passport\Token;
use Modules\Activity\Models\Activity;
use Modules\Employee\Database\Factories\EmployeeFactory;
use Modules\Gdpr\Models\Consent;
use Modules\Gdpr\Models\Treatment;
use Modules\Media\Models\Media;
use Modules\TechPlanner\Models\Profile;
use Modules\Tenant\Models\Domain;
use Modules\User\Models\AuthenticationLog;
use Modules\User\Models\Device;
use Modules\User\Models\DeviceUser;
use Modules\User\Models\Membership;
use Modules\User\Models\Notification;
use Modules\User\Models\Permission;
use Modules\User\Models\Role;
use Modules\User\Models\SocialiteUser;
use Modules\User\Models\Team;
use Modules\User\Models\TenantUser;
use Override;
use Parental\HasParent;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;

/**
 * Class Employee.
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $employee_code
 * @property array<string, mixed> $personal_data
 * @property array<string, mixed> $contact_data
 * @property array<string, mixed> $work_data
 * @property array<string, mixed> $documents
 * @property string|null $photo_url
 * @property string $status
 * @property int|null $department_id
 * @property int|null $manager_id
 * @property int|null $position_id
 * @property array<string, mixed> $salary_data
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Modules\User\Models\User|null $user
 * @property-read Collection<int, WorkHour> $workHours
 * @property string|null $name
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string $email
 * @property string|null $email_verified_at
 * @property string|null $password
 * @property string|null $remember_token
 * @property int|null $current_team_id
 * @property string|null $profile_photo_path
 * @property string|null $deleted_at
 * @property string|null $lang
 * @property int $is_active
 * @property int $is_otp
 * @property string|null $password_expires_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property string|null $deleted_by
 * @property string|null $type
 * @property-read Collection<int, Consent> $activeConsents
 * @property-read int|null $active_consents_count
 * @property-read Collection<int, Activity> $activities
 * @property-read int|null $activities_count
 * @property-read Collection<int, AuthenticationLog> $authentications
 * @property-read int|null $authentications_count
 * @property-read Collection<int, Client> $clients
 * @property-read int|null $clients_count
 * @property-read Collection<int, Consent> $consents
 * @property-read int|null $consents_count
 * @property-read Team|null $currentTeam
 * @property-read TenantUser|Membership|DeviceUser|null $pivot
 * @property-read Collection<int, Device> $devices
 * @property-read int|null $devices_count
 * @property-read Collection<int, \Modules\User\Models\User> $all_team_users
 * @property-read string|null $full_name
 * @property-read string $status_label
 * @property-read AuthenticationLog|null $latestAuthentication
 * @property-read MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read DatabaseNotificationCollection<int, Notification> $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection<int, Team> $ownedTeams
 * @property-read int|null $owned_teams_count
 * @property-read Collection<int, Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read Profile|null $profile
 * @property-read Collection<int, Role> $roles
 * @property-read int|null $roles_count
 * @property-read Collection<int, SocialiteUser> $socialiteUsers
 * @property-read int|null $socialite_users_count
 * @property-read int|null $subordinates_count
 * @property-read Collection<int, Membership> $teamUsers
 * @property-read int|null $team_users_count
 * @property-read Collection<int, Team> $teams
 * @property-read int|null $teams_count
 * @property-read Collection<int, Domain> $tenants
 * @property-read int|null $tenants_count
 * @property-read Collection<int, Token> $tokens
 * @property-read int|null $tokens_count
 * @property-read Collection<int, Treatment> $treatments
 * @property-read int|null $treatments_count
 * @property-read int|null $work_hours_count
 *
 * @method static EmployeeFactory factory($count = null, $state = [])
 * @method static Builder<static>|Employee newModelQuery()
 * @method static Builder<static>|Employee newQuery()
 * @method static Builder<static>|Employee orWhereNotState(string $column, $states)
 * @method static Builder<static>|Employee orWhereState(string $column, $states)
 * @method static Builder<static>|Employee permission($permissions, $without = false)
 * @method static Builder<static>|Employee query()
 * @method static Builder<static>|Employee role($roles, $guard = null, $without = false)
 * @method static Builder<static>|Employee whereCreatedAt($value)
 * @method static Builder<static>|Employee whereCreatedBy($value)
 * @method static Builder<static>|Employee whereCurrentTeamId($value)
 * @method static Builder<static>|Employee whereDeletedAt($value)
 * @method static Builder<static>|Employee whereDeletedBy($value)
 * @method static Builder<static>|Employee whereEmail($value)
 * @method static Builder<static>|Employee whereEmailVerifiedAt($value)
 * @method static Builder<static>|Employee whereFirstName($value)
 * @method static Builder<static>|Employee whereId($value)
 * @method static Builder<static>|Employee whereIsActive($value)
 * @method static Builder<static>|Employee whereIsOtp($value)
 * @method static Builder<static>|Employee whereLang($value)
 * @method static Builder<static>|Employee whereLastName($value)
 * @method static Builder<static>|Employee whereName($value)
 * @method static Builder<static>|Employee whereNotState(string $column, $states)
 * @method static Builder<static>|Employee wherePassword($value)
 * @method static Builder<static>|Employee wherePasswordExpiresAt($value)
 * @method static Builder<static>|Employee whereProfilePhotoPath($value)
 * @method static Builder<static>|Employee whereRememberToken($value)
 * @method static Builder<static>|Employee whereState(string $column, $states)
 * @method static Builder<static>|Employee whereType($value)
 * @method static Builder<static>|Employee whereUpdatedAt($value)
 * @method static Builder<static>|Employee whereUpdatedBy($value)
 * @method static Builder<static>|Employee withoutPermission($permissions)
 * @method static Builder<static>|Employee withoutRole($roles, $guard = null)
 *
 * @property-read Employee|null $manager
 * @property-read Collection<int, Employee> $subordinates
 *
 * @mixin \Eloquent
 */
class Employee extends User
{
    use HasParent;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'employee_code',
        'personal_data',
        'contact_data',
        'work_data',
        'documents',
        'photo_url',
        'status',
        'department_id',
        'manager_id',
        'position_id',
        'salary_data',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    #[Override]
    protected function casts(): array
    {
        return [
            'personal_data' => 'array',
            'contact_data' => 'array',
            'work_data' => 'array',
            'documents' => 'array',
            'salary_data' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the work hours for this employee.
     *
     * @return HasMany<WorkHour, $this>
     */
    public function workHours(): HasMany
    {
        return $this->hasMany(WorkHour::class, 'employee_id');
    }

    /**
     * Get the manager of this employee.
     *
     * @return BelongsTo<Employee, $this>
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    /**
     * Get the subordinates of this employee.
     *
     * @return HasMany<Employee, $this>
     */
    public function subordinates(): HasMany
    {
        return $this->hasMany(Employee::class, 'manager_id');
    }

    /**
     * Check if employee is active today.
     */
    public function isActiveToday(): bool
    {
        return $this->workHours()->whereDate('timestamp', today())->exists();
    }

    /**
     * Get employee's status label.
     */
    public function getStatusLabelAttribute(): string
    {
        $status = $this->status;
        $statusStr = is_string($status) ? $status : '';

        return match ($statusStr) {
            'active' => 'Attivo',
            'inactive' => 'Inattivo',
            'on_leave' => 'In Ferie',
            'terminated' => 'Cessato',
            default => 'Sconosciuto',
        };
    }
}
