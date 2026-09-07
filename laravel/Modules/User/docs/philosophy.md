# User Module: Philosophy, Architecture & Zen

> **Identity & Authorization** — Who you are, what you can do, which tenant you belong to. User module is the backbone of Laraxot's security and multi-tenancy.

---

## 1. Philosophy & Zen

### Canonical Purpose

User module provides:
- **Authentication** (Passport OAuth, email/password, Socialite multi-provider)
- **Authorization** (Roles, Permissions via Spatie, Policies)
- **Identity** (User profile, device tracking, session management)
- **Multi-tenancy** (User → Teams → Tenants isolation)
- **Security** (2FA-ready, device verification, rate limiting)

**Why**: Every module depends on User for `auth()` context, authorization checks, tenant scoping.

### Zen

**"Identity is the anchor. Everything else hangs from it."**

User is not just a table; it's a security perimeter. Decisions here (auth flow, role structure, device trust) cascade through the entire system.

---

## 2. Architecture

### Core Models (50+ total)

| Model | Purpose |
|-------|---------|
| **BaseUser** | Foundation user (Passport, Spatie Permission, Teams, Tenants) |
| **User** | Concrete user (tenant-specific, extends BaseUser) |
| **BaseTeam** | Team (ownership, membership) |
| **BaseProfile** | User profile (bio, avatar, extra attributes) |
| **Device** | Device tracking (IP, user agent, last seen) |
| **DeviceVerification** | 2FA-ready device verification |
| **Authentication** | Auth session record (immutable log) |
| **AuthenticationLog** | Failed attempt log |
| **OauthClient**, **OauthToken** | Passport OAuth (API tokens) |
| **SocialiteUser** | OAuth provider links (Auth0, Microsoft, Google) |
| **Role**, **Permission** | Spatie RBAC (authorization) |
| **BaseUuidModel** | Variant base model using UUID instead of int ID |

### Auth Flow

```
Request
  ↓
Passport/Sanctum (guard)
  ↓
User Model (auth context)
  ↓
Policy/Gate (authorization)
  ↓
Tenant Scope (data isolation)
  ↓
Action (with user context)
```

### Multi-Tenancy Pattern

```
User
  ├─ currentTeam (active context)
  ├─ ownedTeams (user manages)
  ├─ membershipTeams (user belongs to)
  └─ tenants (via teams)

Team
  ├─ users (members)
  ├─ pivot TeamsUser (role within team)
  └─ tenant (owner)

Tenant (from Tenant module)
  ├─ users (all users with access)
  ├─ domain, database config
  └─ data isolation boundary
```

### Traits (Composition)

| Trait | What |
|-------|------|
| **HasApiTokens** | Passport tokens (OAuth) |
| **HasXotFactory** | Factory generation |
| **HasUuids** | Use UUID instead of int ID |
| **Notifiable** | Send notifications |
| **HasAuthentications** | Track auth events |
| **HasAuthenticationLogTrait** | Failed login tracking |
| **HasDevices** | Device management |
| **HasSocialite** | OAuth provider links |
| **HasSpatiePermission** | Roles & permissions |
| **HasTeams** | Team membership |
| **HasModules** | Module-level permissions |
| **InteractsWithMedia** | Avatar, media library |
| **HasChildren** | Polymorphic User inheritance |

### OAuth & Socialite

**Passport** (native Laravel OAuth):
- User → OauthClient (API credentials)
- OauthToken (refresh + access token)
- Use: API authentication, SPA backends

**Socialite** (social login):
- SocialiteUser (provider + provider ID)
- Providers: Auth0, Microsoft, Google, GitHub, Facebook
- Use: "Login with..." flows, SSO integration

### Roles & Permissions

**Spatie Permission**:
- Role (admin, editor, viewer, custom)
- Permission (create_post, delete_comment, etc.)
- User.hasRole('admin') / hasPermission('edit_post')
- Team-level roles (team-specific permissions)

**Policies**:
- Laravel Gate/Policy for fine-grained checks
- Tied to User + Resource (e.g., "Can user edit this post?")

---

## 3. Best/Bad Practices

### Best

1. **Passport + Sanctum hybrid**
   - Passport for long-lived API tokens (desktop apps, integrations)
   - Sanctum for SPA (short-lived, CSRF-protected)
   ```php
   $user->createToken('api', ['*'])->plainTextToken;
   ```

2. **Tenant scoping via user context**
   - Every query within action runs in user's current tenant
   - Query scope applied at middleware/ServiceProvider level
   - No manual `where('tenant_id', ...)` needed

3. **Device verification + 2FA ready**
   - DeviceVerification model (extends app to add MFA)
   - Device::track() logs all auth, can rate-limit per device
   ```php
   Device::where('user_id', $user->id)->where('ip', request()->ip())->update(['last_seen' => now()]);
   ```

4. **Profile extraction**
   - BaseProfile keeps user table lean (no nullable columns for avatar, bio)
   - 1:1 relationship, loaded eagerly where needed

5. **Immutable authentication log**
   - AuthenticationLog::create() records every auth event (success + failure)
   - Never deleted (compliance, forensics)

### Bad

1. **Storing OAuth tokens in cookies**
   ```php
   // ❌ WRONG
   cookie('oauth_token', $token->access_token)->httpOnly(false);
   
   // ✅ RIGHT
   // Use Passport guard, token stored server-side
   ```

2. **Bypassing Spatie Permission with custom policy**
   ```php
   // ❌ WRONG
   if ($user->id === 1) return true; // Hardcoded super-admin
   
   // ✅ RIGHT
   $user->hasRole('admin') && $user->hasPermission('action');
   ```

3. **Multi-team without scoping**
   ```php
   // ❌ WRONG
   User::all()->teams(); // Leaks all teams
   
   // ✅ RIGHT
   auth()->user()->currentTeam->users();
   ```

4. **Device verification optional**
   - If added later, existing sessions unverified
   - Approach: Gradual rollout with feature flag

### False Friends

1. **auth()->user() vs auth('api')->user()**
   - First: session guard (web)
   - Second: API guard (Passport token)
   - **Trap**: Switching guards in middleware breaks context

2. **Role vs Permission ambiguity**
   - Role: container of permissions (one user = multiple roles)
   - Permission: atomic action (one permission = many roles)
   - **Trap**: Assigning permissions directly to user (works, but unmaintainable)

3. **currentTeam() magic**
   ```php
   // ⚠️ currentTeam is context, not loaded by default
   auth()->user()->currentTeam; // May be null if not set
   auth()->user()->load('currentTeam')->currentTeam; // Safe
   ```

4. **Device.user_id vs Device.ip**
   - user_id: owner of the device
   - ip: client IP at last login (not unique, VPN/proxy spoofs it)
   - **Trap**: Using IP alone for 2FA (unreliable)

---

## 4. Integration

### Who Uses User

**Every module**:
- Auth context in policies
- Tenant scoping
- Audit trail (created_by, updated_by)
- Notifications (send to users)

### Reverse Dependencies

- **Tenant module** (scoping context)
- **Notify module** (send notifications to users)
- **Activity module** (track who changed what)
- **Geo module** (user location/address)
- **Media module** (user avatar)

---

## 5. Security Audit

### Built-In

✓ Passport rate limiting (OAuth guard)
✓ Authentication log (immutable)
✓ Device tracking (IP, user agent)
✓ Spatie Permission (fine-grained ACL)
✓ Tenant isolation (currentTeam scope)

### Gaps (to address)

- [ ] 2FA implementation (DeviceVerification ready, not implemented)
- [ ] Rate limiting on login attempts (AuthenticationLog tracked, not enforced)
- [ ] Cross-tenant CSRF token validation
- [ ] Session hijacking detection (compare user agent / IP change)

---

## 6. Roadmap

1. **Multi-Factor Authentication (MFA)**
   - TOTP (Google Authenticator) + SMS backup codes
   - Leverage DeviceVerification model

2. **Single Sign-On (SSO)**
   - SAML/OpenID Connect support
   - Extend SocialiteUser model

3. **Passwordless Auth**
   - Magic link / email-based login
   - 2FA-aware

4. **Session Management UI**
   - User sees active devices
   - Revoke sessions remotely

5. **Audit Trail Dashboard**
   - Authentication log visualization
   - Failed login alerts

---

## 7. Installation & Setup

```bash
# Already in monorepo
cd laravel/
composer install

# Run migrations (74 total)
php artisan migrate --path="Modules/User/database/migrations"

# Seed default roles/permissions
php artisan db:seed UserSeeder

# Publish config
php artisan vendor:publish --provider="Modules\User\Providers\UserServiceProvider"
```

### Quick Start: Protect a Route

```php
// routes/web.php
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', DashboardAction::class);
});

// Filament resource (auto-protected)
class UserResource extends XotBaseResource {
    // ...
}
```

---

## 8. Summary Card

```
┌──────────────────────────────────────────────┐
│ MODULE: User (Identity & Authorization)      │
├──────────────────────────────────────────────┤
│ Purpose: Auth, roles, teams, multi-tenancy   │
│ Owner: laravel/Modules/User/                 │
│ Status: Stable (production)                  │
│ PHPStan: Level 10 target                     │
│ Models: 50+ (104 files total)                │
│ Migrations: 74                               │
│ Dependencies: Xot, Tenant, UI                │
│ Reverse Deps: All other modules              │
│ Complexity: High (security-critical)         │
└──────────────────────────────────────────────┘
```

