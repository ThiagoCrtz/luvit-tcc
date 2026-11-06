<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
{
    $request->validate([
        'name'            => 'required|string|max:255',
        'arroba'          => 'required|string|regex:/^[a-zA-Z0-9_]{3,20}$/|unique:table_users,arroba', 
        'email'           => 'required|string|email|max:255|unique:table_users,email',
        'password'        => 'required|string|min:8|confirmed',
        'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120', 
        'bio'             => 'nullable|string|max:500',
    ], [
        'profile_picture.image' => 'O arquivo deve ser uma imagem.',
        'profile_picture.mimes' => 'A imagem deve ser do tipo: jpeg, png, jpg, gif ou webp.',
        'profile_picture.max' => 'A imagem não pode ter mais de 5MB.',
    ]);
    
    $profilePicturePath = null;
    if ($request->hasFile('profile_picture')) {
        $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
    }
    
    $user = User::create([
        'name'            => $request->name,
        'arroba'          => $request->arroba, 
        'email'           => $request->email,
        'password'        => Hash::make($request->password),
        'profile_picture' => $profilePicturePath,
        'bio'             => $request->bio,
    ]);
    
    auth()->login($user);
    $request->session()->regenerate();
    
    return redirect()->route('home')->with('success', 'Conta criada com sucesso!');
}
}
