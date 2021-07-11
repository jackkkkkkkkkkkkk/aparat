<?php

use App\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Category::all()->count()) {
            Category::truncate();
        }
        $categories = [
            'عمومی' => ['banner' => '', 'icon' => '','user_id'=>1],
            'سریال و فیلم‌های سینمایی' => ['banner' => '', 'icon' => '','user_id'=>null],
            'گیم' => ['banner' => '', 'icon' => '','user_id'=>null],
            'ورزشی' => ['banner' => '', 'icon' => '','user_id'=>null],
            'کارتون' => ['banner' => '', 'icon' => '','user_id'=>null],
            'طنز' => ['banner' => '', 'icon' => '','user_id'=>null],
            'آموزشی' => ['banner' => '', 'icon' => '','user_id'=>null],
            'تفریحی' => ['banner' => '', 'icon' => '','user_id'=>null],
            'فیلم' => ['banner' => '', 'icon' => '','user_id'=>null],
            'مذهبی' => ['banner' => '', 'icon' => '','user_id'=>null],
            'موسیقی' => ['banner' => '', 'icon' => '','user_id'=>null],
            'خبری' => ['banner' => '', 'icon' => '','user_id'=>null],
            'سیاسی' => ['banner' => '', 'icon' => '','user_id'=>null],
            'علم و تکنولوژی' => ['banner' => '', 'icon' => '','user_id'=>null],
            'حوادث' => ['banner' => '', 'icon' => '','user_id'=>null],
            'گردشگری' => ['banner' => '', 'icon' => '','user_id'=>null],
            'حیوانات' => ['banner' => '', 'icon' => '','user_id'=>null],
            'متفرقه' => ['banner' => '', 'icon' => '','user_id'=>null],
            'تبلیغات' => ['banner' => '', 'icon' => '','user_id'=>null],
            'هنری' => ['banner' => '', 'icon' => '','user_id'=>null],
            'بانوان' => ['banner' => '', 'icon' => '','user_id'=>null],
            'سلامت' => ['banner' => '', 'icon' => '','user_id'=>null],
            'آشپزی' => ['banner' => '', 'icon' => '','user_id'=>null],
        ];
        foreach ($categories as $categoryName => $options) {
            Category::create([
                'title' => $categoryName,
                'icon' => $options['icon'],
                'banner' => $options['banner'],
                'user_id'=>$options['user_id']
            ]);
        }
    }
}
