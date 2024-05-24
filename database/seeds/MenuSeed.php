<?php

use Illuminate\Database\Seeder;

use Core\Dashboard\Models\Menu;

class MenuSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $footer_menu_1 = '
Chăm sóc sức khoẻ
Tương lai cho con trẻ
Tạo dựng tài sản
Vượt qua biến cố bệnh tật
Bảo vệ người trụ cột
Chuẩn bị giai đoạn hưu trí';
        $this->makeMenuItems('footer-1', explode("\n", $footer_menu_1));

        $footer_menu_2 = '
Kênh thanh toán
Thay đổi thông tin hợp đồng bảo hiểm
Giải quyết quyền lợi bảo hiểm
Những điều cần biết cho khách hàng
Chăm sóc khách hàng';
        $this->makeMenuItems('footer-2', explode("\n", $footer_menu_2));

        $footer_menu_3 = '
Lịch sử phát triển
Tầm nhìn sứ mệnh
Ban lãnh đạo
Giải thưởng và danh hiệu
Báo cáo tài chính
Hoạt động cộng đồng';
        $this->makeMenuItems('footer-3', explode("\n", $footer_menu_3));

        $footer_menu_4 = '
Tuyển dụng cán bộ
Tuyển dụng nhân viên';
        $this->makeMenuItems('footer-4', explode("\n", $footer_menu_4));
    }

    protected function makeMenuItems($type, $menus)
    {
        foreach ($menus as $menu) {
            $menu = trim($menu);
            if (empty($menu)) {
                continue;
            }

            $menuItem        = Menu::firstOrNew(['label' => $menu]);
            $menuItem->label = $menu;
            $menuItem->title = $menu;
            $menuItem->slug  = '#';
            $menuItem->type  = $type;

            $menuItem->save();
        }
    }
}
