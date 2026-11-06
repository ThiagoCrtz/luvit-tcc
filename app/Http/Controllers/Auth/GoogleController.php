<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        if (!config('services.google.client_id') || !config('services.google.client_secret')) {
            return redirect()->route('login.form')->with('error', 'O login Google ainda não foi configurado. Entre com seu e-mail e senha.');
        }
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                if (empty($user->arroba)) {
                    $user->arroba = $this->generateUniqueArroba($googleUser->getName());
                    $user->save();
                }
                
                if (is_null($user->email_verified_at)) {
                    $user->email_verified_at = now();
                    $user->save();
                }
            } else {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'arroba' => $this->generateUniqueArroba($googleUser->getName()),
                    'password' => Hash::make(Str::random(24)),
                    'email_verified_at' => now(),
                    'profile_picture' => $googleUser->getAvatar(),
                ]);
            }

            Auth::login($user, true);
            request()->session()->regenerate();

            return redirect()->route('home'); 

        } catch (Exception $e) {
            return redirect()->route('login.form')
                ->with('error', 'Erro ao fazer login com Google. Tente novamente.');
        }
    }

    private function generateUniqueArroba($name)
    {
        $baseArroba = substr(Str::slug($name, '') ?: 'usuario', 0, 14);
        $arroba = $baseArroba;
        $counter = 1;

        while (User::where('arroba', $arroba)->exists()) {
            $arroba = $baseArroba . $counter;
            $counter++;
        }

        return $arroba;
    }
}