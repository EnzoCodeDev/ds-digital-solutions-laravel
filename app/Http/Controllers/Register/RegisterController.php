<?php

namespace App\Http\Controllers\Register;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\user;
use Ramsey\Uuid\Uuid;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        //Validar los datos del frontend
        $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'string', 'email', 'max:150'],
            'password' => ['required', 'string', 'min:7']
        ]);
        //Verificar que el email sea unico en la base de datos 
        if (user::where('email', $request->email)->exists()) {
            return response()->json([
                'res' => false,
                'response' => 'El email ya existe',
            ], 201);
        }
        $user = new User();
        $user->uuid = Uuid::uuid1();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
        return response()->json([
            'res' => true,
            'new user' => $user,
        ], 201);
    }
}
