<?php

function getDbContents($path)
{
    $contents = file_get_contents($path);

    $contents = str_replace(
        [
            'http://baovietnhantho.awe7.com',
            'baovietnhantho.awe7.com',
            '.awe7.com',
        ],
        [
            'https://test.baovietnhantho.com.vn',
            'test.baovietnhantho.com.vn',
            '.com.vn',
        ],
        $contents
    );

    return $contents;
}

$db = getDbContents(__DIR__.'/db.sql');
file_put_contents(__DIR__.'/db-conver.sql', $db);
