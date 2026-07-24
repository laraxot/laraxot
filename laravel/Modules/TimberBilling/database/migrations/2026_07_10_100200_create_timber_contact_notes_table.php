<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('timber_contact_notes', function (Blueprint $table): void {
            $table->id();
            $table->morphs('noteable'); // TimberCustomer / TimberSupplier
            $table->text('body');
            $table->string('created_by_user_id', 36)->nullable()->index();
            $table->foreign('created_by_user_id')->references('id')->on('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timber_contact_notes');
    }
};
