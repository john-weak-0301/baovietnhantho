<?php

use Illuminate\Database\Seeder;
use App\Model\ServiceCategory;

class ServiceCategorySeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->getCategory() as $value) {
            $category = new ServiceCategory();

            $category->name        = $value['name'];
            $category->description = $value['description'];

            $category->save();
        }
    }

    protected function getCategory(): array
    {
        return [
            [
                'name'        => 'Tra cứu thông tin',
                'description' => 'Áp dụng với hồ sơ yêu cầu GQQLBH do khách hàng nộp trực tiếp tại Trụ sở Công ty và đáp ứng một số điều kiện quy định dưới đây.',
            ],

            [
                'name'        => 'Thay đổi thông tin hợp đồng bảo hiểm',
                'description' => 'Điều chỉnh thông tin hồ sơ/hợp đồng bảo hiểm',
            ],

            [
                'name'        => 'Giải quyết quyền lợi bảo hiểm',
                'description' => 'Áp dụng với hồ sơ yêu cầu GQQLBH do khách hàng nộp trực tiếp tại Trụ sở Công ty và đáp ứng một số điều kiện quy định dưới đây.',
            ],

            [
                'name'        => 'Thanh toán phí bảo hiểm',
                'description' => 'Quý khách có thể lựa chọn một trong những hình thức thanh toán phí bảo hiểm sau:',
            ],

            [
                'name'        => 'Những câu hỏi thường gặp',
                'description' => 'Chọn một trong những chủ đề câu hỏi mà bạn thắc mắc',
            ],
        ];
    }
}
