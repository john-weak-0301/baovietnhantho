<?php

use Illuminate\Database\Seeder;

use App\Model\Personality;

class PersonalitySeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->getPersonality() as $value) {
            $personality = new Personality();

            $personality->name        = $value['name'];
            $personality->description = '';

            $personality->save();
        }
    }

    protected function getPersonality(): array
    {
        return [
            ['name' => 'Cẩn thận'],
            ['name' => 'Kiên nhẫn'],
            ['name' => 'Chu đáo'],
            ['name' => 'Trung thành'],
            ['name' => 'Dịu dàng'],
            ['name' => 'Hiện đại'],
            ['name' => 'Trầm tính'],
            ['name' => 'Thực tế'],
            ['name' => 'Hài hước'],
            ['name' => 'Sáng tạo'],
        ];
    }
}
