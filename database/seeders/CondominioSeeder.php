<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\Models\Persona;
use App\Models\Rol;
use App\Models\Departamento;
use App\Models\TipoPago;
use App\Models\Motivo;
use App\Models\Evento;
use App\Models\Gasto;
use App\Models\Usuario;
use App\Models\PersonaDepartamento;
use App\Models\Carro;
use App\Models\Control;
use App\Models\Pregunta;
use App\Models\Reporte;
use App\Models\Asistencia;
use App\Models\Mensaje;
use App\Models\Respuesta;
use App\Models\Pago;
use App\Models\Mantenimiento;

class CondominioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('es_ES');


        // Roles
        $roles = ['Administrador', 'Propietario', 'Inquilino', 'Visitante', 'Seguridad'];
        foreach ($roles as $rol) {
            Rol::firstOrCreate(['rol' => $rol]);
        }
        $rolAdmin = Rol::where('rol', 'Administrador')->first();
        $rolProp = Rol::where('rol', 'Propietario')->first();
        $rolInq = Rol::where('rol', 'Inquilino')->first();

        // Tipos de Pago
        $tiposPago = ['Efectivo', 'Transferencia Bancaria', 'Cheque', 'Tarjeta de Crédito'];
        foreach ($tiposPago as $tipo) {
            TipoPago::firstOrCreate(['tipo' => $tipo]);
        }

        // Motivos
        $motivos = ['Cuota de Mantenimiento', 'Multa por Ruido', 'Reserva de Salón', 'Reposición de Tarjeta', 'Multa por Estacionamiento'];
        foreach ($motivos as $motivo) {
            Motivo::firstOrCreate(['motivo' => $motivo]);
        }

        // Departamentos
        $departamentos = [];
        for ($i = 1; $i <= 5; $i++) { 
            for ($j = 1; $j <= 4; $j++) { 
                $num = $i . '0' . $j;
                $departamentos[] = Departamento::create([
                    'departamento' => 'Depto ' . $num,
                    'moroso' => $faker->boolean(20), 
                    'codigo' => strtoupper($faker->bothify('??#??')),
                ]);
            }
        }

        // Personas
        $personas = [];
        for ($i = 0; $i < 30; $i++) {
            $personas[] = Persona::create([
                'nombre' => $faker->firstName,
                'apellido_p' => $faker->lastName,
                'apellido_m' => $faker->lastName,
                'celular' => $faker->numerify('##########'),
                'activo' => true,
            ]);
        }

        // Eventos
        $eventos = [];
        for ($i = 0; $i < 5; $i++) {
            $eventos[] = Evento::create([
                'fecha' => $faker->dateTimeBetween('-1 month', '+2 months'),
                'descripcion' => $faker->sentence(4),
            ]);
        }

        // Gastos
        for ($i = 0; $i < 10; $i++) {
            Gasto::create([
                'monto' => $faker->randomFloat(2, 100, 5000),
                'descripcion' => $faker->sentence(3),
                'fecha' => $faker->date(),
            ]);
        }


        // Usuarios (Crear usuarios para las primeras 10 personas)
        $usuarios = [];
        foreach (array_slice($personas, 0, 10) as $index => $persona) {
            $usuarios[] = Usuario::create([
                'id_persona' => $persona->id,
                'pass' => Hash::make('password123'),
                'admin' => $index === 0, 
            ]);
        }

        // Persona - Departamento 
        foreach ($departamentos as $depa) {
            $habitantes = $faker->randomElements($personas, $faker->numberBetween(1, 2));
            foreach ($habitantes as $persona) {
                if (!PersonaDepartamento::where('id_persona', $persona->id)->where('id_depa', $depa->id)->exists()) {
                     PersonaDepartamento::create([
                        'id_persona' => $persona->id,
                        'id_depa' => $depa->id,
                        'id_rol' => $faker->randomElement([$rolProp->id, $rolInq->id]),
                        'residente' => true,
                        'codigo' => $faker->numerify('####'),
                    ]);
                }
            }
        }

        // Carros
        foreach ($departamentos as $depa) {
            if ($faker->boolean(70)) { 
                Carro::create([
                    'id_depa' => $depa->id,
                    'placa' => strtoupper($faker->bothify('???-###')),
                    'marca' => $faker->word,
                    'modelo' => $faker->year,
                    'color' => $faker->colorName,
                ]);
            }
        }

        // Controles
        foreach ($departamentos as $depa) {
            Control::create([
                'codigo' => strtoupper($faker->bothify('CTRL-####')),
                'id_depa' => $depa->id,
            ]);
        }

        // Preguntas (para eventos)
        $preguntas = [];
        foreach ($eventos as $evento) {
            $preguntas[] = Pregunta::create([
                'pregunta' => "¿Asistirá usted al evento " . substr($evento->descripcion, 0, 20) . "?",
                'id_evento' => $evento->id,
            ]);
        }

        // Reportes
        $reportes = [];
        foreach ($usuarios as $usuario) {
            if ($faker->boolean(30)) {
                $reportes[] = Reporte::create([
                    'id_usuario' => $usuario->id,
                    'reporte' => $faker->paragraph,
                    'fecha' => $faker->dateTimeBetween('-1 month', 'now'),
                ]);
            }
        }

        // 3. TABLAS CON DEPENDENCIAS COMPLEJAS

        // Asistencia
        $asistencias = [];
        foreach ($eventos as $evento) {
            $asistentes = $faker->randomElements($personas, $faker->numberBetween(3, 10));
            foreach ($asistentes as $persona) {
                $asistencias[] = Asistencia::create([
                    'id_persona' => $persona->id,
                    'id_evento' => $evento->id,
                    'hora' => $faker->time(),
                ]);
            }
        }

        // Mensajes
        for ($i = 0; $i < 20; $i++) {
            $remitente = $faker->randomElement($personas);
            $destinatario = $faker->randomElement($personas);
            // Asegurar que sean diferentes
            while ($remitente->id === $destinatario->id) {
                $destinatario = $faker->randomElement($personas);
            }

            Mensaje::create([
                'remitente' => $remitente->id,
                'destinatario' => $destinatario->id,
                'id_depaA' => $faker->randomElement($departamentos)->id, // Simplificación: depa aleatorio
                'id_depaB' => $faker->randomElement($departamentos)->id,
                'mensaje' => $faker->sentence,
                'fecha' => $faker->dateTimeBetween('-1 month', 'now'),
            ]);
        }

        // Respuestas
        foreach ($asistencias as $asistencia) {
            // Buscar preguntas del evento de esta asistencia
            $preguntasEvento = Pregunta::where('id_evento', $asistencia->id_evento)->get();
            foreach ($preguntasEvento as $pregunta) {
                Respuesta::create([
                    'id_pregunta' => $pregunta->id,
                    'id_asistente' => $asistencia->id,
                    'respuesta' => $faker->boolean,
                ]);
            }
        }

        // Pagos
        $pagos = [];
        foreach ($departamentos as $depa) {
            // Crear algunos pagos para cada departamento
            for ($k = 0; $k < $faker->numberBetween(1, 3); $k++) {
                $pagos[] = Pago::create([
                    'id_depa' => $depa->id,
                    'monto' => $faker->randomFloat(2, 500, 3000),
                    'id_tipo' => TipoPago::inRandomOrder()->first()->id,
                    'fecha' => $faker->date(),
                    'id_motivo' => Motivo::inRandomOrder()->first()->id,
                    'descripcion' => $faker->sentence,
                    'comprobante' => 'comprobante_' . $faker->uuid . '.pdf',
                    'efectuado' => $faker->boolean(80),
                    'id_reporte' => $faker->boolean(10) && count($reportes) > 0 ? $faker->randomElement($reportes)->id : null,
                ]);
            }
        }


        // Mantenimiento
        foreach ($departamentos as $depa) {
            // Crear registros de mantenimiento para los últimos 3 meses
            for ($m = 0; $m < 3; $m++) {
                $fecha = now()->subMonths($m);
                // Buscar si hay un pago asociado
                $pago = Pago::where('id_depa', $depa->id)->inRandomOrder()->first();
                
                Mantenimiento::create([
                    'mes' => $fecha->month,
                    'anio' => $fecha->year,
                    'id_depa' => $depa->id,
                    'completado' => $faker->boolean(90),
                    'monto' => 1500.00, // Cuota fija ejemplo
                    'id_pago' => $pago ? $pago->id : null,
                ]);
            }
        }
    }
}
