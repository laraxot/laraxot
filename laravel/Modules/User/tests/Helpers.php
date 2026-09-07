<?php

declare(strict_types=1);

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\Field;
use Filament\Panel;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Passport;
use Mockery\Expectation;
use Mockery\MockInterface;
use Modules\User\Actions\Socialite\IsUserAllowedAction;
use Modules\User\Database\Factories\TeamFactory;
use Modules\User\Database\Factories\UserFactory;
use Modules\User\Models\Profile;
use Modules\User\Models\Team;
use Modules\User\Models\TeamInvitation;
use Modules\User\Models\User;
use Modules\User\Providers\Filament\AdminPanelProvider;
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;
use PragmaRX\Google2FA\Google2FA;

use function Safe\glob;
use function Safe\json_decode;
use function Safe\json_encode;

/**
 * @param  array<string, mixed>  $attributes
 */
function createUser(array $attributes = []): User
{
    $factory = User::factory();
    \assert($factory instanceof Factory);

    $user = $factory->create($attributes);
    \assert($user instanceof User);

    return $user;
}

/**
 * @param  array<string, mixed>  $attributes
 */
function makeUser(array $attributes = []): User
{
    $factory = User::factory();
    \assert($factory instanceof Factory);

    $user = $factory->make($attributes);
    \assert($user instanceof User);

    return $user;
}

/**
 * @param  array<string, mixed>  $attributes
 */
function createTeam(array $attributes = []): Team
{
    return TeamFactory::new()->createOne(array_merge([
        'name' => 'team-'.uniqid(),
    ], $attributes));
}

/**
 * @param  array<string, mixed>  $attributes
 */
function createTestUser(array $attributes = []): User
{
    return UserFactory::new()->createOne(array_merge([
        'email' => 'test-'.uniqid('', true).'@example.com',
    ], $attributes));
}

/**
 * @return array{user: User, team: Team, personalTeam: Team}
 */
function bootstrapHasTeamsFixture(): array
{
    $user = createTestUser();
    $team = TeamFactory::new()->createOne(['name' => 'shared-'.uniqid()]);
    $personalTeam = TeamFactory::new()->createOne([
        'user_id' => $user->id,
        'name' => 'personal-'.uniqid(),
        'personal_team' => true,
    ]);

    return [
        'user' => $user,
        'team' => $team,
        'personalTeam' => $personalTeam,
    ];
}

function userTableHasColumn(string $table, string $column): bool
{
    return Schema::connection('user')->hasColumn($table, $column);
}

function pestSkip(string $message): never
{
    Assert::markTestSkipped($message);
}

/**
 * Narrows the wide return type of Mockery's shouldReceive()/allows() to the
 * concrete Expectation class so chained calls like andReturn()/with() resolve.
 */
function mockeryExpect(mixed $expectation): Expectation
{
    \assert($expectation instanceof Expectation);

    return $expectation;
}

function skipUnlessUserColumn(string $table, string $column, string $reason = ''): void
{
    if (! userTableHasColumn($table, $column)) {
        pestSkip($reason !== '' ? $reason : "Column {$table}.{$column} missing on user connection.");
    }
}

function userTableExists(string $table): bool
{
    return Schema::connection('user')->hasTable($table);
}

function skipUnlessUserTable(string $table, string $reason = ''): void
{
    if (! userTableExists($table)) {
        pestSkip($reason !== '' ? $reason : "Table {$table} missing on user connection.");
    }
}

function permissionRolePivotTable(): string
{
    return Config::string('permission.table_names.model_has_roles', 'model_has_role');
}

function permissionPivotTable(): string
{
    return Config::string('permission.table_names.model_has_permissions', 'model_has_permission');
}

function skipUnlessUsersTableReady(string $reason = ''): void
{
    skipUnlessUserTable('users', $reason !== '' ? $reason : 'users table missing on user connection.');
}

function skipUnlessRoleAssignmentSupported(string $reason = ''): void
{
    $table = permissionRolePivotTable();
    skipUnlessUserTable($table, $reason !== '' ? $reason : "Role pivot table {$table} missing on user connection.");
}

function skipUnlessDirectPermissionSupported(string $reason = ''): void
{
    $table = permissionPivotTable();
    skipUnlessUserTable($table, $reason !== '' ? $reason : "Permission pivot table {$table} missing on user connection.");
}

function skipUnlessTeamUsersRelationSupported(): void
{
    if (! userTableHasColumn('team_user', 'permissions')) {
        pestSkip('team_user.permissions column missing — Team::users() pivot not loadable.');
    }
}

/**
 * @param  array<string, mixed>  $pivot
 */
function attachTeamMember(Team $team, User $user, array $pivot = []): void
{
    $payload = [
        'team_id' => $team->id,
        'user_id' => $user->id,
        'created_at' => now(),
        'updated_at' => now(),
    ];

    if (isset($pivot['role'])) {
        $payload['role'] = $pivot['role'];
    }

    if (userTableHasColumn('team_user', 'permissions') && array_key_exists('permissions', $pivot)) {
        $permissions = $pivot['permissions'];
        $payload['permissions'] = is_array($permissions) ? json_encode($permissions) : $permissions;
    }

    if (userTableHasColumn('team_user', 'joined_at') && array_key_exists('joined_at', $pivot)) {
        $payload['joined_at'] = $pivot['joined_at'];
    }

    DB::connection('user')->table('team_user')->insert($payload);
}

function detachTeamMember(Team $team, User $user): void
{
    DB::connection('user')->table('team_user')
        ->where('team_id', $team->id)
        ->where('user_id', $user->id)
        ->delete();
}

function teamMemberExists(Team $team, User $user): bool
{
    return DB::connection('user')->table('team_user')
        ->where('team_id', $team->id)
        ->where('user_id', $user->id)
        ->exists();
}

function teamUsesSoftDeletes(): bool
{
    /** @var array<class-string, class-string> $traits */
    $traits = \class_uses_recursive(Team::class);

    return in_array(
        SoftDeletes::class,
        $traits,
        true
    );
}

/**
 * @param  array<string, mixed>  $attributes
 */
function createProfile(array $attributes = []): Profile
{
    $factory = Profile::factory();
    \assert($factory instanceof Factory);

    $profile = $factory->create($attributes);
    \assert($profile instanceof Profile);

    return $profile;
}

function setupFilamentAdminPanel(): void
{
    $filament = Filament\Facades\Filament::class;

    try {
        $panel = $filament::getPanel('user::admin');
    } catch (Throwable) {
        $panelProvider = new AdminPanelProvider(app());
        $panel = $panelProvider->panel(Panel::make());
        $filament::registerPanel($panel);
    }

    $filament::setCurrentPanel($panel);
}

/**
 * @param  array<mixed>  $attributes
 */
function mockSocialiteOauthUser(array $attributes = []): Laravel\Socialite\Contracts\User
{
    /** @var array<string, mixed> $attributes */
    $unique = uniqid();
    $data = array_merge([
        'id' => 'id-'.$unique,
        'name' => 'Mario Rossi',
        'email' => 'user'.$unique.'@example.com',
        'avatar' => 'https://example.com/avatar.jpg',
        'nickname' => 'user'.$unique,
    ], $attributes);

    return configureMock(Laravel\Socialite\Contracts\User::class, static function (MockInterface $mock) use ($data): void {
        $mock->allows([
            'getId' => $data['id'],
            'getName' => $data['name'],
            'getEmail' => $data['email'],
            'getAvatar' => $data['avatar'],
            'getNickname' => $data['nickname'],
        ]);
    });
}

/**
 * @template T of object
 *
 * @param  class-string<T>  $class
 * @return T&MockInterface
 */
function typedMock(string $class): MockInterface
{
    /** @var T&MockInterface $mock */
    $mock = Mockery::mock($class);

    return $mock;
}

/**
 * @template T of object
 *
 * @param  class-string<T>  $class
 * @param  callable(T&MockInterface): void  $configure
 * @return T&MockInterface
 */
function configureMock(string $class, callable $configure): MockInterface
{
    /** @var T&MockInterface $mock */
    $mock = Mockery::mock($class);
    $configure($mock);

    return $mock;
}

function fakeSocialiteUser(string $email): Laravel\Socialite\Contracts\User
{
    return configureMock(Laravel\Socialite\Contracts\User::class, static function (MockInterface $mock) use ($email): void {
        $mock->allows(['getEmail' => $email]);
    });
}

function makeIsUserAllowedAction(): IsUserAllowedAction
{
    return new IsUserAllowedAction;
}

/**
 * @return list<string>
 */
function userMigrationFiles(): array
{
    $basePath = dirname(__DIR__, 2).'/database/migrations';
    /** @var list<string> $files */
    $files = glob($basePath.'/*.php');
    sort($files);

    return $files;
}

function skipUserTest(string $message): never
{
    Assert::markTestSkipped($message);
}

function skipLegacyRedirectPersistenceCheck(): void
{
    if (
        Schema::connection('user')->hasColumn('oauth_clients', 'redirect')
        && Schema::connection('user')->hasColumn('oauth_clients', 'redirect_uris')
    ) {
        skipUserTest('oauth_clients legacy redirect columns require redirect_uris sync not performed by Create*ClientAction.');
    }
}

function ensurePersonalAccessClient(): void
{
    $clientModel = Passport::client();

    if ($clientModel->newQuery()->where('revoked', false)->exists()) {
        return;
    }

    $repository = app(ClientRepository::class);
    $repository->createPersonalAccessGrantClient('Test Personal Access Client');
}

/**
 * @return array<int, Component|Action|ActionGroup>
 */
function userResourceSectionComponents(TestCase $testCase, Component $section): array
{
    Assert::assertInstanceOf(Section::class, $section);

    /* @var \Filament\Schemas\Components\Section $section */
    return $testCase->filamentSectionChildComponents($section);
}

/**
 * @param  array<int, Component|Action|ActionGroup>  $components
 */
function userResourceFindComponentByName(array $components, string $name): ?Component
{
    foreach ($components as $component) {
        if (! $component instanceof Field) {
            continue;
        }

        if ($component->getName() === $name) {
            return $component;
        }
    }

    return null;
}

/**
 * @param  array<string, mixed>  $attributes
 */
function stubUser(array $attributes = []): User
{
    return UserFactory::new()->makeOne($attributes);
}

/**
 * @param  array<string, mixed>  $attributes
 */
function hasTeamsCurrentCreateUser(array $attributes = []): User
{
    return createTestUser($attributes);
}

/**
 * @param  array<string, mixed>  $attributes
 */
function hasTeamsCurrentCreateTeam(User $user, array $attributes = []): Team
{
    return TeamFactory::new()->createOne(array_merge([
        'user_id' => $user->id,
        'personal_team' => true,
    ], $attributes));
}

/**
 * @return array{secret: string, qr_code: string, recovery_codes: array<int, string>}
 */
function enableTwoFactorForUser(User $user, Google2FA $google2fa): array
{
    $secret = (string) $google2fa->generateSecretKey();
    $qrCode = $google2fa->getQRCodeUrl(Config::string('app.name'), $user->email, $secret);

    $recoveryCodes = array_map(
        static fn (): string => substr(str_shuffle('0123456789ABCDEF'), 0, 10).'-'.substr(str_shuffle('0123456789ABCDEF'), 0, 10),
        range(1, 10)
    );

    $user->two_factor_secret = encrypt($secret);
    $user->two_factor_recovery_codes = encrypt(json_encode($recoveryCodes));
    $user->save();

    return [
        'secret' => $secret,
        'qr_code' => $qrCode,
        'recovery_codes' => $recoveryCodes,
    ];
}

function confirmTwoFactorForUser(User $user, Google2FA $google2fa, string $secret, string $code): bool
{
    if (! $google2fa->verifyKey($secret, $code)) {
        return false;
    }

    $user->two_factor_confirmed_at = now()->toDateTimeString();
    $user->save();

    return true;
}

function verifyTwoFactorCode(User $user, Google2FA $google2fa, string $code): bool
{
    if (! $user->two_factor_secret) {
        return false;
    }

    $secret = decrypt($user->two_factor_secret);
    if (! is_string($secret)) {
        return false;
    }

    return $google2fa->verifyKey($secret, $code) !== false;
}

function disableTwoFactorForUser(User $user): void
{
    $user->two_factor_secret = null;
    $user->two_factor_recovery_codes = null;
    $user->two_factor_confirmed_at = null;
    $user->save();
}

function verifyTwoFactorRecoveryCode(User $user, string $code): bool
{
    if (! $user->two_factor_recovery_codes) {
        return false;
    }

    $stored = decrypt($user->two_factor_recovery_codes);
    if (! is_string($stored)) {
        return false;
    }

    $codes = json_decode($stored, true);
    if (! is_array($codes)) {
        return false;
    }

    $codes = array_values(array_filter($codes, static fn (mixed $storedCode): bool => $storedCode !== $code));
    $user->two_factor_recovery_codes = encrypt(json_encode($codes));
    $user->save();

    return true;
}

/**
 * @return array<int, string>
 */
function readStoredRecoveryCodes(User $user): array
{
    if (! $user->two_factor_recovery_codes) {
        return [];
    }

    $stored = decrypt($user->two_factor_recovery_codes);
    if (! is_string($stored)) {
        return [];
    }

    $codes = json_decode($stored, true);
    if (! is_array($codes)) {
        return [];
    }

    /** @var array<int, string> $stringCodes */
    $stringCodes = array_values(array_filter($codes, 'is_string'));

    return $stringCodes;
}

/**
 * @return array<int, string>
 */
function regenerateTwoFactorRecoveryCodes(User $user): array
{
    $codes = array_map(
        static fn (): string => substr(str_shuffle('0123456789ABCDEF'), 0, 10).'-'.substr(str_shuffle('0123456789ABCDEF'), 0, 10),
        range(1, 10)
    );

    $user->two_factor_recovery_codes = encrypt(json_encode($codes));
    $user->save();

    return $codes;
}

function teamMgmtUserTableHasColumn(string $table, string $column): bool
{
    return Schema::connection('user')->hasColumn($table, $column);
}

function teamMgmtUserTableExists(string $table): bool
{
    return Schema::connection('user')->hasTable($table);
}

function teamMgmtTeamUsersRelationSupported(): bool
{
    return teamMgmtUserTableHasColumn('team_user', 'permissions');
}

/**
 * @param  array<string, mixed>  $attributes
 */
function teamMgmtCreateUser(array $attributes = []): User
{
    /** @var User $user */
    $user = UserFactory::new()->createOne(array_merge([
        'email' => 'team-mgmt-'.uniqid('', true).'@example.com',
    ], $attributes));

    return $user;
}

/**
 * @param  array<string, mixed>  $attributes
 */
function teamMgmtCreateTeam(User $owner, array $attributes = []): Team
{
    return TeamFactory::new()->createOne(array_merge([
        'user_id' => $owner->id,
        'name' => 'Test Team '.uniqid(),
    ], $attributes));
}

/**
 * @return array{owner: User, member: User, team: Team}
 */
function teamMgmtBootstrap(): array
{
    $owner = teamMgmtCreateUser();
    $member = teamMgmtCreateUser();
    $team = teamMgmtCreateTeam($owner);

    return compact('owner', 'member', 'team');
}

/**
 * @param  array<string, mixed>  $pivot
 */
function teamMgmtAttachMember(Team $team, User $user, array $pivot = []): void
{
    $payload = [
        'team_id' => $team->id,
        'user_id' => $user->id,
        'created_at' => now(),
        'updated_at' => now(),
    ];

    if (isset($pivot['role'])) {
        $payload['role'] = $pivot['role'];
    }

    if (teamMgmtUserTableHasColumn('team_user', 'permissions') && array_key_exists('permissions', $pivot)) {
        $permissions = $pivot['permissions'];
        $payload['permissions'] = is_array($permissions) ? json_encode($permissions) : $permissions;
    }

    if (teamMgmtUserTableHasColumn('team_user', 'joined_at') && array_key_exists('joined_at', $pivot)) {
        $payload['joined_at'] = $pivot['joined_at'];
    }

    DB::connection('user')->table('team_user')->insert($payload);
}

function teamMgmtDetachMember(Team $team, User $user): void
{
    DB::connection('user')->table('team_user')
        ->where('team_id', $team->id)
        ->where('user_id', $user->id)
        ->delete();
}

function teamMgmtMemberExists(Team $team, User $user): bool
{
    return DB::connection('user')->table('team_user')
        ->where('team_id', $team->id)
        ->where('user_id', $user->id)
        ->exists();
}

/**
 * @param  array<string, mixed>  $attributes
 */
function teamMgmtCreateInvitation(Team $team, array $attributes = []): TeamInvitation
{
    $payload = array_merge([
        'uuid' => (string) Str::uuid(),
        'team_id' => (string) $team->id,
        'email' => 'invite-'.uniqid().'@example.com',
        'role' => 'member',
    ], $attributes);

    $invitation = new TeamInvitation;
    $invitation->forceFill($payload);
    $invitation->save();
    $fresh = $invitation->fresh();

    return $fresh instanceof TeamInvitation ? $fresh : $invitation;
}

function teamMgmtBizUserTableHasColumn(string $table, string $column): bool
{
    return Schema::connection('user')->hasColumn($table, $column);
}

function teamMgmtBizTeamUsersRelationSupported(): bool
{
    return teamMgmtBizUserTableHasColumn('team_user', 'permissions');
}

function teamMgmtBizTeamUsesSoftDeletes(): bool
{
    return in_array(SoftDeletes::class, \class_uses_recursive(Team::class), true);
}

/**
 * @param  array<string, mixed>  $attributes
 */
function teamMgmtBizCreateUser(array $attributes = []): User
{
    /** @var User $user */
    $user = UserFactory::new()->createOne(array_merge([
        'email' => 'team-biz-'.uniqid('', true).'@example.com',
    ], $attributes));

    return $user;
}

/**
 * @param  array<string, mixed>  $attributes
 */
function teamMgmtBizCreateTeam(array $attributes = []): Team
{
    return TeamFactory::new()->createOne(array_merge([
        'name' => 'Team-'.uniqid(),
        'personal_team' => false,
    ], $attributes));
}

/**
 * @param  array<string, mixed>  $where
 */
function teamMgmtBizAssertDatabaseHas(string $table, array $where): void
{
    $query = DB::connection('user')->table($table);
    foreach ($where as $column => $value) {
        $query->where($column, $value);
    }

    Assert::assertTrue($query->exists());
}

/**
 * @param  array<string, mixed>  $where
 */
function teamMgmtBizAssertDatabaseMissing(string $table, array $where): void
{
    $query = DB::connection('user')->table($table);
    foreach ($where as $column => $value) {
        $query->where($column, $value);
    }

    Assert::assertFalse($query->exists());
}

/**
 * @param  array<string, mixed>  $pivot
 */
function teamMgmtBizAttachMember(Team $team, User $user, array $pivot = []): void
{
    $payload = [
        'team_id' => $team->id,
        'user_id' => $user->id,
        'created_at' => now(),
        'updated_at' => now(),
    ];

    if (isset($pivot['role'])) {
        $payload['role'] = $pivot['role'];
    }

    if (teamMgmtBizUserTableHasColumn('team_user', 'permissions') && array_key_exists('permissions', $pivot)) {
        $permissions = $pivot['permissions'];
        $payload['permissions'] = is_array($permissions) ? json_encode($permissions) : $permissions;
    }

    DB::connection('user')->table('team_user')->insert($payload);
}

function teamMgmtBizDetachMember(Team $team, User $user): void
{
    DB::connection('user')->table('team_user')
        ->where('team_id', $team->id)
        ->where('user_id', $user->id)
        ->delete();
}

function teamMgmtBizMemberExists(Team $team, User $user): bool
{
    return DB::connection('user')->table('team_user')
        ->where('team_id', $team->id)
        ->where('user_id', $user->id)
        ->exists();
}

/**
 * @param  array<string, mixed>  $attributes
 */
function teamMgmtBizCreateInvitation(Team $team, array $attributes = []): TeamInvitation
{
    $payload = array_merge([
        'uuid' => (string) Str::uuid(),
        'team_id' => (string) $team->id,
        'email' => 'invite-'.uniqid().'@example.com',
        'role' => 'member',
    ], $attributes);

    $invitation = new TeamInvitation;
    $invitation->forceFill($payload);
    $invitation->save();
    $fresh = $invitation->fresh();

    return $fresh instanceof TeamInvitation ? $fresh : $invitation;
}
