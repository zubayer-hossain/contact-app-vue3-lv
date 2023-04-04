<?php

namespace Database\Seeders;

use App\Models\PhoneType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('phone_types')->delete();
        $users = [
            ['id' => 1, 'type' => 'HOME'],
            ['id' => 2, 'type' => 'WORK'],
            ['id' => 3, 'type' => 'MOBILE'],
        ];
        PhoneType::insert($users);
    }
}
