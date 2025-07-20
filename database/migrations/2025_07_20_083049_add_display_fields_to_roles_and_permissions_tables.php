<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->string('display_name')->nullable()->after('name');
            $table->text('description')->nullable()->after('display_name');
            $table->boolean('is_system')->default(false)->after('description');
            $table->unsignedBigInteger('parent_role_id')->nullable()->after('is_system');

            $table->foreign('parent_role_id')->references('id')->on('roles')->onDelete('set null');
        });

        Schema::table('permissions', function (Blueprint $table) {
            $table->string('display_name')->nullable()->after('name');
            $table->text('description')->nullable()->after('display_name');
            $table->boolean('is_system')->default(false)->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropForeign(['parent_role_id']);
            $table->dropColumn(['display_name', 'description', 'is_system', 'parent_role_id']);
        });

        Schema::table('permissions', function (Blueprint $table) {
            $table->dropColumn(['display_name', 'description', 'is_system']);
        });
    }
};
