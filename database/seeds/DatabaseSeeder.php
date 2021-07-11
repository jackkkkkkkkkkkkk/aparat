<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Artisan::call('aparat:clear');
        Schema::disableForeignKeyConstraints();
        $this->call(UserSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(TagSeeder::class);
        $this->call(PlaylistSeeder::class);
        $this->call(PassportClientSeeder::class);
        Schema::enableForeignKeyConstraints();
    }
}
