<?php

namespace App\Http\Controllers\User;

use App\User;
use App\Mail\UserCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\ApiController;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();

        return $this->showAll($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $reglas = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ];

        $this->validate($request,$reglas);

        $campos = $request->all();
        $campos['password'] = bcrypt($request->password);
        $campos['verified'] = User::USUARIO_NO_VERIFICADO;
        $campos['verification_token'] = User::generarVerificationToken();
        $campos['admin'] = User::USUARIO_REGULAR;

        $user = User::create($campos);
        Mail::to($user)->send(new UserCreated($user));
           
        return $this->showOne($user,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //$usuario = User::findOrFail($id);

        return $this->showOne($user,200);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //$usuario = User::findOrFail($id);
        //dd($request);
        $reglas = [
            'email' => 'email|unique:users,email,' . $user->id,
            'password' => 'min:6|confirmed',
            'admin' => 'in:'. User::USUARIO_ADMINISTRADOR . ',' . User::USUARIO_REGULAR,
        ];

        $this->validate($request,$reglas);

        if($request->has('name')){
            $user->name = $request->name;
        }
        if($request->has('email') && $user->email != $request->email){
            $campos['verified'] = User::USUARIO_NO_VERIFICADO;
            $campos['verification_token'] = User::generarVerificationToken(); 
            $user->email = $request->email;
        }
        if($request->has('password')){
            $user->password = bcrypt($request->password);
        }
        if($request->has('admin')){
            if(!$user->esVerificado()){
                return $this->errorResponse('Unicamente los usuarios verificados pueden cambiar su valor de administrador',409);
            }
            $user->admin = $request->admin;
        }
        
        if(!$user->isDirty())
        {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar',422);
        }
        $user->save();

        return $this->showOne($user,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
       // $usuario = User::findOrFail($id);

        $user->delete();
        
        return $this->showOne($user,200);

    }
    public function verify($token)
    {
        $user = User::where('verification_token',$token)->firstOrFail();
        $user->verified = User::USUARIO_VERIFICADO;
        //PARA EVITAR QUE SE SIGA VERIFICANDO
        $user->verification_token = null;
        $user->save();
        return $this->showMessage('La cuenta ha sido verificada');
    }
}
