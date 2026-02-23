<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Persona;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerificationMail;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido_p' => 'required|string|max:255',
            'apellido_m' => 'nullable|string|max:255',
            'celular' => 'required|numeric',
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        return DB::transaction(function () use ($request) {
            $persona = Persona::create([
                'nombre' => $request->nombre,
                'apellido_p' => $request->apellido_p,
                'apellido_m' => $request->apellido_m,
                'celular' => $request->celular,
                'activo' => false,
            ]);

            $token = Str::random(64);

            $usuario = Usuario::create([
                'id_persona' => $persona->id,
                'email' => $request->email,
                'pass' => Hash::make($request->password),
                'admin' => false,
                'verification_token' => $token,
                'verification_token_expires_at' => Carbon::now()->addHours(24),
            ]);

            $verificationUrl = url("/api/email/verify/{$usuario->id}/{$token}");

            Mail::to($usuario->email)->send(new EmailVerificationMail($usuario, $verificationUrl));

            return response()->json([
                'message' => 'Usuario registrado correctamente. Por favor verifica tu correo electrónico.',
                'user' => $usuario->load('persona'),
            ], 201);
        });
    }

    public function verifyEmail($id, $token)
    {
        $usuario = Usuario::findOrFail($id);
        $frontendUrl = env('FRONTEND_URL', 'http://localhost:5173');

        if ($usuario->email_verified_at) {
            return redirect($frontendUrl . '/email-verified?verified=true');
        }

        if ($usuario->verification_token !== $token) {
            return redirect($frontendUrl . '/email-verified?verified=false&error=invalid_token');
        }

        if (Carbon::now()->greaterThan($usuario->verification_token_expires_at)) {
            return redirect($frontendUrl . '/email-verified?verified=false&error=token_expired');
        }

        $usuario->email_verified_at = Carbon::now();
        $usuario->verification_token = null;
        $usuario->verification_token_expires_at = null;
        $usuario->save();

        // Redirigir al frontend
        return redirect($frontendUrl . '/email-verified?verified=true');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $usuario = Usuario::where('email', $request->email)->first();

        if (! $usuario || ! Hash::check($request->password, $usuario->pass)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        if (! $usuario->email_verified_at) {
            throw ValidationException::withMessages([
                'email' => ['Por favor, verifica tu correo electrónico antes de iniciar sesión.'],
            ]);
        }

        $token = $usuario->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $usuario->load('persona'),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Sesión cerrada correctamente']);
    }

    public function me(Request $request)
    {
        return response()->json($request->user()->load('persona'));
    }
}
