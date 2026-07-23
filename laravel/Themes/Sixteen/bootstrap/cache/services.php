<?php

use Illuminate\Auth\AuthServiceProvider;
use Illuminate\Broadcasting\BroadcastServiceProvider;
use Illuminate\Bus\BusServiceProvider;
use Illuminate\Cache\CacheServiceProvider;
use Illuminate\Foundation\Providers\ConsoleSupportServiceProvider;
use Illuminate\Cookie\CookieServiceProvider;
use Illuminate\Database\DatabaseServiceProvider;
use Illuminate\Encryption\EncryptionServiceProvider;
use Illuminate\Filesystem\FilesystemServiceProvider;
use Illuminate\Foundation\Providers\FoundationServiceProvider;
use Illuminate\Hashing\HashServiceProvider;
use Illuminate\Mail\MailServiceProvider;
use Illuminate\Notifications\NotificationServiceProvider;
use Illuminate\Pagination\PaginationServiceProvider;
use Illuminate\Auth\Passwords\PasswordResetServiceProvider;
use Illuminate\Pipeline\PipelineServiceProvider;
use Illuminate\Queue\QueueServiceProvider;
use Illuminate\Redis\RedisServiceProvider;
use Illuminate\Session\SessionServiceProvider;
use Illuminate\Translation\TranslationServiceProvider;
use Illuminate\Validation\ValidationServiceProvider;
use Illuminate\View\ViewServiceProvider;
use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use Filament\Actions\ActionsServiceProvider;
use Filament\FilamentServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Infolists\InfolistsServiceProvider;
use Filament\Notifications\NotificationsServiceProvider;
use Filament\Support\SupportServiceProvider;
use Filament\Tables\TablesServiceProvider;
use Filament\Widgets\WidgetsServiceProvider;
use Kirschbaum\PowerJoins\PowerJoinsServiceProvider;
use Livewire\LivewireServiceProvider;
use Carbon\Laravel\ServiceProvider;
use Termwind\Laravel\TermwindServiceProvider;
use RyanChandler\BladeCaptureDirective\BladeCaptureDirectiveServiceProvider;
use Illuminate\Broadcasting\BroadcastManager;
use Illuminate\Contracts\Broadcasting\Factory;
use Illuminate\Contracts\Broadcasting\Broadcaster;
use Illuminate\Bus\Dispatcher;
use Illuminate\Contracts\Bus\QueueingDispatcher;
use Illuminate\Bus\BatchRepository;
use Illuminate\Bus\DatabaseBatchRepository;
use Illuminate\Cache\RateLimiter;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Cache\Console\ClearCommand;
use Illuminate\Cache\Console\ForgetCommand;
use Illuminate\Foundation\Console\ClearCompiledCommand;
use Illuminate\Auth\Console\ClearResetsCommand;
use Illuminate\Foundation\Console\ConfigCacheCommand;
use Illuminate\Foundation\Console\ConfigClearCommand;
use Illuminate\Foundation\Console\ConfigShowCommand;
use Illuminate\Database\Console\DbCommand;
use Illuminate\Database\Console\MonitorCommand;
use Illuminate\Database\Console\PruneCommand;
use Illuminate\Database\Console\ShowCommand;
use Illuminate\Database\Console\TableCommand;
use Illuminate\Database\Console\WipeCommand;
use Illuminate\Foundation\Console\DownCommand;
use Illuminate\Foundation\Console\EnvironmentCommand;
use Illuminate\Foundation\Console\EnvironmentDecryptCommand;
use Illuminate\Foundation\Console\EnvironmentEncryptCommand;
use Illuminate\Foundation\Console\EventCacheCommand;
use Illuminate\Foundation\Console\EventClearCommand;
use Illuminate\Foundation\Console\EventListCommand;
use Illuminate\Foundation\Console\KeyGenerateCommand;
use Illuminate\Foundation\Console\OptimizeCommand;
use Illuminate\Foundation\Console\OptimizeClearCommand;
use Illuminate\Foundation\Console\PackageDiscoverCommand;
use Illuminate\Cache\Console\PruneStaleTagsCommand;
use Illuminate\Queue\Console\ListFailedCommand;
use Illuminate\Queue\Console\FlushFailedCommand;
use Illuminate\Queue\Console\ForgetFailedCommand;
use Illuminate\Queue\Console\ListenCommand;
use Illuminate\Queue\Console\PruneBatchesCommand;
use Illuminate\Queue\Console\PruneFailedJobsCommand;
use Illuminate\Queue\Console\RestartCommand;
use Illuminate\Queue\Console\RetryCommand;
use Illuminate\Queue\Console\RetryBatchCommand;
use Illuminate\Queue\Console\WorkCommand;
use Illuminate\Foundation\Console\RouteCacheCommand;
use Illuminate\Foundation\Console\RouteClearCommand;
use Illuminate\Foundation\Console\RouteListCommand;
use Illuminate\Database\Console\DumpCommand;
use Illuminate\Database\Console\Seeds\SeedCommand;
use Illuminate\Console\Scheduling\ScheduleFinishCommand;
use Illuminate\Console\Scheduling\ScheduleListCommand;
use Illuminate\Console\Scheduling\ScheduleRunCommand;
use Illuminate\Console\Scheduling\ScheduleClearCacheCommand;
use Illuminate\Console\Scheduling\ScheduleTestCommand;
use Illuminate\Console\Scheduling\ScheduleWorkCommand;
use Illuminate\Console\Scheduling\ScheduleInterruptCommand;
use Illuminate\Database\Console\ShowModelCommand;
use Illuminate\Foundation\Console\StorageLinkCommand;
use Illuminate\Foundation\Console\StorageUnlinkCommand;
use Illuminate\Foundation\Console\UpCommand;
use Illuminate\Foundation\Console\ViewCacheCommand;
use Illuminate\Foundation\Console\ViewClearCommand;
use Illuminate\Foundation\Console\ApiInstallCommand;
use Illuminate\Foundation\Console\BroadcastingInstallCommand;
use Illuminate\Cache\Console\CacheTableCommand;
use Illuminate\Foundation\Console\CastMakeCommand;
use Illuminate\Foundation\Console\ChannelListCommand;
use Illuminate\Foundation\Console\ChannelMakeCommand;
use Illuminate\Foundation\Console\ClassMakeCommand;
use Illuminate\Foundation\Console\ComponentMakeCommand;
use Illuminate\Foundation\Console\ConfigPublishCommand;
use Illuminate\Foundation\Console\ConsoleMakeCommand;
use Illuminate\Routing\Console\ControllerMakeCommand;
use Illuminate\Foundation\Console\DocsCommand;
use Illuminate\Foundation\Console\EnumMakeCommand;
use Illuminate\Foundation\Console\EventGenerateCommand;
use Illuminate\Foundation\Console\EventMakeCommand;
use Illuminate\Foundation\Console\ExceptionMakeCommand;
use Illuminate\Database\Console\Factories\FactoryMakeCommand;
use Illuminate\Foundation\Console\InterfaceMakeCommand;
use Illuminate\Foundation\Console\JobMakeCommand;
use Illuminate\Foundation\Console\LangPublishCommand;
use Illuminate\Foundation\Console\ListenerMakeCommand;
use Illuminate\Foundation\Console\MailMakeCommand;
use Illuminate\Routing\Console\MiddlewareMakeCommand;
use Illuminate\Foundation\Console\ModelMakeCommand;
use Illuminate\Foundation\Console\NotificationMakeCommand;
use Illuminate\Notifications\Console\NotificationTableCommand;
use Illuminate\Foundation\Console\ObserverMakeCommand;
use Illuminate\Foundation\Console\PolicyMakeCommand;
use Illuminate\Foundation\Console\ProviderMakeCommand;
use Illuminate\Queue\Console\FailedTableCommand;
use Illuminate\Queue\Console\BatchesTableCommand;
use Illuminate\Foundation\Console\RequestMakeCommand;
use Illuminate\Foundation\Console\ResourceMakeCommand;
use Illuminate\Foundation\Console\RuleMakeCommand;
use Illuminate\Foundation\Console\ScopeMakeCommand;
use Illuminate\Database\Console\Seeds\SeederMakeCommand;
use Illuminate\Session\Console\SessionTableCommand;
use Illuminate\Foundation\Console\ServeCommand;
use Illuminate\Foundation\Console\StubPublishCommand;
use Illuminate\Foundation\Console\TestMakeCommand;
use Illuminate\Foundation\Console\TraitMakeCommand;
use Illuminate\Foundation\Console\VendorPublishCommand;
use Illuminate\Foundation\Console\ViewMakeCommand;
use Illuminate\Database\Console\Migrations\MigrateCommand;
use Illuminate\Database\Console\Migrations\FreshCommand;
use Illuminate\Database\Console\Migrations\InstallCommand;
use Illuminate\Database\Console\Migrations\RefreshCommand;
use Illuminate\Database\Console\Migrations\ResetCommand;
use Illuminate\Database\Console\Migrations\RollbackCommand;
use Illuminate\Database\Console\Migrations\StatusCommand;
use Illuminate\Database\Console\Migrations\MigrateMakeCommand;
use Illuminate\Mail\Markdown;
use Illuminate\Contracts\Pipeline\Hub;
use Illuminate\Contracts\Validation\UncompromisedVerifier;

return ['providers' => 
[0 => AuthServiceProvider::class, 1 => BroadcastServiceProvider::class, 2 => BusServiceProvider::class, 3 => CacheServiceProvider::class, 4 => ConsoleSupportServiceProvider::class, 5 => CookieServiceProvider::class, 6 => DatabaseServiceProvider::class, 7 => EncryptionServiceProvider::class, 8 => FilesystemServiceProvider::class, 9 => FoundationServiceProvider::class, 10 => HashServiceProvider::class, 11 => MailServiceProvider::class, 12 => NotificationServiceProvider::class, 13 => PaginationServiceProvider::class, 14 => PasswordResetServiceProvider::class, 15 => PipelineServiceProvider::class, 16 => QueueServiceProvider::class, 17 => RedisServiceProvider::class, 18 => SessionServiceProvider::class, 19 => TranslationServiceProvider::class, 20 => ValidationServiceProvider::class, 21 => ViewServiceProvider::class, 22 => BladeHeroiconsServiceProvider::class, 23 => BladeIconsServiceProvider::class, 24 => ActionsServiceProvider::class, 25 => FilamentServiceProvider::class, 26 => FormsServiceProvider::class, 27 => InfolistsServiceProvider::class, 28 => NotificationsServiceProvider::class, 29 => SupportServiceProvider::class, 30 => TablesServiceProvider::class, 31 => WidgetsServiceProvider::class, 32 => PowerJoinsServiceProvider::class, 33 => LivewireServiceProvider::class, 34 => ServiceProvider::class, 35 => TermwindServiceProvider::class, 36 => BladeCaptureDirectiveServiceProvider::class], 'eager' => 
[0 => AuthServiceProvider::class, 1 => CookieServiceProvider::class, 2 => DatabaseServiceProvider::class, 3 => EncryptionServiceProvider::class, 4 => FilesystemServiceProvider::class, 5 => FoundationServiceProvider::class, 6 => NotificationServiceProvider::class, 7 => PaginationServiceProvider::class, 8 => SessionServiceProvider::class, 9 => ViewServiceProvider::class, 10 => BladeHeroiconsServiceProvider::class, 11 => BladeIconsServiceProvider::class, 12 => ActionsServiceProvider::class, 13 => FilamentServiceProvider::class, 14 => FormsServiceProvider::class, 15 => InfolistsServiceProvider::class, 16 => NotificationsServiceProvider::class, 17 => SupportServiceProvider::class, 18 => TablesServiceProvider::class, 19 => WidgetsServiceProvider::class, 20 => PowerJoinsServiceProvider::class, 21 => LivewireServiceProvider::class, 22 => ServiceProvider::class, 23 => TermwindServiceProvider::class, 24 => BladeCaptureDirectiveServiceProvider::class], 'deferred' => 
[BroadcastManager::class => BroadcastServiceProvider::class, Factory::class => BroadcastServiceProvider::class, Broadcaster::class => BroadcastServiceProvider::class, Dispatcher::class => BusServiceProvider::class, \Illuminate\Contracts\Bus\Dispatcher::class => BusServiceProvider::class, QueueingDispatcher::class => BusServiceProvider::class, BatchRepository::class => BusServiceProvider::class, DatabaseBatchRepository::class => BusServiceProvider::class, 'cache' => CacheServiceProvider::class, 'cache.store' => CacheServiceProvider::class, 'cache.psr6' => CacheServiceProvider::class, 'memcached.connector' => CacheServiceProvider::class, RateLimiter::class => CacheServiceProvider::class, AboutCommand::class => ConsoleSupportServiceProvider::class, ClearCommand::class => ConsoleSupportServiceProvider::class, ForgetCommand::class => ConsoleSupportServiceProvider::class, ClearCompiledCommand::class => ConsoleSupportServiceProvider::class, ClearResetsCommand::class => ConsoleSupportServiceProvider::class, ConfigCacheCommand::class => ConsoleSupportServiceProvider::class, ConfigClearCommand::class => ConsoleSupportServiceProvider::class, ConfigShowCommand::class => ConsoleSupportServiceProvider::class, DbCommand::class => ConsoleSupportServiceProvider::class, MonitorCommand::class => ConsoleSupportServiceProvider::class, PruneCommand::class => ConsoleSupportServiceProvider::class, ShowCommand::class => ConsoleSupportServiceProvider::class, TableCommand::class => ConsoleSupportServiceProvider::class, WipeCommand::class => ConsoleSupportServiceProvider::class, DownCommand::class => ConsoleSupportServiceProvider::class, EnvironmentCommand::class => ConsoleSupportServiceProvider::class, EnvironmentDecryptCommand::class => ConsoleSupportServiceProvider::class, EnvironmentEncryptCommand::class => ConsoleSupportServiceProvider::class, EventCacheCommand::class => ConsoleSupportServiceProvider::class, EventClearCommand::class => ConsoleSupportServiceProvider::class, EventListCommand::class => ConsoleSupportServiceProvider::class, KeyGenerateCommand::class => ConsoleSupportServiceProvider::class, OptimizeCommand::class => ConsoleSupportServiceProvider::class, OptimizeClearCommand::class => ConsoleSupportServiceProvider::class, PackageDiscoverCommand::class => ConsoleSupportServiceProvider::class, PruneStaleTagsCommand::class => ConsoleSupportServiceProvider::class, \Illuminate\Queue\Console\ClearCommand::class => ConsoleSupportServiceProvider::class, ListFailedCommand::class => ConsoleSupportServiceProvider::class, FlushFailedCommand::class => ConsoleSupportServiceProvider::class, ForgetFailedCommand::class => ConsoleSupportServiceProvider::class, ListenCommand::class => ConsoleSupportServiceProvider::class, \Illuminate\Queue\Console\MonitorCommand::class => ConsoleSupportServiceProvider::class, PruneBatchesCommand::class => ConsoleSupportServiceProvider::class, PruneFailedJobsCommand::class => ConsoleSupportServiceProvider::class, RestartCommand::class => ConsoleSupportServiceProvider::class, RetryCommand::class => ConsoleSupportServiceProvider::class, RetryBatchCommand::class => ConsoleSupportServiceProvider::class, WorkCommand::class => ConsoleSupportServiceProvider::class, RouteCacheCommand::class => ConsoleSupportServiceProvider::class, RouteClearCommand::class => ConsoleSupportServiceProvider::class, RouteListCommand::class => ConsoleSupportServiceProvider::class, DumpCommand::class => ConsoleSupportServiceProvider::class, SeedCommand::class => ConsoleSupportServiceProvider::class, ScheduleFinishCommand::class => ConsoleSupportServiceProvider::class, ScheduleListCommand::class => ConsoleSupportServiceProvider::class, ScheduleRunCommand::class => ConsoleSupportServiceProvider::class, ScheduleClearCacheCommand::class => ConsoleSupportServiceProvider::class, ScheduleTestCommand::class => ConsoleSupportServiceProvider::class, ScheduleWorkCommand::class => ConsoleSupportServiceProvider::class, ScheduleInterruptCommand::class => ConsoleSupportServiceProvider::class, ShowModelCommand::class => ConsoleSupportServiceProvider::class, StorageLinkCommand::class => ConsoleSupportServiceProvider::class, StorageUnlinkCommand::class => ConsoleSupportServiceProvider::class, UpCommand::class => ConsoleSupportServiceProvider::class, ViewCacheCommand::class => ConsoleSupportServiceProvider::class, ViewClearCommand::class => ConsoleSupportServiceProvider::class, ApiInstallCommand::class => ConsoleSupportServiceProvider::class, BroadcastingInstallCommand::class => ConsoleSupportServiceProvider::class, CacheTableCommand::class => ConsoleSupportServiceProvider::class, CastMakeCommand::class => ConsoleSupportServiceProvider::class, ChannelListCommand::class => ConsoleSupportServiceProvider::class, ChannelMakeCommand::class => ConsoleSupportServiceProvider::class, ClassMakeCommand::class => ConsoleSupportServiceProvider::class, ComponentMakeCommand::class => ConsoleSupportServiceProvider::class, ConfigPublishCommand::class => ConsoleSupportServiceProvider::class, ConsoleMakeCommand::class => ConsoleSupportServiceProvider::class, ControllerMakeCommand::class => ConsoleSupportServiceProvider::class, DocsCommand::class => ConsoleSupportServiceProvider::class, EnumMakeCommand::class => ConsoleSupportServiceProvider::class, EventGenerateCommand::class => ConsoleSupportServiceProvider::class, EventMakeCommand::class => ConsoleSupportServiceProvider::class, ExceptionMakeCommand::class => ConsoleSupportServiceProvider::class, FactoryMakeCommand::class => ConsoleSupportServiceProvider::class, InterfaceMakeCommand::class => ConsoleSupportServiceProvider::class, JobMakeCommand::class => ConsoleSupportServiceProvider::class, LangPublishCommand::class => ConsoleSupportServiceProvider::class, ListenerMakeCommand::class => ConsoleSupportServiceProvider::class, MailMakeCommand::class => ConsoleSupportServiceProvider::class, MiddlewareMakeCommand::class => ConsoleSupportServiceProvider::class, ModelMakeCommand::class => ConsoleSupportServiceProvider::class, NotificationMakeCommand::class => ConsoleSupportServiceProvider::class, NotificationTableCommand::class => ConsoleSupportServiceProvider::class, ObserverMakeCommand::class => ConsoleSupportServiceProvider::class, PolicyMakeCommand::class => ConsoleSupportServiceProvider::class, ProviderMakeCommand::class => ConsoleSupportServiceProvider::class, FailedTableCommand::class => ConsoleSupportServiceProvider::class, \Illuminate\Queue\Console\TableCommand::class => ConsoleSupportServiceProvider::class, BatchesTableCommand::class => ConsoleSupportServiceProvider::class, RequestMakeCommand::class => ConsoleSupportServiceProvider::class, ResourceMakeCommand::class => ConsoleSupportServiceProvider::class, RuleMakeCommand::class => ConsoleSupportServiceProvider::class, ScopeMakeCommand::class => ConsoleSupportServiceProvider::class, SeederMakeCommand::class => ConsoleSupportServiceProvider::class, SessionTableCommand::class => ConsoleSupportServiceProvider::class, ServeCommand::class => ConsoleSupportServiceProvider::class, StubPublishCommand::class => ConsoleSupportServiceProvider::class, TestMakeCommand::class => ConsoleSupportServiceProvider::class, TraitMakeCommand::class => ConsoleSupportServiceProvider::class, VendorPublishCommand::class => ConsoleSupportServiceProvider::class, ViewMakeCommand::class => ConsoleSupportServiceProvider::class, 'migrator' => ConsoleSupportServiceProvider::class, 'migration.repository' => ConsoleSupportServiceProvider::class, 'migration.creator' => ConsoleSupportServiceProvider::class, MigrateCommand::class => ConsoleSupportServiceProvider::class, FreshCommand::class => ConsoleSupportServiceProvider::class, InstallCommand::class => ConsoleSupportServiceProvider::class, RefreshCommand::class => ConsoleSupportServiceProvider::class, ResetCommand::class => ConsoleSupportServiceProvider::class, RollbackCommand::class => ConsoleSupportServiceProvider::class, StatusCommand::class => ConsoleSupportServiceProvider::class, MigrateMakeCommand::class => ConsoleSupportServiceProvider::class, 'composer' => ConsoleSupportServiceProvider::class, 'hash' => HashServiceProvider::class, 'hash.driver' => HashServiceProvider::class, 'mail.manager' => MailServiceProvider::class, 'mailer' => MailServiceProvider::class, Markdown::class => MailServiceProvider::class, 'auth.password' => PasswordResetServiceProvider::class, 'auth.password.broker' => PasswordResetServiceProvider::class, Hub::class => PipelineServiceProvider::class, 'pipeline' => PipelineServiceProvider::class, 'queue' => QueueServiceProvider::class, 'queue.connection' => QueueServiceProvider::class, 'queue.failer' => QueueServiceProvider::class, 'queue.listener' => QueueServiceProvider::class, 'queue.worker' => QueueServiceProvider::class, 'redis' => RedisServiceProvider::class, 'redis.connection' => RedisServiceProvider::class, 'translator' => TranslationServiceProvider::class, 'translation.loader' => TranslationServiceProvider::class, 'validator' => ValidationServiceProvider::class, 'validation.presence' => ValidationServiceProvider::class, UncompromisedVerifier::class => ValidationServiceProvider::class], 'when' => 
[BroadcastServiceProvider::class => 
[], BusServiceProvider::class => 
[], CacheServiceProvider::class => 
[], ConsoleSupportServiceProvider::class => 
[], HashServiceProvider::class => 
[], MailServiceProvider::class => 
[], PasswordResetServiceProvider::class => 
[], PipelineServiceProvider::class => 
[], QueueServiceProvider::class => 
[], RedisServiceProvider::class => 
[], TranslationServiceProvider::class => 
[], ValidationServiceProvider::class => 
[]]];
