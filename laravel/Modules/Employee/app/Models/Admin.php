<?php

declare(strict_types=1);

namespace Modules\Employee\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Laravel\Passport\Client;
use Laravel\Passport\Token;
use Modules\Activity\Models\Activity;
use Modules\Gdpr\Models\Consent;
use Modules\Gdpr\Models\Treatment;
use Modules\Media\Models\Media;
use Modules\User\Database\Factories\UserFactory;
use Modules\User\Models\Authentication;
use Modules\User\Models\AuthenticationLog;
use Modules\User\Models\Device;
use Modules\User\Models\Membership;
use Modules\User\Models\Notification;
use Modules\User\Models\Permission;
use Modules\User\Models\Role;
use Modules\User\Models\SocialiteUser;
use Modules\User\Models\Team;
use Modules\User\Models\Tenant;
use Modules\Xot\Contracts\ProfileContract;
use Override;
use Parental\HasParent;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;

/**
 * Class Admin
 *
 * NOTA: Il trait HasFactory è stato rimosso perché già incluso nella catena di ereditarietà (BaseUser -> User -> Admin).
 * Dichiararlo qui è ridondante e può causare warning o confusione.
 * Vedi docs/DRY-model-traits.md
 *
 * @property string $id
 * @property string $user_id
 * @property string|null $date_of_birth
 *                                      Employee Module Admin Model
 *
 * Admin user type using Single Table Inheritance with Parental package.
 * Child class of User model for administrative users.
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $type
 * @property string|null $first_name
 * @property string|null $last_name
 * @property Carbon|null $date_of_birth
 * @property string|null $gender
 * @property string|null $address
 * @property string|null $phone
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property-read \Modules\User\Models\User|null $user
 *
 * @method static Builder|Admin newModelQuery()
 * @method static Builder|Admin newQuery()
 * @method static Builder|Admin query()
 * @method static Builder|Admin whereAddress($value)
 * @method static Builder|Admin whereCreatedAt($value)
 * @method static Builder|Admin whereCreatedBy($value)
 * @method static Builder|Admin whereDateOfBirth($value)
 * @method static Builder|Admin whereGender($value)
 * @method static Builder|Admin whereId($value)
 * @method static Builder|Admin wherePhone($value)
 * @method static Builder|Admin whereUpdatedAt($value)
 * @method static Builder|Admin whereUpdatedBy($value)
 * @method static Builder|Admin whereUserId($value)
 *
 * @property string|null $name
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string $email
 * @property string|null $city
 * @property string|null $registration_number
 * @property string|null $status
 * @property array<array-key, mixed>|null $certifications
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $password
 * @property string|null $remember_token
 * @property int|null $current_team_id
 * @property string|null $profile_photo_path
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $state
 * @property array<array-key, mixed>|null $moderation_data
 * @property string|null $lang
 * @property string|null $type
 * @property bool $is_active
 * @property bool $is_otp
 * @property \Illuminate\Support\Carbon|null $password_expires_at
 * @property string|null $uuid
 * @property string|null $full_name
 * @property string|null $deleted_by
 * @property-read Collection<int, Consent> $activeConsents
 * @property-read int|null $active_consents_count
 * @property-read Collection<int, Activity> $activities
 * @property-read int|null $activities_count
 * @property-read Collection<int, Authentication> $authentications
 * @property-read int|null $authentications_count
 * @property-read Collection<int, Client> $clients
 * @property-read int|null $clients_count
 * @property-read Collection<int, Consent> $consents
 * @property-read int|null $consents_count
 * @property-read Team|null $currentTeam
 * @property-read Collection<int, Model> $all_team_users
 * @property-read AuthenticationLog|null $latestAuthentication
 * @property-read MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read DatabaseNotificationCollection<int, Notification> $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection<int, Team> $ownedTeams
 * @property-read int|null $owned_teams_count
 * @property-read Collection<int, Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read ProfileContract|null $profile
 * @property-read Collection<int, Role> $roles
 * @property-read int|null $roles_count
 * @property-read Collection<int, SocialiteUser> $socialiteUsers
 * @property-read int|null $socialite_users_count
 * @property-read mixed|null $pivot
 * @property-read Collection<int, Team> $teams
 * @property-read int|null $teams_count
 * @property-read Collection<int, Token> $tokens
 * @property-read int|null $tokens_count
 * @property-read Collection<int, Treatment> $treatments
 * @property-read int|null $treatments_count
 *
 * @method static Builder<static>|Admin admins()
 * @method static Builder<static>|Admin doctors()
 * @method static UserFactory factory($count = null, $state = [])
 * @method static Builder<static>|Admin orWhereNotState(string $column, $states)
 * @method static Builder<static>|Admin orWhereState(string $column, $states)
 * @method static Builder<static>|Admin patients()
 * @method static Builder<static>|Admin permission($permissions, $without = false)
 * @method static Builder<static>|Admin role($roles, $guard = null, $without = false)
 * @method static Builder<static>|Admin whereCertifications($value)
 * @method static Builder<static>|Admin whereCity($value)
 * @method static Builder<static>|Admin whereCurrentTeamId($value)
 * @method static Builder<static>|Admin whereDeletedAt($value)
 * @method static Builder<static>|Admin whereDeletedBy($value)
 * @method static Builder<static>|Admin whereEmail($value)
 * @method static Builder<static>|Admin whereEmailVerifiedAt($value)
 * @method static Builder<static>|Admin whereFirstName($value)
 * @method static Builder<static>|Admin whereFullName($value)
 * @method static Builder<static>|Admin whereIsActive($value)
 * @method static Builder<static>|Admin whereIsOtp($value)
 * @method static Builder<static>|Admin whereLang($value)
 * @method static Builder<static>|Admin whereLastName($value)
 * @method static Builder<static>|Admin whereModerationData($value)
 * @method static Builder<static>|Admin whereName($value)
 * @method static Builder<static>|Admin whereNotState(string $column, $states)
 * @method static Builder<static>|Admin wherePassword($value)
 * @method static Builder<static>|Admin wherePasswordExpiresAt($value)
 * @method static Builder<static>|Admin whereProfilePhotoPath($value)
 * @method static Builder<static>|Admin whereRegistrationNumber($value)
 * @method static Builder<static>|Admin whereRememberToken($value)
 * @method static Builder<static>|Admin whereState($value)
 * @method static Builder<static>|Admin whereStatus($value)
 * @method static Builder<static>|Admin whereType($value)
 * @method static Builder<static>|Admin whereUuid($value)
 * @method static Builder<static>|Admin withoutPermission($permissions)
 * @method static Builder<static>|Admin withoutRole($roles, $guard = null)
 *
 * @property-read Collection<int, Device> $devices
 * @property-read int|null $devices_count
 * @property string|null $dental_problems
 * @property string|null $last_dental_visit
 * @property string|null $pregnancy_certificate
 * @property string|null $isee_certificate
 * @property string|null $identity_document
 * @property string|null $health_card
 * @property string|null $certificates
 * @property-read Collection<int, Membership> $teamUsers
 * @property-read int|null $team_users_count
 *
 * @method static Builder<static>|Admin whereCertificates($value)
 * @method static Builder<static>|Admin whereDentalProblems($value)
 * @method static Builder<static>|Admin whereHealthCard($value)
 * @method static Builder<static>|Admin whereIdentityDocument($value)
 * @method static Builder<static>|Admin whereIseeCertificate($value)
 * @method static Builder<static>|Admin whereLastDentalVisit($value)
 * @method static Builder<static>|Admin wherePregnancyCertificate($value)
 *
 * @property string|null $country_code
 * @property string|null $children_count
 * @property string|null $family_members
 * @property string|null $years_in_italy
 * @property string|null $nationality
 * @property string|null $fiscal_code
 * @property string|null $data_privacy_form
 * @property string|null $doctor_certificate
 * @property array<array-key, mixed>|null $certification
 * @property string|null $last_dental_visit_period
 *
 * @method static Builder<static>|Admin whereCertification($value)
 * @method static Builder<static>|Admin whereChildrenCount($value)
 * @method static Builder<static>|Admin whereCountryCode($value)
 * @method static Builder<static>|Admin whereDataPrivacyForm($value)
 * @method static Builder<static>|Admin whereDoctorCertificate($value)
 * @method static Builder<static>|Admin whereFamilyMembers($value)
 * @method static Builder<static>|Admin whereFiscalCode($value)
 * @method static Builder<static>|Admin whereLastDentalVisitPeriod($value)
 * @method static Builder<static>|Admin whereNationality($value)
 * @method static Builder<static>|Admin whereYearsInItaly($value)
 *
 * @property string|null $age_range
 *
 * @method static Builder<static>|Admin whereAgeRange($value)
 *
 * @property-read Collection<int, Tenant> $tenants
 * @property-read int|null $tenants_count
 *
 * @mixin \Eloquent
 */
class Admin extends User
{
    use HasParent;

    /**
     * Gli attributi che sono mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'date_of_birth',
        'gender',
        'address',
        'phone',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    #[Override]
    protected function casts(): array
    {
        return array_merge(
            parent::casts(),
            [
                // 'certifications' => 'array',
                // 'availability' => 'array',
            ],
        );
    }
}
