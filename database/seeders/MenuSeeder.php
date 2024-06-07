<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $menu1 = Menu::create(['title' => 'Menu 1', 'uri' => '#', 'sorted_id' => 1]);
        $menu2 = Menu::create(['title' => 'Menu 2', 'uri' => '#', 'sorted_id' => 2]);
        
        Menu::create(['title' => 'Submenu 1', 'parent_id' => $menu1->id, 'uri' => '#', 'sorted_id' => 1]);
        Menu::create(['title' => 'Submenu 2', 'parent_id' => $menu1->id, 'uri' => '#', 'sorted_id' => 2]);
    
    }
}
