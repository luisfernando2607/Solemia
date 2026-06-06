<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'ver_reportes',
            'editar_menu',
            'procesar_pagos',
            'gestionar_usuarios',
            'abrir_caja',
            'cancelar_items',
            'aplicar_descuentos',
            'ver_inventario',
            'editar_inventario',
            'configurar_sistema',
            'gestionar_categorias',
            'gestionar_productos',
            'gestionar_modificadores',
            'gestionar_promociones',
            'gestionar_mesas',
            'gestionar_zonas',
            'ver_comandas',
            'gestionar_comandas',
            'ver_kds',
            'gestionar_kds',
            'gestionar_caja',
            'ver_caja',
            'emitir_facturas',
            'ver_notificaciones',
            'gestionar_proveedores',
            'gestionar_compras',
            'gestionar_ingredientes',
            'gestionar_recetas',
            'ver_whatsapp',
            'gestionar_whatsapp',
            'gestionar_impresoras',
            'gestionar_turnos',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        $admin = Role::firstOrCreate(['name' => 'Administrador', 'guard_name' => 'web']);
        $admin->givePermissionTo(Permission::all());

        $gerente = Role::firstOrCreate(['name' => 'Gerente', 'guard_name' => 'web']);
        $gerente->givePermissionTo([
            'ver_reportes', 'editar_menu', 'ver_inventario', 'editar_inventario',
            'gestionar_categorias', 'gestionar_productos', 'gestionar_modificadores',
            'gestionar_promociones', 'gestionar_mesas', 'gestionar_zonas',
            'ver_comandas', 'ver_kds', 'ver_caja', 'gestionar_proveedores',
            'gestionar_compras', 'gestionar_ingredientes', 'gestionar_recetas',
            'ver_notificaciones', 'ver_whatsapp', 'gestionar_whatsapp',
            'cancelar_items', 'aplicar_descuentos', 'configurar_sistema',
            'gestionar_impresoras', 'gestionar_turnos', 'emitir_facturas',
        ]);

        $cajero = Role::firstOrCreate(['name' => 'Cajero', 'guard_name' => 'web']);
        $cajero->givePermissionTo([
            'procesar_pagos', 'abrir_caja', 'gestionar_caja', 'ver_caja',
            'aplicar_descuentos', 'cancelar_items', 'emitir_facturas',
            'ver_reportes', 'ver_comandas',
        ]);

        $mesero = Role::firstOrCreate(['name' => 'Mesero', 'guard_name' => 'web']);
        $mesero->givePermissionTo([
            'ver_comandas', 'gestionar_comandas', 'ver_kds',
            'gestionar_mesas',
        ]);

        $cocinero = Role::firstOrCreate(['name' => 'Cocinero', 'guard_name' => 'web']);
        $cocinero->givePermissionTo([
            'ver_kds', 'gestionar_kds', 'ver_comandas',
        ]);
    }
}
