<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\SignupRequest; //form request de register

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
        User::create($data);
        // return "Hola: $name, E-mail: $email";
    }
}
