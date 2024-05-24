<?php

use Illuminate\Database\Seeder;
use App\Model\Fund;

class FundSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Fund::truncate();
        $data = [
            [
                'name' => "Quỹ an toàn",
                'risks_of_investing' => "Thấp",
                'desc_target' => "Tạo khả năng sinh lời ổn định",
                'desc_profit' => "Đầu tư vào các danh mục ổn định",
                'desc_invest' => '<p>Trái phiếu, tiền gửi và các công cụ tại thị trường tiền tệ: <a href="#" target="_blank">10%</a></p>',
                'created_at' => now(),
            ],
            [
                'name' => "Quỹ cân bằng",
                'risks_of_investing' => "Trung bình",
                'desc_target' => "Tạo khả năng sinh lời ổn định",
                'desc_profit' => "Đầu tư vào các danh mục ổn định",
                'desc_invest' => '<p>Trái phiếu, tiền gửi và các công cụ tại thị trường tiền tệ: <a href="#" target="_blank">10%</a></p>',
                'created_at' => now(),
            ],
            [
                'name' => "Quỹ tăng trưởng",
                'risks_of_investing' => "Cao",
                'desc_target' => "Tạo khả năng sinh lời ổn định",
                'desc_profit' => "Đầu tư vào các danh mục ổn định",
                'desc_invest' => '<p>Trái phiếu, tiền gửi và các công cụ tại thị trường tiền tệ: <a href="#" target="_blank">10%</a></p>',
                'created_at' => now(),
            ],
        ];
        Fund::insert($data);
    }
}
