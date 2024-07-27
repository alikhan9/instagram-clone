<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use Intervention\Image\Facades\Image;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed','max:255',Rules\Password::defaults()],
            'phone' => ['required', 'unique:users' ,'regex:/^[0-9]{10}$/'],
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ], [
            'name.required' => 'Le champ nom est requis.',
            'username.required' => "Le champ nom d'utilisateur est requis.",
            'username.unique' => "Ce pseudo n'est pas disponible.",
            'email.required' => "Le champ e-mail est requis.",
            'email.email' => "L'adresse e-mail doit être une adresse e-mail valide.",
            'email.unique' => "L'adresse e-mail est déjà utilisée.",
            'password.required' => 'Le champ mot de passe est requis.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'password.min' => 'Le mot de passe doit comporter au moins 8 caractères.',
            'phone.required' => 'Le champ téléphone est requis.',
            'phone.regex' => 'Le numéro de téléphone doit être de 10 chiffres.',
            'phone.unique' => "Ce numéro de téléphone n'est pas disponible.",
            'avatar.required' => "Le champ d'avatar est requis.",
            'avatar.image' => "L'avatar doit être une image.",
            'avatar.mimes' => "L'avatar doit être un fichier de type : jpeg, png, jpg, gif, svg.",
            'avatar.max' => "L'avatar ne peut pas dépasser 2 Mo."
        ]);

        $image = $request->files->get('avatar');
        $filename = uniqid() . '.webp';

        $optimizedImageMedium = Image::make($image)->encode('webp', 80);

        // Save the optimized images
        Storage::disk('public')->put('images/avatar_' . $filename, $optimizedImageMedium);

        $avatar = '/storage/images/avatar_' . $filename;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'username' => $request->username,
            'phone' => $request->phone,
            'avatar' => $avatar
        ]);

        event(new Registered($user));

        Auth::login($user);

        return to_route('home');
    }
}
