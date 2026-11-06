<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProfileController extends Controller
{
    use AuthorizesRequests; 
    
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function show(User $user): View
    {
        $profileData = $this->userService->getProfileData($user);
        
        $user->following_count = $user->followingCount();
        $user->followers_count = $user->followersCount();
        $isFollowing = auth()->check() ? auth()->user()->isFollowing($user->id) : false;
        
        return view('profile.show', [
            'user' => $user,
            'likedMovies' => $profileData['likedMovies'],
            'watchlistMovies' => $profileData['watchlistMovies'],
            'likedGames' => $profileData['likedGames'],
            'watchlistGames' => $profileData['watchlistGames'],
            'reviews' => $profileData['reviews'],
            'lists' => $profileData['lists'],
            'isFollowing' => $isFollowing, 
        ]);
    }

    public function edit(User $user): View
    {
        $this->authorize('update', $user);
        return view('profile.edit', compact('user'));
    }

   public function update(Request $request, User $user): RedirectResponse
{
    $this->authorize('update', $user);

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'bio' => 'nullable|string|max:500',
        'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        'current_password' => 'nullable|required_with:new_password',
        'new_password' => 'nullable|min:8|confirmed',
    ], [
        'name.required' => 'O nome é obrigatório.',
        'name.max' => 'O nome não pode ter mais de 255 caracteres.',
        'bio.max' => 'A biografia não pode ter mais de 500 caracteres.',
        'profile_picture.image' => 'O arquivo deve ser uma imagem.',
        'profile_picture.mimes' => 'A imagem deve ser do tipo: jpeg, png, jpg, gif ou webp.',
        'profile_picture.max' => 'A imagem não pode ter mais de 5MB.',
        'current_password.required_with' => 'A senha atual é obrigatória para alterar a senha.',
        'new_password.min' => 'A nova senha deve ter pelo menos 8 caracteres.',
        'new_password.confirmed' => 'A confirmação da senha não corresponde.',
    ]);

    if ($request->filled('new_password') && !Hash::check($request->current_password, $user->password)) {
        return back()->withErrors(['current_password' => 'A senha atual está incorreta.'])->withInput();
    }

    $user->name = $validated['name'];
    $user->bio = $validated['bio'] ?? null;

    if ($request->hasFile('profile_picture')) {
        if ($user->profile_picture && !str_starts_with($user->profile_picture, 'https://')) {
            Storage::disk('public')->delete($user->profile_picture);
        }
        $path = $request->file('profile_picture')->store('profile_pictures', 'public');
        $user->profile_picture = $path;
    }

    if ($request->filled('new_password')) {
        $user->password = Hash::make($request->new_password);
    }

    $user->save();

    return redirect()
        ->route('profile.show', $user->arroba)
        ->with('success', 'Perfil atualizado com sucesso!');
}
}
