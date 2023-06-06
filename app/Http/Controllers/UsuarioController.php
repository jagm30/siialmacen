<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $usuarios = User::all();     
        //return $usuarios;          
        return view('usuarios.index', compact('usuarios')); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        //$request->user()->authorizeRoles(['admin']); 
        $user = User::create([
            'name'          => $request->nombre,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'tipo_usuario'  => $request->tipo_usuario,
        ]);
        return json_encode(array(
            "Estado"=>"Agregado correctamente"
        ));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if ($request->ajax()) {
            $usuario = DB::table('users')
                    ->where('users.id',$id)
                    ->first();
            return json_encode($usuario);
        }else{
            $usuario = User::find($id);
            return $usuario;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function edit(Usuario $usuario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Usuario $usuario)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */

    public function edicion(Request $request ,$id_usuario ,$nombre ,$email ,$password ,$tipo_usuario)
    { 
        if ($password === 'ninguno'){
            $usuario = User::find($id_usuario);
            $usuario->name             = $nombre;
            $usuario->email            = $email;
            $usuario->tipo_usuario     = $tipo_usuario;
            $usuario->save();
            return response()->json(['data' => "Cambios guardados correctamente..."]);
        }else{
            $usuario = User::find($id_usuario);
            $usuario->name             = $nombre;
            $usuario->email            = $email;
            $usuario->tipo_usuario     = $tipo_usuario;
            $usuario->password         =  Hash::make($password);
            $usuario->save();
            return response()->json(['data' => "Cambios guardados correctamente..."]);    
        }         
    }
    public function destroy($id)
    {
        $usuario   = User::findOrFail($id);
        $usuario->delete();
        return response()->json(['data' => "Eliminado correctamente..."]);
    }
}
