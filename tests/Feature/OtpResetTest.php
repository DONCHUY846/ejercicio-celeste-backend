<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Usuario;
use App\Models\Persona;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use App\Mail\OtpResetPasswordMail;

class OtpResetTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the full OTP password reset flow.
     */
    public function test_otp_reset_flow()
    {
        Mail::fake();
        // We use the file store in OtpService, so we should interact with it or let it run.
        // Mocking Cache facade might be tricky if we want to retrieve the value put by the service.
        // Instead of mocking Cache::shouldReceive, we can rely on the actual 'file' store or 'array' store if configured.
        // For testing, let's just let it use the configured store.
        
        // 1. Create User
        $persona = Persona::create([
            'nombre' => 'Jesus',
            'apellido_p' => 'Avina',
            'celular' => '1234567890',
            'activo' => true,
        ]);

        $email = 'jesus.avina.23s@utzmg.edu.mx';
        $user = Usuario::create([
            'id_persona' => $persona->id,
            'email' => $email,
            'pass' => Hash::make('oldpassword'),
            'admin' => false,
        ]);

        // 2. Request OTP
        $response = $this->postJson('/api/auth/forgot-password', [
            'email' => $email,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Si el correo existe, se ha enviado un código de recuperación.']);

        // Check Mailable
        Mail::assertSent(OtpResetPasswordMail::class, function ($mail) use ($email) {
            return $mail->hasTo($email);
        });

        // 3. Get OTP from Cache
        $key = 'otp_reset_' . $email;
        $otp = Cache::store('file')->get($key);
        $this->assertNotNull($otp, 'OTP should be in cache');

        // 4. Verify OTP (Invalid case)
        $response = $this->postJson('/api/auth/verify-otp-reset', [
            'email' => $email,
            'otp' => '000000', // Wrong OTP
        ]);
        $response->assertStatus(400);

        // 5. Verify OTP (Valid case)
        $response = $this->postJson('/api/auth/verify-otp-reset', [
            'email' => $email,
            'otp' => $otp,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Contraseña restablecida correctamente. Ahora puedes iniciar sesión con la nueva contraseña.']);

        // 6. Verify Password Change
        $user->refresh();
        $this->assertTrue(Hash::check('password123', $user->pass));

        // 7. Verify OTP is removed
        $this->assertNull(Cache::store('file')->get($key));
    }

    public function test_rate_limiting_forgot_password()
    {
        // Ideally we should test rate limiting, but it might be hard with 'array' cache driver in tests usually.
        // But let's try.
        
        $persona = Persona::create(['nombre' => 'Test', 'apellido_p' => 'Test', 'celular' => '123', 'activo' => true]);
        $user = Usuario::create([
            'id_persona' => $persona->id,
            'email' => 'test@test.com',
            'pass' => Hash::make('password'),
        ]);

        for ($i = 0; $i < 3; $i++) {
            $this->postJson('/api/auth/forgot-password', ['email' => 'test@test.com'])->assertStatus(200);
        }

        // 4th attempt should fail
        $this->postJson('/api/auth/forgot-password', ['email' => 'test@test.com'])->assertStatus(429);
    }
}
