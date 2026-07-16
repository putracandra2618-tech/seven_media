<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('task_tag', function (Blueprint $table) {
            if (!Schema::hasColumn('task_tag', 'tagged_at')) {
                $table->timestamp('tagged_at')->nullable()->after('updated_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('task_tag', function (Blueprint $table) {
            $table->dropColumn('tagged_at');
        });
    }
};
