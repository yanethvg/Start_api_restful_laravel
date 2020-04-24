@component('mail::message')
# Hola {{$user->name}}
Has cambiado tu correo electronico. Por favor verifica la nueva dirección usando el siguiente boton: 

@component('mail::button', ['url' => route('verify',$user->verification_token)])
Confirmar mi cuenta
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
