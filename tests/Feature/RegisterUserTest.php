<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use App\Notifications\VerifyEmail;
use Illuminate\Support\Facades\URL;

uses(refreshDatabase::class);


it('Show the registration screen', function(){
    $response= $this->get(route('register'));

    $response->assertOk();

    $response->assertStatus(200);
    $response->assertSee('Crear Cuenta');
    $response->assertSee('Registrarme');

    $response->assertSeeInOrder([
        'Crear Cuenta',
        'Registrarme'
    ]);
});// puede ser it o test , es lo mismo

it('registers a new user as unverified and dispatches the registered event', function(){

    Event::fake(); // simula el evento

    $response = $this->post(route('register.store'),[
        'name' => 'Juan Pérez',
        'email' => 'juan@juan.com',
        'password' => 'P$ssword123.',
        'password_confirmation' => 'P$ssword123.'
    ]);

    $response->assertRedirect(route('verification.notice'));

    $user = User::where('email', 'juan@juan.com')->first();

    expect($user)->not->toBeNull();
    expect($user->name)->toBe('Juan Pérez');
    expect($user->email)->toBe('juan@juan.com'); 
    expect($user->hasVerifiedEmail())->toBeFalse();   

    Event::assertDispatched(Registered::class); // se simula el evento de register, nunca nos llegara un email

});

it('should validate required fileds when the request body is empty', function(){
    $response = $this->post(route('register.store'),[]);

    $response->assertSessionHasErrors([
        'name',
        'email',
        'password'
    ]);

    $response->assertSessionHasErrors([
        'name' => 'El nombre es obligatorio',
        'email' => 'El email es obligatorio',
        'password' => 'La contraseña es obligatoria'
    ]);
});

it('prevents duplicate email addresses', function(){

    User::factory()->create([
        'email'=> 'juan@juan.com'
    ]);

        $response = $this->post(route('register.store'),[
        'name' => 'Juan Pérez',
        'email' => 'juan@juan.com',
        'password' => 'P$ssword123.',
        'password_confirmation' => 'P$ssword123.'
    ]);

    $response->assertRedirect();

    $response->assertSessionHasErrors([
        'email' => 'Este correo ya está registrado',
    ]);

});




it('sends the verification email notification after registration', function(){

    Notification::fake(); // simula el evento

    $response = $this->post(route('register.store'),[
        'name' => 'Juan Pérez',
        'email' => 'juan@juan.com',
        'password' => 'P$ssword123.',
        'password_confirmation' => 'P$ssword123.'
    ]);


    $user = User::where('email', 'juan@juan.com')->first();



    Notification::assertSentTo($user, VerifyEmail::class); // se simula el evento de register, nunca nos llegara un email

});


it('verifies the user email from a signed verification link', function(){

    $user = User::factory()->unverified()->create();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $user->id,
                'hash' => sha1($user->email),
            ]
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        $response->assertRedirect(route('dashboard'));

        //dd($user);
        expect($user->hasVerifiedEmail())->toBeTrue();

});


it('does not allow an unverified user to access the dashboard', function(){
    $user = User::factory()->unverified()->create();
    $response = $this->actingAs($user)->get(route('dashboard'));
    $response->assertRedirect(route('verification.notice'));

});

it('allow an verified user to access the dashboard', function(){
    $user = User::factory()->create([
        'email_verified_at' => now()
    ]);
    $response = $this->actingAs($user)->get(route('dashboard'));
    $response->assertOk();    
});