<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Roles 
        $admin = Role::create(['name' => 'admin']);
        $usuario = Role::create(['name' => 'usuario']);
        $empleado = Role::create(['name' => 'empleado']);
        $secretaria = Role::create(['name' => 'secretaria']);
        $guarda = Role::create(['name' => 'guarda']);
        //Permisos
        Permission::create(['name' => 'create'])->syncRoles([$admin]);
        Permission::create(['name' => 'update '])->syncRoles([$admin, $usuario]);
        Permission::create(['name' => 'delete'])->syncRoles($admin,  $empleado, $guarda);
        Permission::create(['name' => 'ver'])->syncRoles([$admin,  $empleado, $secretaria, $guarda]);
    }
}
