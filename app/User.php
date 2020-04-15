<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    const USUARIO_VERIFICADO = '1';
    const USUARIO_NO_VERIFICADO = '0';

    const USUARIO_ADMINISTRADOR = 'true';
    const USUARIO_REGULAR ='false';

    protected $table ='users';
    //especificando que el atributo es de tipo fecha
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'verified',  
        'verification_token', 
        'admin',
    ];

    //mutador de Nombre
    public function setNameAttribute($valor)
    {
        $this->attributes['name'] = strtolower($valor);
    }
     //accesor para el nombre
    //se retorna el valor con transformacion sin necesidad de modificarlo
    public function getNameAttribute($valor)
    {
       // return ucfirst($valor);
        return ucwords($valor);
    }
    //mutador para el correo electronico
    public function setEmailAttribute($valor)
    {
        $this->attributes['email'] = strtolower($valor);
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 
        'remember_token',
        'verification_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function esVerificado()
    {
        return $this->verified == User::USUARIO_VERIFICADO;
    }
    public function esAdministrador()
    {
        return $this->verified == User::USUARIO_ADMINISTRADOR;
    }
    //se recomienda a partir de 24 caracteres por eso se coloca 40 porque es largo
    public static function generarVerificationToken()
    {
        return Str::random(40);
    }

}
