<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('timber_price_lists', function (Blueprint $table): void {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('timber_price_list_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('price_list_id')->constrained('timber_price_lists')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('timber_products')->cascadeOnDelete();
            $table->decimal('net_price', 14, 4)->default(0);
            $table->timestamps();

            $table->unique(['price_list_id', 'product_id']);
        });

        Schema::create('timber_customer_prices', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('customer_id')->constrained('timber_customers')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('timber_products')->cascadeOnDelete();
            $table->decimal('net_price', 14, 4)->default(0);
            $table->timestamps();

            $table->unique(['customer_id', 'product_id']);
        });

        Schema::create('timber_price_list_versions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('price_list_id')->constrained('timber_price_lists')->cascadeOnDelete();
            $table->json('items');
            $table->text('note')->nullable();
            $table->string('created_by_user_id', 36)->nullable()->index();
            $table->foreign('created_by_user_id')->references('id')->on('users')->nullOnDelete();
            $table->timestamps();
        });

        // Seed dei listini dai codici gia' definiti in config.
        $lists = config('timberbilling.timber.price_lists', []);
        if (is_array($lists)) {
            foreach ($lists as $code => $name) {
                DB::table('timber_price_lists')->insert([
                    'code' => (string) $code,
                    'name' => is_string($name) ? $name : (string) $code,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('timber_price_list_versions');
        Schema::dropIfExists('timber_customer_prices');
        Schema::dropIfExists('timber_price_list_items');
        Schema::dropIfExists('timber_price_lists');
    }
};
