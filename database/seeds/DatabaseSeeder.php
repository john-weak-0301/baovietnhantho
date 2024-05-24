<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $db = $this->getDbContents(__DIR__.'/data/db.sql');

        Eloquent::unguard();

        DB::unprepared($db);

        Eloquent::reguard();
    }

    /**
     * @param  string  $path
     * @return string
     */
    protected function getDbContents($path)
    {
        $contents = file_get_contents($path);

        $contents = str_replace(
            [
                'http://baovietnhantho.awe7.com',
                'baovietnhantho.awe7.com',
                '.awe7.com',
            ],
            [
                'https://baovietnhantho.com.vn',
                'baovietnhantho.com.vn',
                '.com.vn',
            ],
            $contents
        );

        return $contents;
    }
}
