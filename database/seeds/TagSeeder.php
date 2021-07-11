<?php

use App\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Tag::all()->count()) {
            Tag::truncate();
        }
        $tags = [
            'عمومی',
            'سریال و فیلم‌های سینمایی',
            'گیم',
            'ورزشی',
            'کارتون',
            'طنز',
            'آموزشی',
            'تفریحی',
            'فیلم',
            'مذهبی',
            'موسیقی',
            'خبری',
            'سیاسی',
            'علم و تکنولوژی',
            'حوادث',
            'گردشگری',
            'حیوانات',
            'متفرقه',
            'تبلیغات',
            'هنری',
            'بانوان',
            'سلامت',
            'آشپزی',
        ];
        foreach ($tags as $tag) {
            Tag::create(['title' => $tag]);
        }
    }
}
