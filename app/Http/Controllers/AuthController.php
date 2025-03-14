<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
 // Método para registrar usuarios
 public function register(Request $request)
 {
  // Validar los datos del request
  $validated = $request->validate([
   'name'     => 'required|string|max:255',
   'email'    => 'required|string|email|max:255|unique:users',
   'password' => 'required|string|min:6|confirmed',
  ]);

  // Crear el usuario
  $user = User::create([
   'name'     => $validated['name'],
   'email'    => $validated['email'],
   'password' => Hash::make($validated['password']),
  ]);

  // Crear token de acceso para el usuario
  $token = $user->createToken('auth_token')->plainTextToken;

  return response()->json([
   'access_token' => $token,
   'token_type'   => 'Bearer',
  ]);
 }

 // Método para iniciar sesión
 public function login(Request $request)
 {
  $request->validate([
   'email'    => 'required|string|email',
   'password' => 'required|string',
  ]);

  $user = User::where('email', $request->email)->first();

  if (!$user || !Hash::check($request->password, $user->password)) {
   throw ValidationException::withMessages([
    'email' => ['Credenciales inválidas.'],
   ]);
  }

  $token = $user->createToken('auth_token')->plainTextToken;

  return response()->json([
   'access_token' => $token,
   'token_type'   => 'Bearer',
  ]);
 }

 // Método para cerrar sesión (revocar token)
 public function logout(Request $request)
 {
  // Revoca el token actual del usuario autenticado
  $request->user()->currentAccessToken()->delete();

  return response()->json([
   'message' => 'Sesión cerrada correctamente',
  ]);
 }
}
