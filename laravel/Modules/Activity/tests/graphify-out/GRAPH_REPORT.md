# Graph Report - tests  (2026-08-19)

## Corpus Check
- 100 files · ~21,478 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 181 nodes · 281 edges · 13 communities (12 shown, 1 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `1af60fc2`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- CanPaginateHarness
- Illuminate\Database\Eloquent\Model
- Modules\Xot\Filament\Resources\XotBaseResource
- Modules\Activity\Models\Activity
- Modules\User\Models\User
- TestCase
- Modules\Activity\Models\BaseModel
- ListLogActivitiesPageCoverageTest.php

## God Nodes (most connected - your core abstractions)
1. `CanPaginateHarness` - 14 edges
2. `TestCase` - 10 edges
3. `ListLogActivitiesActionTestPage` - 4 edges
4. `ListLogActivitiesActionTestResource` - 4 edges
5. `ListLogActivitiesActionTestResourceSimple` - 4 edges
6. `TestActivityModel` - 3 edges
7. `HasEventsDummyModel` - 3 edges
8. `activityFakeUser()` - 3 edges
9. `makePaginateQueryMock()` - 3 edges
10. `getPaginationMode()` - 3 edges

## Surprising Connections (you probably didn't know these)
- `makeCanPaginateHarness()` --references--> `CanPaginateHarness`  [EXTRACTED]
  Unit/CanPaginateCoverageTest.php → Fixtures/CanPaginateHarness.php

## Import Cycles
- None detected.

## Communities (13 total, 1 thin omitted)

### Community 1 - "CanPaginateHarness"
Cohesion: 0.11
Nodes (12): Filament\Tables\Enums\PaginationMode, CanPaginateHarness, Illuminate\Contracts\Pagination\CursorPaginator, Illuminate\Contracts\Pagination\Paginator, Illuminate\Database\Eloquent\Builder, Illuminate\Pagination\LengthAwarePaginator, Modules\Activity\Filament\Pages\Concerns\CanPaginate, makeCanPaginateHarness() (+4 more)

### Community 2 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.10
Nodes (8): HasEventsDummyModel, ListLogActivitiesActionTestRecord, LogActivityActionTestModel, LogModelCreatedActionTestModel, LogModelDeletedActionTestModel, LogModelUpdatedActionTestModel, Illuminate\Database\Eloquent\Model, Modules\Activity\Traits\HasEvents

### Community 3 - "Modules\Xot\Filament\Resources\XotBaseResource"
Cohesion: 0.15
Nodes (6): ListLogActivitiesActionTestPage, ListLogActivitiesActionTestResource, ListLogActivitiesActionTestResourceSimple, Modules\Xot\Filament\Resources\Pages\XotBaseListRecords, Modules\Xot\Filament\Resources\XotBaseResource, self

### Community 5 - "Modules\User\Models\User"
Cohesion: 0.22
Nodes (8): createActionsTestUser(), Modules\User\Models\User, activityCreateActivity(), activityCreateUser(), createActivityLifecycleUser(), policyBefore(), activityFakeUser(), User

### Community 6 - "TestCase"
Cohesion: 0.21
Nodes (6): Illuminate\Foundation\Application, Illuminate\Foundation\Testing\DatabaseTransactions, Modules\Activity\Filament\Pages\ListLogActivities, Modules\Xot\Tests\XotBaseTestCase, TestCase, makeListLogActivitiesPage()

### Community 7 - "Modules\Activity\Models\BaseModel"
Cohesion: 0.20
Nodes (3): TestActivityModel, TestBaseModel, Modules\Activity\Models\BaseModel

### Community 8 - "ListLogActivitiesPageCoverageTest.php"
Cohesion: 0.60
Nodes (3): Filament\Notifications\Notification, exposeRestoreFailure(), exposeRestoreSuccess()

## Knowledge Gaps
- **1 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `TestCase` connect `TestCase` to `TestCase.php`?**
  _High betweenness centrality (0.055) - this node is a cross-community bridge._
- **Should `TestCase.php` be split into smaller, more focused modules?**
  _Cohesion score 0.05263157894736842 - nodes in this community are weakly interconnected._
- **Should `CanPaginateHarness` be split into smaller, more focused modules?**
  _Cohesion score 0.1103448275862069 - nodes in this community are weakly interconnected._
- **Should `Illuminate\Database\Eloquent\Model` be split into smaller, more focused modules?**
  _Cohesion score 0.09846153846153846 - nodes in this community are weakly interconnected._
- **Should `Modules\Activity\Models\Activity` be split into smaller, more focused modules?**
  _Cohesion score 0.13333333333333333 - nodes in this community are weakly interconnected._