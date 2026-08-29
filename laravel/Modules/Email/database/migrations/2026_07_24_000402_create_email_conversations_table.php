<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;
use Modules\Xot\Datas\XotData;

return new class() extends XotBaseMigration
{
    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $user_class = XotData::make()->getUserClass();

            $table->id();
            $table->string('email')->unique();
            $table->string('display_name')->nullable();
            $table->foreignId('identity_id')->nullable()->constrained('email_contact_identities')->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->foreignId('customer_site_id')->nullable()->constrained('customer_sites')->nullOnDelete();
            $table->unsignedInteger('contact_id')->nullable()->index();
            $table->foreign('contact_id')->references('id')->on('customer_contacts')->nullOnDelete();
            $table->foreignIdFor($user_class, 'user_id')->nullable()->index();
            $table->string('status')->default('open');
            $table->timestamp('last_message_at')->nullable();
            $table->json('meta')->nullable();
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps(table: $table, hasSoftDeletes: false);
        });
    }
};
