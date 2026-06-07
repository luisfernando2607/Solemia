<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing menu data
        DB::statement('SET foreign_key_checks = 0');
        Product::withTrashed()->forceDelete();
        Category::whereNotNull('id')->forceDelete();
        DB::statement('SET foreign_key_checks = 1');

        $this->platosFuertes();
        $this->tradicionales();
        $this->picadas();
        $this->extras();

        $this->command->info('Menú de El Palacio de las Alitas insertado correctamente.');
    }

    private function platosFuertes(): void
    {
        $cat = Category::create(['name' => '1. Platos Fuertes (Combos Completos)', 'sort_order' => 1]);

        $products = [
            ['name' => 'Arroz Menestra Pollo',          'price' => 3.00, 'min' => 10, 'desc' => 'Presa de pollo sazonada y asada, acompañada de arroz y menestra.'],
            ['name' => 'Arroz Menestra Carne',           'price' => 3.00, 'min' => 8,  'desc' => 'Corte de carne de res suave asado al término ideal, con arroz y menestra.'],
            ['name' => 'Arroz Menestra Chuleta',         'price' => 3.25, 'min' => 12, 'desc' => 'Chuleta de cerdo jugosa, dorada exteriormente, servida con arroz y menestra.'],
            ['name' => 'Arroz Menestra Chuzo',           'price' => 3.00, 'min' => 6,  'desc' => 'Brocheta de carne (chuzo) asada al carbón acompañada de arroz y menestra.'],
            ['name' => 'Arroz Menestra Costilla normal', 'price' => 3.75, 'min' => 15, 'desc' => 'Costilla de cerdo sazonada de forma tradicional y asada a la perfección.'],
            ['name' => 'Arroz Menestra Costilla ahumada B.B.Q.', 'price' => 3.75, 'min' => 15, 'desc' => 'Costilla de cerdo con notas ahumadas, bañada en una robusta salsa barbacoa.', 'available' => false],
        ];

        foreach ($products as $p) {
            Product::create([
                'category_id' => $cat->id,
                'name' => $p['name'],
                'description' => $p['desc'],
                'base_price' => $p['price'],
                'prep_time_minutes' => $p['min'],
                'kitchen_area' => 'parrilla',
                'is_available' => $p['available'] ?? true,
            ]);
        }
    }

    private function tradicionales(): void
    {
        $cat = Category::create(['name' => '2. Tradicionales / Secos', 'sort_order' => 2]);

        Product::create([
            'category_id' => $cat->id,
            'name' => 'Seco de Gallina',
            'description' => 'Presa de gallina tierna estofada en una salsa base de cerveza, chicha y hierbitas.',
            'base_price' => 3.00,
            'prep_time_minutes' => 4,
            'kitchen_area' => 'cocina',
        ]);

        Product::create([
            'category_id' => $cat->id,
            'name' => 'Seco de Chivo',
            'description' => 'Guiso tradicional de chivo con un toque agridulce y especias criollas.',
            'base_price' => 3.50,
            'prep_time_minutes' => 4,
            'kitchen_area' => 'cocina',
        ]);
    }

    private function picadas(): void
    {
        $cat = Category::create(['name' => '3. Picadas / Al Paso', 'sort_order' => 3]);

        Product::create([
            'category_id' => $cat->id,
            'name' => 'Chuzos',
            'description' => 'Brocheta individual de carne asada al carbón, sazonada con el toque de la casa.',
            'base_price' => 1.50,
            'prep_time_minutes' => 6,
            'kitchen_area' => 'parrilla',
        ]);

        Product::create([
            'category_id' => $cat->id,
            'name' => 'Alitas',
            'description' => 'Porción de alitas de pollo doradas y crujientes.',
            'base_price' => 1.75,
            'prep_time_minutes' => 10,
            'kitchen_area' => 'parrilla',
        ]);

        Product::create([
            'category_id' => $cat->id,
            'name' => 'Mollajas',
            'description' => 'Mollejas sazonadas y asadas a la plancha o carbón con harto sabor.',
            'base_price' => 0,
            'prep_time_minutes' => 8,
            'kitchen_area' => 'parrilla',
            'is_available' => false,
        ]);
    }

    private function extras(): void
    {
        $cat = Category::create(['name' => '4. Porciones y Extras (Acompañamientos)', 'sort_order' => 4]);

        // Proteins
        Product::create(['category_id' => $cat->id, 'name' => 'Porción Pollo',     'base_price' => 2.00, 'prep_time_minutes' => 10, 'kitchen_area' => 'parrilla']);
        Product::create(['category_id' => $cat->id, 'name' => 'Porción Carne',     'base_price' => 2.00, 'prep_time_minutes' => 8,  'kitchen_area' => 'parrilla']);
        Product::create(['category_id' => $cat->id, 'name' => 'Porción Chuleta',   'base_price' => 2.25, 'prep_time_minutes' => 12, 'kitchen_area' => 'parrilla']);
        Product::create(['category_id' => $cat->id, 'name' => 'Porción Costilla normal',      'base_price' => 2.75, 'prep_time_minutes' => 15, 'kitchen_area' => 'parrilla']);
        Product::create(['category_id' => $cat->id, 'name' => 'Porción Costilla ahumada B.B.Q.', 'base_price' => 3.50, 'prep_time_minutes' => 15, 'kitchen_area' => 'parrilla']);

        // Sides
        Product::create(['category_id' => $cat->id, 'name' => 'Porción Patacón o Maduro', 'base_price' => 0.50, 'prep_time_minutes' => 4, 'description' => 'Plátano verde frito y majado o plátano maduro frito.', 'kitchen_area' => 'cocina']);
        Product::create(['category_id' => $cat->id, 'name' => 'Porción Moro',            'base_price' => 1.00, 'prep_time_minutes' => 2, 'description' => 'Arroz cocido con lentejas y queso fundido al estilo criollo.', 'kitchen_area' => 'cocina']);
        Product::create(['category_id' => $cat->id, 'name' => 'Porción Arroz Blanco',    'base_price' => 0.75, 'prep_time_minutes' => 1, 'description' => 'Porción clásica de arroz blanco sueltito.', 'kitchen_area' => 'cocina']);
        Product::create(['category_id' => $cat->id, 'name' => 'Porción Arroz Menestra',  'base_price' => 1.50, 'prep_time_minutes' => 3, 'description' => 'Combinación de arroz blanco con una porción de menestra clásica.', 'kitchen_area' => 'cocina']);
    }
}
