<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\TableModel;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RoleAndPermissionSeeder::class);

        $this->call(MenuSeeder::class);

        $this->call(SampleReportDataSeeder::class);

        // Users
        $admin = User::firstOrCreate(['email' => 'admin@solemia.com'], ['name' => 'Administrador', 'password' => bcrypt('admin123'), 'pin' => '1234']);
        $admin->assignRole('Administrador');

        $gerente = User::firstOrCreate(['email' => 'gerente@solemia.com'], ['name' => 'Gerente', 'password' => bcrypt('gerente123'), 'pin' => '1111']);
        $gerente->assignRole('Gerente');

        $cajero = User::firstOrCreate(['email' => 'cajero@solemia.com'], ['name' => 'Cajero', 'password' => bcrypt('cajero123'), 'pin' => '2222']);
        $cajero->assignRole('Cajero');

        $mesero = User::firstOrCreate(['email' => 'mesero@solemia.com'], ['name' => 'Mesero', 'password' => bcrypt('mesero123'), 'pin' => '3333']);
        $mesero->assignRole('Mesero');

        $cocinero = User::firstOrCreate(['email' => 'cocinero@solemia.com'], ['name' => 'Cocinero', 'password' => bcrypt('cocinero123'), 'pin' => '4444']);
        $cocinero->assignRole('Cocinero');

        // Zones (idempotent)
        $sala = Zone::firstOrCreate(['name' => 'Sala principal'], ['description' => 'Área principal del restaurante', 'sort_order' => 1]);
        $terraza = Zone::firstOrCreate(['name' => 'Terraza'], ['description' => 'Área exterior', 'sort_order' => 2]);
        $barra = Zone::firstOrCreate(['name' => 'Barra'], ['description' => 'Barra de atención directa', 'sort_order' => 3]);

        // Tables (idempotent by zone + number)
        for ($i = 1; $i <= 6; $i++) {
            TableModel::firstOrCreate(['zone_id' => $sala->id, 'number' => (string)$i], ['capacity' => $i <= 2 ? 2 : 4, 'shape' => 'square', 'pos_x' => ($i - 1) * 120, 'pos_y' => 0]);
        }
        for ($i = 7; $i <= 10; $i++) {
            TableModel::firstOrCreate(['zone_id' => $terraza->id, 'number' => (string)$i], ['capacity' => 4, 'shape' => 'round', 'pos_x' => ($i - 7) * 140, 'pos_y' => 0]);
        }
        TableModel::firstOrCreate(['zone_id' => $barra->id, 'number' => 'B1'], ['capacity' => 1, 'shape' => 'rectangle', 'pos_x' => 0, 'pos_y' => 0]);
        TableModel::firstOrCreate(['zone_id' => $barra->id, 'number' => 'B2'], ['capacity' => 1, 'shape' => 'rectangle', 'pos_x' => 80, 'pos_y' => 0]);
        TableModel::firstOrCreate(['zone_id' => $barra->id, 'number' => 'B3'], ['capacity' => 1, 'shape' => 'rectangle', 'pos_x' => 160, 'pos_y' => 0]);

        // Customers
        Customer::firstOrCreate(['ruc' => '1712345678001'], ['name' => 'Juan Pérez', 'phone' => '0991234567', 'email' => 'juan@email.com', 'address' => 'Av. Amazonas N52-123']);
        Customer::firstOrCreate(['ruc' => '1723456789001'], ['name' => 'María González', 'phone' => '0982345678', 'email' => 'maria@email.com', 'address' => 'Calle República E4-567']);
        Customer::firstOrCreate(['ruc' => '1734567890001'], ['name' => 'Carlos Rodríguez', 'phone' => '0973456789', 'email' => 'carlos@email.com', 'address' => 'Av. 6 de Diciembre N78-901']);
        Customer::firstOrCreate(['email' => 'ana@email.com'], ['name' => 'Ana Martínez', 'phone' => '0964567890']);
        Customer::firstOrCreate(['ruc' => '1745678900001'], ['name' => 'Luis Fernando Torres', 'phone' => '0955678901', 'email' => 'luis@email.com', 'address' => 'Av. Eloy Alfaro O1-234']);
    }
}
