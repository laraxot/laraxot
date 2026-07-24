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
        Schema::create('timber_wood_species', function (Blueprint $table): void {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Popola con le specie attualmente definite in config, così la pagina è
        // subito operativa e resta la sorgente modificabile da UI.
        $species = config('timber.wood_species', []);
        if (is_array($species)) {
            $order = 0;
            foreach ($species as $code => $name) {
                DB::table('timber_wood_species')->insert([
                    'code' => (string) $code,
                    'name' => is_string($name) ? $name : (string) $code,
                    'sort_order' => $order++,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('timber_wood_species');
    }
};
