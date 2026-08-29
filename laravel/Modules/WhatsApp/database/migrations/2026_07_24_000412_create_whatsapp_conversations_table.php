<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\AiAssistant\Models\AiActionProposal;
use Modules\Customer\Models\Customer;
use Modules\Customer\Models\CustomerSite;
use Modules\WhatsApp\Models\WhatsAppContactIdentity;
use Modules\WhatsApp\Models\WhatsAppConversation;
use Modules\Xot\Database\Migrations\XotBaseMigration;
use Modules\Xot\Datas\XotData;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = WhatsAppConversation::class;

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $user_class = XotData::make()->getUserClass();

            $table->id();
            $table->string('phone_e164')->index();
            $table->string('wa_id')->nullable()->index();
            $table->string('display_name')->nullable();
            $table->foreignIdFor(WhatsAppContactIdentity::class, 'identity_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Customer::class, 'customer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(CustomerSite::class, 'customer_site_id')->nullable()->constrained()->nullOnDelete();
            // contacts.id e' unsignedInteger (non bigint): stesso pattern esplicito
            // usato in Modules/Email/database/migrations/*_create_email_contact_identities_table.php.
            $table->unsignedInteger('contact_id')->nullable()->index();
            $table->foreign('contact_id')->references('id')->on('customer_contacts')->nullOnDelete();
            $table->foreignIdFor($user_class, 'user_id')->nullable()->index();
            $table->string('status')->default('open');
            $table->timestamp('last_message_at')->nullable();
            $table->json('meta')->nullable();

            // The single "live" pending proposal for this conversation (dedup).
            $table->foreignIdFor(AiActionProposal::class, 'active_proposal_id')->nullable()->constrained()->nullOnDelete();
            // approved_up_to_message_id (checkpoint FK verso whatsapp_messages) NON sta qui:
            // dipendenza circolare (whatsapp_messages.conversation_id -> whatsapp_conversations),
            // whatsapp_messages non esiste ancora a questo punto della sequenza timestamp.
            // Colonna aggiunta da 2026_07_24_000413_create_whatsapp_messages_table.php via tableUpdate()
            // dopo che whatsapp_messages esiste.

            $table->unique(['phone_e164']);
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps(table: $table, hasSoftDeletes: false);
        });
    }
};
