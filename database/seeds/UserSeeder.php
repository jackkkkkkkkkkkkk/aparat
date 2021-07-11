<?php

use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (User::all()->count()) {
            User::truncate();
        }
        $this->makeAdminUser();
        $this->makeUser();
    }

    private function makeAdminUser()
    {
        factory(User::class)->create([
            'name' => 'مدیر سایت',
            'email' => 'ali@gmail.com',
            'password' => bcrypt('123456'),
            'type' => 'admin',
            'mobile' => '09107542246'
        ]);
    }

    private function makeUser()
    {
        factory(User::class)->create([
            'name' => 'کاربر معمولی',
            'email' => 'alimohammadi1376@gmail.com',
            'password' => bcrypt('123456'),
            'type' => 'user'
        ]);
    }
}
