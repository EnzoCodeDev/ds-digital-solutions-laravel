<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Ramsey\Uuid\Uuid;

class RoleSedder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $uuid = Uuid::uuid1();
        $uuid2 = Uuid::uuid4();
        $admin = Role::create(['name' => 'Administrador', 'description' => 'Este es el administrador', 'uuid' => $uuid . $uuid2]);
        $RolePrueba = Role::create(['name' => 'Role prueba', 'description' => 'Este es un usuario normal', 'uuid' => $uuid . $uuid2]);
        Permission::create(['name' => 'access parametrizacion', 'description' => 'Ver el menú parametrización', 'seccion' => 'menu'])->syncRoles([$admin]);
        Permission::create(['name' => 'accessIndex parametrizacion', 'description' => 'Acceso a la parametrización', 'seccion' => 'submenu'])->syncRoles([$admin]);
        Permission::create(['name' => 'view parametrizacion', 'description' => 'Ver un documento parametrizado', 'seccion' => 'parametrizacion'])->syncRoles([$admin]);
        Permission::create(['name' => 'create parametrizacion', 'description' => 'Parametrizar un documento', 'seccion' => 'parametrizacion'])->syncRoles([$admin]);
        Permission::create(['name' => 'edit parametrizacion', 'description' => 'Editar un documento parametrizado', 'seccion' => 'parametrizacion'])->syncRoles([$admin]);
        Permission::create(['name' => 'access proceso', 'description' => 'Acceso a los procesos', 'seccion' => 'submenu'])->syncRoles([$admin]);
        Permission::create(['name' => 'view proceso', 'description' => 'Ver un proceso', 'seccion' => 'proceso'])->syncRoles([$admin]);
        Permission::create(['name' => 'create proceso', 'description' => 'Crear un proceso', 'seccion' => 'proceso'])->syncRoles([$admin]);
        Permission::create(['name' => 'edit proceso', 'description' => 'Editar un proceso', 'seccion' => 'proceso'])->syncRoles([$admin]);
        Permission::create(['name' => 'access subproceso', 'description' => 'Acceso a los sub procesos', 'seccion' => 'submenu'])->syncRoles([$admin]);
        Permission::create(['name' => 'view subproceso', 'description' => 'Ver un sub proceso', 'seccion' => 'subproceso'])->syncRoles([$admin]);
        Permission::create(['name' => 'create subproceso', 'description' => 'Crear un sub proceso', 'seccion' => 'subproceso'])->syncRoles([$admin]);
        Permission::create(['name' => 'edit subproccess', 'description' => 'Editar un sub proceso', 'seccion' => 'subproceso'])->syncRoles([$admin]);
        Permission::create(['name' => 'acceso datos', 'description' => 'Ver el menú datos', 'seccion' => 'menu'])->syncRoles([$admin]);
        Permission::create(['name' => 'acceso maestroinfor', 'description' => 'Acceso a maestro de información', 'seccion' => 'submenu'])->syncRoles([$admin, $RolePrueba]);
        Permission::create(['name' => 'deligenciar document', 'description' => 'Deligenciar un documento', 'seccion' => 'maestro informacion'])->syncRoles([$admin, $RolePrueba]);
        Permission::create(['name' => 'view deligenciar document', 'description' => 'Ver un documento deligenciado', 'seccion' => 'maestro informacion'])->syncRoles([$admin, $RolePrueba]);
    }
}
