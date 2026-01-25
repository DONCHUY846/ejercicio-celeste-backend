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
        // 1. TABLAS INDEPENDIENTES
        
        Schema::create('personas', function (Blueprint $table) {
            $table->id('id'); 
            $table->string('nombre');
            $table->string('apellido_p');
            $table->string('apellido_m')->nullable();
            $table->decimal('celular', 15, 0); 
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->id('id');
            $table->string('rol');
        });

        Schema::create('departamentos', function (Blueprint $table) {
            $table->id('id');
            $table->string('departamento');
            $table->boolean('moroso')->default(false);
            $table->string('codigo', 5);
        });

        Schema::create('tipos_pago', function (Blueprint $table) {
            $table->id('id');
            $table->string('tipo');
        });

        Schema::create('motivos', function (Blueprint $table) {
            $table->id('id');
            $table->string('motivo');
        });

        Schema::create('eventos', function (Blueprint $table) {
            $table->id('id');
            $table->dateTime('fecha');
            $table->string('descripcion');
        });

        Schema::create('gastos', function (Blueprint $table) {
            $table->id('id');
            $table->decimal('monto', 18, 2);
            $table->string('descripcion');
            $table->date('fecha');
        });

        // 2. TABLAS CON DEPENDENCIAS DE PRIMER NIVEL

        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('id_persona')->unique()->constrained('personas', 'id')->onDelete('cascade');
            $table->string('pass');
            $table->boolean('admin')->default(false);
        });

        // Tabla Pivote (Muchos a Muchos)
        Schema::create('per_dep', function (Blueprint $table) {
            $table->foreignId('id_persona')->constrained('personas', 'id');
            $table->foreignId('id_depa')->constrained('departamentos', 'id');
            $table->foreignId('id_rol')->constrained('roles', 'id');
            $table->boolean('residente')->default(false);
            $table->string('codigo')->nullable();
            
            $table->primary(['id_persona', 'id_depa', 'id_rol']);
        });

        Schema::create('carros', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('id_depa')->constrained('departamentos', 'id');
            $table->string('placa', 20);
            $table->string('marca', 100)->nullable();
            $table->string('modelo', 100)->nullable();
            $table->string('color', 50)->nullable();
        });

        Schema::create('controles', function (Blueprint $table) {
            $table->id('id');
            $table->string('codigo');
            $table->foreignId('id_depa')->constrained('departamentos', 'id');
        });

        Schema::create('preguntas', function (Blueprint $table) {
            $table->id('id');
            $table->string('pregunta');
            $table->foreignId('id_evento')->constrained('eventos', 'id');
        });

        Schema::create('reportes', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('id_usuario')->constrained('usuarios', 'id');
            $table->string('reporte');
            $table->timestamp('fecha')->useCurrent();
        });

        // 3. TABLAS CON DEPENDENCIAS COMPLEJAS

        Schema::create('asistencia', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('id_persona')->constrained('personas', 'id');
            $table->foreignId('id_evento')->constrained('eventos', 'id');
            $table->time('hora');
        });

        Schema::create('mensajes', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('remitente')->constrained('personas', 'id');
            $table->foreignId('destinatario')->constrained('personas', 'id');
            
            $table->foreignId('id_depaA')->constrained('departamentos', 'id');
            $table->foreignId('id_depaB')->constrained('departamentos', 'id');
            
            $table->string('mensaje');
            $table->timestamp('fecha')->useCurrent();
        });

        Schema::create('respuestas', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('id_pregunta')->constrained('preguntas', 'id');
            $table->foreignId('id_asistente')->constrained('asistencia', 'id'); 
            $table->boolean('respuesta');
        });

        Schema::create('pagos', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('id_depa')->constrained('departamentos', 'id');
            $table->decimal('monto', 18, 2);
            $table->foreignId('id_tipo')->constrained('tipos_pago', 'id');
            $table->date('fecha');
            $table->foreignId('id_motivo')->constrained('motivos', 'id');
            $table->string('descripcion')->nullable();
            $table->text('comprobante')->nullable(); 
            $table->boolean('efectuado')->default(false);
            $table->foreignId('id_reporte')->nullable()->constrained('reportes', 'id'); 
        });

        // 4. TABLA FINAL
        
        Schema::create('mantenimiento', function (Blueprint $table) {
            $table->id('id');
            $table->integer('mes');
            $table->integer('anio'); 
            $table->foreignId('id_depa')->constrained('departamentos', 'id');
            $table->boolean('completado')->default(false);
            $table->decimal('monto', 18, 2);
            $table->foreignId('id_pago')->nullable()->constrained('pagos', 'id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mantenimiento');
        Schema::dropIfExists('pagos');
        Schema::dropIfExists('respuestas');
        Schema::dropIfExists('mensajes');
        Schema::dropIfExists('asistencia');
        Schema::dropIfExists('reportes');
        Schema::dropIfExists('preguntas');
        Schema::dropIfExists('controles');
        Schema::dropIfExists('carros');
        Schema::dropIfExists('per_dep');
        Schema::dropIfExists('usuarios');
        Schema::dropIfExists('gastos');
        Schema::dropIfExists('eventos');
        Schema::dropIfExists('motivos');
        Schema::dropIfExists('tipos_pago');
        Schema::dropIfExists('departamentos');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('personas');
    }
};