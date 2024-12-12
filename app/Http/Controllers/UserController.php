<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\UserCredentialsMail;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
        ]);

        $password = Str::random(10);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($password),
        ]);

        Mail::to($user->email)->send(new UserCredentialsMail($user, $password));

        return redirect()->route('users.index')->with('success', 'Usuario creado con éxito.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($request->all());

        return redirect()->route('users.index')->with('success', 'Usuario actualizado con éxito.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuario eliminado con éxito.');
    }

    public function generatePassword(User $user)
    {
        try {
            $newPassword = Str::random(10);
            $user->password = Hash::make($newPassword);
            $user->save();

            Mail::to($user->email)->send(new \App\Mail\NewPasswordMail($newPassword));

            return response()->json(['success' => true, 'message' => 'Contraseña generada con éxito.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Hubo un problema al generar la nueva contraseña.']);
        }
    }
}