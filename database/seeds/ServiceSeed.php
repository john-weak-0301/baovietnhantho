<?php

use App\Model\Service;
use App\Model\ServiceCategory;
use Illuminate\Database\Seeder;

class ServiceSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->getService() as $value) {
            $service = new Service();

            $service->title   = $value['title'];
            $service->content = '';
            $service->save();

            $category = ServiceCategory::findCategory($value['category_name']);
            $service->categories()->sync($category);
        }
    }

    protected function getService(): array
    {
        return [
            [
                'title'         => 'Hướng dẫn tra cứu thông tin hợp đồng',
                'category_name' => 'Tra cứu thông tin',
            ],

            [
                'title'         => 'Hướng dẫn tra cứu bảng phí tiếp theo',
                'category_name' => 'Tra cứu thông tin',
            ],

            [
                'title'         => 'Hướng dẫn xem lại lịch sử đống phí',
                'category_name' => 'Tra cứu thông tin',
            ],

            [
                'title'         => 'Thanh toán quyền lợi bảo hiểm',
                'category_name' => 'Tra cứu thông tin',
            ],

            [
                'title'         => 'Thay đổi điều kiện hợp đồng (Sản phẩm truyền thống)',
                'category_name' => 'Thay đổi thông tin hợp đồng bảo hiểm',
            ],

            [
                'title'         => 'Thay đổi điều kiện hợp đồng (Sản phẩm liên kết chung)',
                'category_name' => 'Thay đổi thông tin hợp đồng bảo hiểm',
            ],

            [
                'title'         => 'Thay đổi điều kiện đóng phí',
                'category_name' => 'Thay đổi thông tin hợp đồng bảo hiểm',
            ],

            [
                'title'         => 'Thay đổi bên mua bảo hiểm',
                'category_name' => 'Thay đổi thông tin hợp đồng bảo hiểm',
            ],

            [
                'title'         => 'Thay đổi người thụ hưởng',
                'category_name' => 'Thay đổi thông tin hợp đồng bảo hiểm',
            ],

            [
                'title'         => 'Thay đổi thông tin khách hàng',
                'category_name' => 'Thay đổi thông tin hợp đồng bảo hiểm',
            ],

            [
                'title'         => 'Giải quyết quyền lợi bảo hiểm 15 phút',
                'category_name' => 'Giải quyết quyền lợi bảo hiểm',
            ],

            [
                'title'         => 'Thanh toán quyền lợi bảo hiểm',
                'category_name' => 'Giải quyết quyền lợi bảo hiểm',
            ],

            [
                'title'         => 'Hướng dẫn giải quyết quyền lợi bảo hiểm',
                'category_name' => 'Giải quyết quyền lợi bảo hiểm',
            ],

            [
                'title'         => 'Quy trình giải quyết quyền lợi bảo hiểm',
                'category_name' => 'Giải quyết quyền lợi bảo hiểm',
            ],

            [
                'title'         => 'Thanh toán phí qua Tư vấn viên',
                'category_name' => 'Thanh toán phí bảo hiểm',
            ],

            [
                'title'         => 'Thanh toán phí qua Internet Banking (i-Banking) và Dịch vụ trích nợ tự động',
                'category_name' => 'Thanh toán phí bảo hiểm',
            ],

            [
                'title'         => 'Thanh toán phí trực tuyến bằng thẻ trên trang Cổng thông tin khách hàng - ePOS',
                'category_name' => 'Thanh toán phí bảo hiểm',
            ],

            [
                'title'         => 'Thanh toán phí qua ngân hàng',
                'category_name' => 'Thanh toán phí bảo hiểm',
            ],

            [
                'title'         => 'Thanh toán phí trực tiếp tại Công ty',
                'category_name' => 'Thanh toán phí bảo hiểm',
            ],

            [
                'title'         => 'Về thuật ngữ bảo hiểm',
                'category_name' => 'Những câu hỏi thường gặp',
            ],

            [
                'title'         => 'Về bảo hiểm nhân thọ nói chung',
                'category_name' => 'Những câu hỏi thường gặp',
            ],

            [
                'title'         => 'Về các kênh đóng phí bảo hiểm',
                'category_name' => 'Những câu hỏi thường gặp',
            ],

            [
                'title'         => 'Thanh toán quyền lợi hợp đồng bảo hiểm',
                'category_name' => 'Những câu hỏi thường gặp',
            ],

            [
                'title'         => 'Điều chỉnh thông tin hợp đồng bảo hiểm',
                'category_name' => 'Những câu hỏi thường gặp',
            ],
        ];
    }
}
