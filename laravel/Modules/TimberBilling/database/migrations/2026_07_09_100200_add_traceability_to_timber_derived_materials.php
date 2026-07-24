<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::table('timber_derived_materials', function (Blueprint $table): void {
            $table->foreignId('root_lot_id')->nullable()->after('processing_step_id')->constrained('timber_lots')->nullOnDelete();
            $table->string('traceable_code')->nullable()->after('output_code')->index();
            $table->string('stage_type_code')->nullable()->after('traceable_code');
            $table->json('stage_history')->nullable()->after('stage_type_code');
        });
    }

    public function down(): void
    {
        Schema::table('timber_derived_materials', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('root_lot_id');
            $table->dropColumn(['traceable_code', 'stage_type_code', 'stage_history']);
        });
    }
};
