<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@ecommerce.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin'
        ]);

        DB::table('products')->insert([
            'user_id' => 1,
            'name' => 'Adidas Final UCL Ball',
            'image' => 'https://images.unsplash.com/photo-1589487391730-58f20eb2c308?ixid=MXwxMjA3fDB8MHxzZWFyY2h8OXx8Zm9vdGJhbGx8ZW58MHx8MHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
            'price' => 300000,
            'quantity' => 3
        ]);

        DB::table('products')->insert([
            'user_id' => 1,
            'name' => 'Acer Nitro 5',
            'image' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?ixid=MXwxMjA3fDB8MHxzZWFyY2h8M3x8bGFwdG9wfGVufDB8fDB8&ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
            'price' => 5500000,
            'quantity' => 3
        ]);

    }
}
