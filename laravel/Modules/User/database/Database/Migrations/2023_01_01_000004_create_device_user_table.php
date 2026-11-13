<?php

declare(strict_types=1);
<<<<<<< .merge_file_3XYOwq

=======
>>>>>>> .merge_file_JJioqq
use Illuminate\Database\Schema\Blueprint;
use Modules\User\Models\Device;
use Modules\Xot\Database\Migrations\XotBaseMigration;
use Modules\Xot\Datas\XotData;

<<<<<<< .merge_file_3XYOwq
return new class extends XotBaseMigration {
=======
return new class extends XotBaseMigration
{
>>>>>>> .merge_file_JJioqq
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // -- CREATE --
        $this->tableCreate(function (Blueprint $table): void {
            $user_class = XotData::make()->getUserClass();
            $table->id('id');
            $table->foreignIdFor(Device::class, 'device_id')->index();
            $table->foreignIdFor($user_class, 'user_id')->index();
            $table->dateTime('login_at')->nullable();
            $table->dateTime('logout_at')->nullable();
        });
        // -- UPDATE --
        $this->tableUpdate(function (Blueprint $table): void {
            if (! $this->hasColumn('push_notifications_token')) {
                $table->string('push_notifications_token')->nullable();
            }

            if (! $this->hasColumn('push_notifications_enabled')) {
                $table->boolean('push_notifications_enabled')->nullable();
            }
            // -- change
            if ($this->hasColumn('device_id')) {
                $table->string('device_id', 36)->nullable()->change();
            }
            // dddx($this->getColumnType('device_id'));//varchar
<<<<<<< .merge_file_3XYOwq
            if ('uuid' === $this->getColumnType('user_id')) {
=======
            if ($this->getColumnType('user_id') === 'uuid') {
>>>>>>> .merge_file_JJioqq
                $table->string('user_id', 36)->nullable()->change();
            }

            $this->updateTimestamps($table);
        });
    }
};
