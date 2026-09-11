<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;

/**
 * La Dichiarazione di Conformita' del DM 37/2008, agganciata alla commessa.
 *
 * Non e' un documento qualsiasi: e' l'atto che l'installatore e' tenuto per
 * legge a consegnare al committente a fine lavori, anche se il cliente non ha
 * pagato, e va depositato allo Sportello Unico entro trenta giorni. Senza gli
 * allegati previsti dall'Allegato I la dichiarazione e' incompleta, quindi il
 * pezzo che conta di questa funzione non e' stampare il modulo: e' impedire di
 * emetterlo quando manca qualcosa.
 *
 * Sta nel modulo Compliance e non nel CRM perche' la dichiarazione descrive un
 * impianto realizzato in un immobile durante una commessa, e tutti e tre quei
 * dati esistono gia' qui: work_orders, customer_sites, customer_systems.
 * Tenerla altrove vuol dire farli ribattere a mano all'installatore.
 *
 * E c'e' un secondo motivo: EvaluateWorkOrderReadinessAction pretende gia' un
 * documento di tipo `dico` fra quelli obbligatori di una commessa, ma sa solo
 * dire se esiste. Con questa tabella sa anche dire se il fascicolo e' completo,
 * che e' la domanda vera.
 */
return new class() extends XotBaseMigration
{
    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();

            // Da qui si prendono committente, immobile e impianto: e' il motivo
            // per cui questa funzione vive nel gestionale commesse.
            $table->foreignId('work_order_id')->nullable()->constrained('work_orders')->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->foreignId('customer_site_id')->nullable()->constrained('customer_sites')->nullOnDelete();
            $table->foreignId('customer_system_id')->nullable()->constrained('customer_systems')->nullOnDelete();

            // Il documento nel fascicolo della commessa, creato quando la
            // dichiarazione viene emessa: e' cosi' che la spia di prontezza
            // della commessa diventa verde.
            $table->foreignId('compliance_document_id')->nullable()->constrained('compliance_documents')->nullOnDelete();

            // Gli id utente qui sono UUID: una foreignId verso users.id li
            // troncherebbe, e si perderebbe chi ha emesso l'atto.
            $table->string('issued_by_user_id', 36)->nullable()->index();

            // Art. 7 prevede due modelli: l'Allegato I per le imprese
            // installatrici e l'Allegato II per gli uffici tecnici interni di
            // imprese non installatrici. Non e' un dettaglio formale: sul
            // secondo il certificato CCIAA non e' fra gli allegati dovuti, e
            // cambia chi firma.
            $table->string('model', 2)->default('I');

            $table->string('number', 40);
            $table->date('issued_on')->nullable();

            // Impresa e committente vengono copiati dentro la dichiarazione
            // invece di essere letti dall'anagrafica al momento della stampa:
            // una dichiarazione e' un atto datato, e se domani il cliente cambia
            // indirizzo quella di ieri deve continuare a dire quello che diceva
            // ieri.
            $table->string('company_name');
            $table->string('company_address')->nullable();
            $table->string('company_vat', 20)->nullable();
            $table->string('company_cciaa')->nullable();
            $table->string('legal_representative')->nullable();
            $table->string('technical_manager')->nullable();

            $table->string('client_name');
            $table->string('client_tax_code', 32)->nullable();

            // L'immobile: e' l'unita' immobiliare dove sta l'impianto, non la
            // sede del cliente. Si precompila dal cantiere della commessa.
            $table->string('site_address')->nullable();
            $table->string('site_city')->nullable();
            $table->string('site_province', 4)->nullable();
            $table->string('site_postal_code', 10)->nullable();
            $table->string('site_unit')->nullable();
            $table->string('site_cadastral')->nullable();
            $table->string('site_use')->nullable();

            // Le lettere dell'art. 1 comma 2 del DM 37/08.
            $table->string('plant_type', 2);

            $table->string('work_type', 30);
            $table->text('work_description')->nullable();
            $table->text('materials')->nullable();
            $table->text('standards')->nullable();

            // Il progetto e' obbligatorio nei casi dell'art. 5. Quando lo e', il
            // riferimento va scritto in dichiarazione e il progetto allegato.
            $table->boolean('project_required')->default(false);
            $table->string('project_author')->nullable();
            $table->date('project_date')->nullable();

            $table->boolean('non_standard_materials')->default(false);
            $table->boolean('previous_declaration_exists')->default(false);

            $table->string('status', 20)->default('draft');
            $table->timestamp('issued_at')->nullable();
            $table->text('notes')->nullable();

            $table->unique('number');
            $table->index(['status', 'issued_on']);
            $table->index('work_order_id');
            $table->index('customer_id');

            $this->updateTimestamps($table, hasSoftDeletes: true);
        });
    }
};
