<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class SignupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function messages() : array
    {
        return [
            'name.required' => 'El nombre es obligatorio',
            'email.required' => 'El email es obligatorio',
            'email.email' => 'El email no es válido',
            'email.unique' => 'Este correo ya está registrado',
            'password.required' => 'La contraseña es obligatoria',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'password.min' => 'La cotraseña debe tener al menos :min carácteres',
            'password.letters' => 'La contraseña debe tener al menos 1 letra',
            'password.mixed' => 'La contraseña debe tener al menos 1 mayúscula y letra minúscula',
            'password.symbols' => 'La contraseña debe tener al menos un caracter especial(@_-.*)',
            'password.numbers' => 'La contraseña debe tener al menos 1 número',
            'password.uncompromised' => 'la contraseña ha  aparecido en filtraciones de datos elige una mas segura '
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required','string'],
            'email' => ['required','email','unique:users,email'], //tabla users columna email
            'password' => ['required','confirmed', 
            Password::min(8) // minimo 4 caracteres
             ->letters()  //aqui le digo que al menos una letra
            ->mixedCase() // que tenga al menos una Mayuscula , minuscula
            ->symbols()  // la contraseña debe tener al menos un caracter especial
            ->numbers() //la contraseña debe tener al menos 1 numero
            ->uncompromised() // la contraseña no puede estar comprometida en filtraciones de datos
            ]
        ];
    }
}
