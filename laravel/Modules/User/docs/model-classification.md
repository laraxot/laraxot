<<<<<<< HEAD
<<<<<<< HEAD
---
module: theme
topic: model-classification
canonical: ../../../Themes/docs/shared-components/model-classification-Modules.md
related:
  - "./00-index-1.md"
  - "./00-index.md"
  - "./2fa-guide.md"
  - "./2fa.md"
  - "./accessor-delegation-pattern.md"
  - "./actions-path-convention-1.md"
  - "./actions-path-convention-2.md"
  - "./actions-path-convention.md"
---

See canonical documentation: ../../../Themes/docs/shared-components/model-classification-Modules.md
=======
=======
>>>>>>> 87273113 (.)
# User Module - Model Classification

## Business-Relevant Models (Require Factories/Seeders)

### ✅ Core User Management
- **User** - System users
- **Team** - User teams/groups
- **Role** - User roles
- **Permission** - System permissions
- **Profile** - User profiles
- **Tenant** - Multi-tenancy tenants
- **Device** - User devices
- **AuthenticationLog** - Authentication history

### 🔗 Relationship Entities
- **TeamUser** - Team-User membership
- **ModelHasPermission** - Model-permission assignments
- **RoleHasPermission** - Role-permission assignments  
- **ModelHasRole** - Model-role assignments
- **PermissionRole** - Permission-role relationships
- **PermissionUser** - Permission-user assignments
- **ProfileTeam** - Profile-team relationships
- **TeamInvitation** - Team invitations
- **TeamPermission** - Team-permission assignments
- **TenantUser** - Tenant-user assignments
- **Membership** - Organization memberships

## Infrastructure & Base Classes (No Factories/Seeders Needed)

### 🏗️ Base Classes
- **BaseUuidModel** - Base UUID model
- **BaseUser** - Base user class
- **BaseTeam** - Base team class
- **BaseTeamUser** - Base team-user class
- **BaseIsTenant** - Base tenant class
- **BasePivot** - Base pivot model
- **BaseMorphPivot** - Base polymorphic pivot
- **BaseProfile** - Base profile class
- **BaseTenant** - Base tenant class
- **BaseInteractsWithExtra** - Base extra data class

### 🔧 Traits
- **BaseInteractsWithTenant** - Tenant interaction trait
- **HasAuthenticationLogTrait** - Authentication logging trait
- **HasTenants** - Multi-tenancy trait
- **HasPasswordExpiry** - Password expiration trait
- **IsProfileTrait** - Profile functionality trait
- **HasTeams** - Team management trait
- **HasRoles** - Role management trait
- **InteractsWithTenant** - Tenant interaction trait
- **IsTenant** - Tenant identification trait
- **TenantScope** - Tenant query scope

### 🛡️ Policy Classes
- **TeamPolicy** - Team authorization
- **UserBasePolicy** - Base user policy
- **PermissionPolicy** - Permission authorization
- **RolePolicy** - Role authorization
- **UserPolicy** - User authorization

### 🔐 OAuth Models (If Using OAuth)
- **OauthAuthCode** - OAuth authorization codes
- **OauthClient** - OAuth clients
- **OauthAccessToken** - OAuth access tokens
- **OauthRefreshToken** - OAuth refresh tokens
- **OauthPersonalAccessClient** - OAuth personal access clients
- **SocialProvider** - Social authentication providers
- **SocialiteUser** - Social media users

### 📊 Infrastructure Models
- **Extra** - Additional user data
- **DeviceProfile** - Device profiles
- **Authentication** - Authentication records
- **Feature** - Feature flags
- **PasswordReset** - Password reset tokens
- **Notification** - User notifications

## Recommendations
- Focus factories/seeders on core user management entities
- Base classes and traits should not have factories
- Policy classes are for authorization only
- OAuth models only need factories if using OAuth authentication
- Evaluate if all infrastructure models are actually used
- Consider removing unused OAuth models if not using OAuth
<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
---
module: theme
topic: model-classification
canonical: ../../../Themes/docs/shared-components/model-classification-Modules.md
related:
  - "./00-index-1.md"
  - "./00-index.md"
  - "./2fa-guide.md"
  - "./2fa.md"
  - "./accessor-delegation-pattern.md"
  - "./actions-path-convention-1.md"
  - "./actions-path-convention-2.md"
  - "./actions-path-convention.md"
---

See canonical documentation: ../../../Themes/docs/shared-components/model-classification-Modules.md
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
