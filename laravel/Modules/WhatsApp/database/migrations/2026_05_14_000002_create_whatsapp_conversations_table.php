<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    public function up(): void
    {
        $this->tableCreate(static function (Blueprint $table): void {
            $table->id();
            $table->string('phone_e164')->index();
            $table->string('wa_id')->nullable()->index();
            $table->string('display_name')->nullable();
            $table->foreignId('identity_id')->nullable()->constrained('whatsapp_contact_identities')->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->foreignId('customer_site_id')->nullable()->constrained('customer_sites')->nullOnDelete();
            $table->foreignId('contact_id')->nullable()->constrained('contacts')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status')->default('open');
            $table->timestamp('last_message_at')->nullable();
            $table->json('meta')->nullable();

            $table->unique(['phone_e164']);
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps(table: $table, hasSoftDeletes: false);
        });
    }
};
