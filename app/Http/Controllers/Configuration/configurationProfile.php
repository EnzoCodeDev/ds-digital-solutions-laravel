<?php

namespace App\Http\Controllers\Configuration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\UserSearchInfo;
use App\Models\User;

class configurationProfile extends Controller
{
    public function updatePassword(Request $request, $uuid)
    {
        //Validar que vengan los datos
        $request->validate([
            'password' => ['required', 'string', 'min:6'],
            'passwordNew' => ['required', 'string', 'min:6'],
            'passwordConfir' => ['required', 'string', 'min:6'],
        ]);
        //Verificar que las contraseñas sean iguales
        if ($request->passwordNew === $request->passwordConfir) {
            //Vereficar si la contraseña que ingreso es la misma que tiene asignada
            if (Hash::check($request->password, \Auth::user()->password)) {
                User::where('uuid', $uuid)
                    ->update(['password' => bcrypt($request->passwordNew)]);
                $userUpdated = User::where('uuid', $uuid)->get()->first();
                return response()->json([
                    'res' => 'update_user',
                    'newUser' => $userUpdated,
                ], 200);
            } else {
                return response()->json([
                    'res' => 'failed_password_verify',
                ], 200);
            };
        } else {
            return response()->json([
                'res' => 'failed_password_coincidir',
            ], 200);
        };
    }
    public function updateProfile(Request $request, $uuid)
    {
        //Validaciones del lado del backend en el nombre
        if ($request->nombre === null) {
            return response()->json([
                'res' => 'not_found_name',
            ], 200);
        };
        if (strlen($request->nombre) < 2) {
            return response()->json([
                'res' => 'not_large',
            ], 200);
        };
        //Validaciones del lado del backend en el img_url
        if ($request->imgLink === null) {
            return response()->json([
                'res' => 'not_found_url',
            ], 200);
        };
        if (strlen($request->imgLink) < 10) {
            return response()->json([
                'res' => 'not_large_url',
            ], 200);
        };
        //Guardar nuevo nombre
        User::where('uuid', $uuid)
            ->update(['name' => $request->nombre]);
        //Verificar el link de la imagen
        if ($request->imgLink !== null) {
            User::where('uuid', $uuid)
                ->update(['img_header' => $request->imgLink]);
        };
        $userUpdated = User::where('uuid', $uuid)->get()->first();
        return response()->json([
            'res' => 'update_user',
            'newUser' => $userUpdated,
        ], 200);
    }
    public function updateInfoSearch(Request $request, $uuid)
    {
        $userSeach = UserSearchInfo::create([
            'identify' => $request->all()['cc'],
            'name' => $request->all()['name']
        ]);
        return response()->json([
            'res' => 'ok',
            'userSeach' => $userSeach
        ], 200);
    }
}
