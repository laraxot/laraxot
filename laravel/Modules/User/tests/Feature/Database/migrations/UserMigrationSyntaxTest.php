<?php

declare(strict_types=1);

namespace Modules\User\Tests\Feature\Database\Migrations;

use Modules\User\Tests\TestCase;
<<<<<<< .merge_file_753eG5
=======
<<<<<<< .merge_file_EFHjK6
=======
use Modules\User\Models\SocialiteUser;
>>>>>>> .merge_file_MrRdJx
>>>>>>> .merge_file_g9FlwY
use PHPUnit\Framework\Assert;

use function Safe\exec;
use function Safe\file_get_contents;

uses(TestCase::class);

describe('User Migration Syntax', function (): void {
<<<<<<< .merge_file_753eG5
    test('user migrations do not contain merge conflict markers', function (): void {
        foreach (userMigrationFiles() as $migrationFile) {
            $contents = file_get_contents($migrationFile);
=======
<<<<<<< .merge_file_EFHjK6
=======
    test('user model has one canonical owner migration', function (): void {
        $migrationPath = module_path('User', 'database/migrations');
        $migration = $migrationPath.'/2026_11_12_120000_create_users_table.php';

        Assert::assertFileExists($migration);
        Assert::assertFileDoesNotExist($migrationPath.'/2026_11_12_000002_repair_users_deleted_at_column.php');
        Assert::assertFileDoesNotExist($migrationPath.'/2026_10_22_000001_repair_users_type_state_columns.php');
        Assert::assertFileDoesNotExist($migrationPath.'/2026_11_11_000001_add_digital_identity_columns_to_users_table.php');

        $contents = file_get_contents($migration);
        Assert::assertStringContainsString(
            '$this->updateTimestamps(table: $table, hasSoftDeletes: true);',
            $contents,
        );
        Assert::assertStringNotContainsString('$table->softDeletes();', $contents);
    });

    test('socialite user has one canonical owner migration on the user connection', function (): void {
        $migrationPath = module_path('User', 'database/migrations');
        $migration = $migrationPath.'/2026_11_12_120002_create_socialite_users_table.php';

        Assert::assertFileExists($migration);
        Assert::assertFileDoesNotExist($migrationPath.'/2023_01_01_000003_create_socialite_user_table.php');

        $contents = file_get_contents($migration);
        Assert::assertStringContainsString('protected ?string $model_class = SocialiteUser::class;', $contents);
        Assert::assertStringContainsString('$this->updateTimestamps($table);', $contents);
        Assert::assertSame('user', (new SocialiteUser())->getConnectionName());
    });

>>>>>>> .merge_file_MrRdJx
    test('user migrations do not contain merge conflict markers', function (): void {
        foreach (userMigrationFiles() as $migrationFile) {
            $contents = file_get_contents($migrationFile);

            Assert::assertDoesNotMatchRegularExpression(
                '/^(<{7}|={7}|>{7})( |$)/m',
                $contents,
                'Merge conflict marker found in '.$migrationFile,
            );
>>>>>>> .merge_file_g9FlwY
        }
    });

    test('user migrations have valid php syntax', function (): void {
        foreach (userMigrationFiles() as $migrationFile) {
            $output = [];
            $exitCode = 0;

            exec('php -l '.escapeshellarg($migrationFile), $output, $exitCode);

            $lines = [];
            if (is_array($output)) {
                foreach ($output as $line) {
                    if (is_string($line)) {
                        $lines[] = $line;
                    }
                }
            }

            Assert::assertSame(0, $exitCode, implode(PHP_EOL, $lines));
        }
    });
});
