# Graph Report - tests  (2026-08-19)

## Corpus Check
- 21 files · ~4,162 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 55 nodes · 75 edges · 12 communities (10 shown, 2 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `b78607d8`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- HasLikesTraitTest.php
- StatsOverviewWidgetTest.php
- HasRatingActionsTest.php
- TestCase
- RatingFilamentExtendedTest.php
- RatingFilamentRelationManagerTest.php
- Modules\Xot\Contracts\UserContract
- HasRatingsTraitAccessorsTest.php
- Illuminate\Foundation\Application

## God Nodes (most connected - your core abstractions)
1. `TestCase` - 6 edges
2. `LikeableStub` - 5 edges
3. `ratingMockHost()` - 4 edges
4. `ratingStatsHost()` - 4 edges
5. `ratingRelationManagerColumnNames()` - 3 edges
6. `ratingFakeUser()` - 3 edges
7. `RatingsHostStub` - 2 edges
8. `BaseRatingsTableStub` - 2 edges
9. `BaseEditRatingStub` - 2 edges

## Surprising Connections (you probably didn't know these)
- `LikeableStub` --inherits--> `Illuminate\Database\Eloquent\Model`  [EXTRACTED]
  Unit/HasLikesTraitTest.php →   _Bridges community 1 → community 2_

## Import Cycles
- None detected.

## Communities (12 total, 2 thin omitted)

### Community 1 - "HasLikesTraitTest.php"
Cohesion: 0.43
Nodes (5): Illuminate\Database\Eloquent\Collection, Illuminate\Database\Eloquent\Relations\MorphMany, Modules\Rating\Models\Traits\HasLikes, MorphMany, LikeableStub

### Community 2 - "StatsOverviewWidgetTest.php"
Cohesion: 0.47
Nodes (4): Illuminate\Database\Eloquent\Model, Illuminate\Support\Collection, Model, ratingStatsHost()

### Community 3 - "HasRatingActionsTest.php"
Cohesion: 0.60
Nodes (4): HasRatingContract, Illuminate\Database\Eloquent\Relations\MorphToMany, Modules\Rating\Models\Contracts\HasRatingContract, ratingMockHost()

### Community 4 - "TestCase"
Cohesion: 0.40
Nodes (3): Illuminate\Foundation\Testing\DatabaseTransactions, Modules\Xot\Tests\XotBaseTestCase, TestCase

### Community 5 - "RatingFilamentExtendedTest.php"
Cohesion: 0.60
Nodes (4): Modules\Rating\Filament\Resources\RatingResource\Pages\BaseEditRating, Modules\Rating\Filament\Resources\RatingResource\Tables\BaseRatingsTable, BaseEditRatingStub, BaseRatingsTableStub

### Community 6 - "RatingFilamentRelationManagerTest.php"
Cohesion: 0.67
Nodes (3): Modules\Rating\Filament\RelationManagers\RatingsRelationManager, Modules\Rating\Filament\Resources\HasRatingResource\RelationManagers\RatingsRelationManager, ratingRelationManagerColumnNames()

### Community 7 - "Modules\Xot\Contracts\UserContract"
Cohesion: 0.67
Nodes (3): Modules\Xot\Contracts\UserContract, ratingFakeUser(), UserContract

## Knowledge Gaps
- **2 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `TestCase` connect `TestCase` to `TestCase.php`, `Illuminate\Foundation\Application`?**
  _High betweenness centrality (0.090) - this node is a cross-community bridge._
- **Why does `LikeableStub` connect `HasLikesTraitTest.php` to `StatsOverviewWidgetTest.php`?**
  _High betweenness centrality (0.046) - this node is a cross-community bridge._