<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Agrega la columna rol_id a la tabla usuarios y crea su llave foránea.
     */
    public function up(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->foreignId('rol_id')
                ->nullable()
                ->after('id_persona')
                ->constrained('roles', 'id');
        });
    }

    /**
     * Revierte la adición de la columna rol_id.
     */
    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropConstrainedForeignId('rol_id');
        });
    }
};
