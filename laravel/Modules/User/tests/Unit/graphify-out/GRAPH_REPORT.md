# Graph Report - laravel/Modules/User/tests/Unit  (2026-08-20)

## Corpus Check
- 109 files · ~27,054 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 242 nodes · 288 edges · 59 communities (51 shown, 8 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `eea9ff2f`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- Modules\User\Models\User
- Laravel\Socialite\Contracts\User
- MockUserWithTeams
- UserExecuteCoverage50Test.php
- Modules\User\Models\Tenant
- Modules\User\Models\Permission
- Modules\User\Models\BaseUser
- GetCurrentDeviceActionTest.php
- Modules\User\Traits\PasswordValidationRules
- Modules\User\Datas\PasswordData
- Modules\Xot\Contracts\UserContract
- HasShieldPermissionsFixture
- DeletableAccessTokenFixture
- Modules\User\Console\Commands\ChangeTypeCommand
- LoginComponentTest.php
- HasRolesTraitFixture.php
- HasUserTestCaseFixture.php
- Modules\Xot\Tests\CreatesApplication
- FilamentShieldStubFixture

## God Nodes (most connected - your core abstractions)
1. `MockUserWithTeams` - 10 edges
2. `MockUserWithTeams` - 5 edges
3. `AdminPanelAccessUserFixture` - 4 edges
4. `HasShieldPermissionsFixture` - 4 edges
5. `createMockSocialiteUserForDomain()` - 3 edges
6. `DeletableAccessTokenFixture` - 3 edges
7. `createMockSocialiteUser()` - 3 edges
8. `__construct()` - 3 edges
9. `createMockSocialiteUserForEmailAnalyzer()` - 3 edges
10. `userNameFieldsResolverMock()` - 3 edges

## Surprising Connections (you probably didn't know these)
- `traitsHasTeamsMockUser()` --references--> `MockUserWithTeams`  [EXTRACTED]
  laravel/Modules/User/tests/Unit/Models/Traits/HasTeamsTest.php → laravel/Modules/User/tests/Unit/Models/Traits/Fixtures/MockUserWithTeams.php

## Import Cycles
- None detected.

## Communities (59 total, 8 thin omitted)

### Community 0 - "Modules\User\Models\User"
Cohesion: 0.09
Nodes (19): currentTeamFixCreateTeam(), currentTeamFixCreateUser(), pestHasTeamsAttachMember(), pestHasTeamsBootstrapFixture(), pestHasTeamsCreateTestUser(), hasTeamsAttachMember(), hasTeamsBootstrapFixture(), hasTeamsCreateTestUser() (+11 more)

### Community 1 - "Laravel\Socialite\Contracts\User"
Cohesion: 0.12
Nodes (15): createMockSocialiteUserForDomain(), SocialiteUser, createMockSocialiteUser(), SocialiteUser, __construct(), user(), createMockSocialiteUserForEmailAnalyzer(), SocialiteUser (+7 more)

### Community 2 - "MockUserWithTeams"
Cohesion: 0.17
Nodes (7): Illuminate\Database\Eloquent\Model, Illuminate\Database\Eloquent\Relations\BelongsToMany, MockUserWithTeams, traitsHasTeamsMockUser(), MockUserWithTeams, Modules\User\Models\Traits\HasTeams, Modules\Xot\Models\Traits\RelationX

### Community 3 - "UserExecuteCoverage50Test.php"
Cohesion: 0.14
Nodes (10): Filament\Schemas\Components\Component, Filament\Schemas\Components\Section, Illuminate\Support\ServiceProvider, Modules\User\Providers\Traits\HasPassportConfiguration, HasPassportConfigurationFixture, User, userFindNamedComponent(), userMockWithTeams() (+2 more)

### Community 4 - "Modules\User\Models\Tenant"
Cohesion: 0.16
Nodes (7): modelsProfileCreate(), Modules\User\Models\AuthenticationLog, Modules\User\Models\Profile, Modules\User\Models\Tenant, Profile, createPersistedTenant(), makeAuthenticationLogFor()

### Community 5 - "Modules\User\Models\Permission"
Cohesion: 0.27
Nodes (8): modelsPermissionCreate(), modelsRoleCreate(), Modules\User\Models\Permission, Modules\User\Models\Role, createTestPermission(), createTestRoleForPermission(), createTestPermissionForRole(), createTestRole()

### Community 6 - "Modules\User\Models\BaseUser"
Cohesion: 0.24
Nodes (4): AdminPanelAccessUserFixture, Illuminate\Foundation\Auth\User, TestBaseUser, Modules\User\Models\BaseUser

### Community 7 - "GetCurrentDeviceActionTest.php"
Cohesion: 0.36
Nodes (5): assertDeviceMatches(), bindFakeAgent(), modelsDeviceCreate(), Modules\User\Models\Device, Modules\User\Tests\Fakes\FakeAgent

### Community 8 - "Modules\User\Traits\PasswordValidationRules"
Cohesion: 0.32
Nodes (3): Modules\User\Traits\PasswordValidationRules, PasswordValidationRulesFixture, PasswordValidationRulesMockableFixture

### Community 11 - "Modules\Xot\Contracts\UserContract"
Cohesion: 0.50
Nodes (3): Modules\Xot\Contracts\UserContract, UserContract, userBehaviorUser()

### Community 16 - "HasRolesTraitFixture.php"
Cohesion: 0.83
Nodes (3): Modules\User\Models\Traits\HasRoles, Modules\Xot\Models\BaseModel, HasRolesTraitFixture

## Knowledge Gaps
- **8 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Should `Modules\User\Models\User` be split into smaller, more focused modules?**
  _Cohesion score 0.08819345661450925 - nodes in this community are weakly interconnected._
- **Should `Laravel\Socialite\Contracts\User` be split into smaller, more focused modules?**
  _Cohesion score 0.12121212121212122 - nodes in this community are weakly interconnected._
- **Should `UserExecuteCoverage50Test.php` be split into smaller, more focused modules?**
  _Cohesion score 0.13725490196078433 - nodes in this community are weakly interconnected._