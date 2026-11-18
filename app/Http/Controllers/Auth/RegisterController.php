<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\SignupRequest; //form request de register
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function index(){
        return view('auth.register');
    }

    public function store(SignupRequest $request){
        
        // $name= $request->input('name');
        // $email= $request->input('email'); //metodo antiguo nunca se ocupa

        $data =$request->validated();
    
        //Almacene en la base de datos
        $user = User::create($data);
        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('verification.notice');
        
    }
}
