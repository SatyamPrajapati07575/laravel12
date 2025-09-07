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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('gender', ['male', 'female', 'other'])->after('password');
            $table->unsignedBigInteger('country_id')->nullable()->after('gender');
            $table->unsignedBigInteger('city_id')->nullable()->after('country_id');
            $table->enum('role',['user', 'admin'])->default('user')->after('city_id');
            $table->boolean('terms')->default(false)->after('role');

            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
             $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['country_id']);
            $table->dropForeign(['city_id']);
            $table->dropColumn(['gender','country_id','city_id','role','terms']);
        });
    }
};
