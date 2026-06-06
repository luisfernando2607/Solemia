<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\TableModel;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RoleAndPermissionSeeder::class);

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

        // Zones
        $sala = Zone::create(['name' => 'Sala principal', 'description' => 'Área principal del restaurante', 'sort_order' => 1]);
        $terraza = Zone::create(['name' => 'Terraza', 'description' => 'Área exterior', 'sort_order' => 2]);
        $barra = Zone::create(['name' => 'Barra', 'description' => 'Barra de atención directa', 'sort_order' => 3]);

        // Tables
        for ($i = 1; $i <= 6; $i++) {
            TableModel::create(['zone_id' => $sala->id, 'number' => (string)$i, 'capacity' => $i <= 2 ? 2 : 4, 'shape' => 'square', 'pos_x' => ($i - 1) * 120, 'pos_y' => 0]);
        }
        for ($i = 7; $i <= 10; $i++) {
            TableModel::create(['zone_id' => $terraza->id, 'number' => (string)$i, 'capacity' => 4, 'shape' => 'round', 'pos_x' => ($i - 7) * 140, 'pos_y' => 0]);
        }
        TableModel::create(['zone_id' => $barra->id, 'number' => 'B1', 'capacity' => 1, 'shape' => 'rectangle', 'pos_x' => 0, 'pos_y' => 0]);
        TableModel::create(['zone_id' => $barra->id, 'number' => 'B2', 'capacity' => 1, 'shape' => 'rectangle', 'pos_x' => 80, 'pos_y' => 0]);
        TableModel::create(['zone_id' => $barra->id, 'number' => 'B3', 'capacity' => 1, 'shape' => 'rectangle', 'pos_x' => 160, 'pos_y' => 0]);

        // Categories & Products
        $entradas = Category::create(['name' => 'Entradas', 'sort_order' => 1]);
        $fuertes = Category::create(['name' => 'Platos fuertes', 'sort_order' => 2]);
        $bebidas = Category::create(['name' => 'Bebidas', 'sort_order' => 3]);
        $postres = Category::create(['name' => 'Postres', 'sort_order' => 4]);

        Product::create(['category_id' => $entradas->id, 'name' => 'Bruschetta', 'base_price' => 8.50, 'prep_time_minutes' => 5, 'kitchen_area' => 'frio']);
        Product::create(['category_id' => $entradas->id, 'name' => 'Carpaccio', 'base_price' => 12.00, 'prep_time_minutes' => 7, 'kitchen_area' => 'frio']);
        Product::create(['category_id' => $fuertes->id, 'name' => 'Lomo Saltado', 'base_price' => 18.50, 'prep_time_minutes' => 15, 'kitchen_area' => 'parrilla']);
        Product::create(['category_id' => $fuertes->id, 'name' => 'Pasta Alfredo', 'base_price' => 14.00, 'prep_time_minutes' => 12, 'kitchen_area' => 'cocina']);
        Product::create(['category_id' => $fuertes->id, 'name' => 'Pizza Margherita', 'base_price' => 16.00, 'prep_time_minutes' => 20, 'kitchen_area' => 'horno']);
        Product::create(['category_id' => $bebidas->id, 'name' => 'Limonada Natural', 'base_price' => 4.50, 'prep_time_minutes' => 3, 'kitchen_area' => 'bebidas']);
        Product::create(['category_id' => $bebidas->id, 'name' => 'Vino Tinto', 'base_price' => 22.00, 'prep_time_minutes' => 2, 'kitchen_area' => 'bebidas']);
        Product::create(['category_id' => $postres->id, 'name' => 'Tiramisú', 'base_price' => 9.00, 'prep_time_minutes' => 5, 'kitchen_area' => 'postres']);
        Product::create(['category_id' => $postres->id, 'name' => 'Panna Cotta', 'base_price' => 8.00, 'prep_time_minutes' => 4, 'kitchen_area' => 'postres']);

        // Customers
        Customer::create(['name' => 'Juan Pérez', 'ruc' => '1712345678001', 'phone' => '0991234567', 'email' => 'juan@email.com', 'address' => 'Av. Amazonas N52-123']);
        Customer::create(['name' => 'María González', 'ruc' => '1723456789001', 'phone' => '0982345678', 'email' => 'maria@email.com', 'address' => 'Calle República E4-567']);
        Customer::create(['name' => 'Carlos Rodríguez', 'ruc' => '1734567890001', 'phone' => '0973456789', 'email' => 'carlos@email.com', 'address' => 'Av. 6 de Diciembre N78-901']);
        Customer::create(['name' => 'Ana Martínez', 'phone' => '0964567890', 'email' => 'ana@email.com']);
        Customer::create(['name' => 'Luis Fernando Torres', 'ruc' => '1745678900001', 'phone' => '0955678901', 'email' => 'luis@email.com', 'address' => 'Av. Eloy Alfaro O1-234']);
    }
}
