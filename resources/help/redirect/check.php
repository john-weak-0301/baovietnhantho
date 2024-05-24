<?php

$fp = fopen(__DIR__.'/bvnt-redirect-links.conf', 'rb');

if (!$fp) {
    throw new Exception('Cannot read the config file');
}

$lnCount   = 0;
$links     = [];
$deadLinks = $verifiedLinks = [];

function get_http_response_code($link)
{
    $headers = @get_headers($link);

    return (int) substr($headers[0], 9, 3);
}

function get_http_response_redirect_url($link)
{
    $headers = get_headers($link, 1);

    $code = (int) substr($headers[0], 9, 3);

    if ($code !== 301 && $code !== 302) {
        return null;
    }

    return $headers['Location'] ?? null;
}

while (!feof($fp)) {
    $lnCount++;

    $string = trim(fgets($fp));
    if (!$string) {
        continue;
    }

    if (strpos($string, '#') === 0) {
        continue;
    }

    if ($string[strlen($string) - 1] !== ';') {
        throw new Exception(sprintf('Missing ";" at "%s" line %d', $string, $lnCount));
    }

    [$oldLink, $newLink] = preg_split('/\s+/', $string);
    $oldLink = trim(rtrim($oldLink, ';'));
    $newLink = trim(rtrim($newLink, ';'));

    if (isset($links[$oldLink])) {
        throw new Exception(sprintf('Conflicting parameter "%s" line %d', $oldLink, $lnCount));
    }

    $links[$oldLink] = $newLink;
}

foreach ($links as $oldLink => $newLink) {
    echo 'Checking: '.$newLink." ... ";
    $code = get_http_response_code('https://www.baovietnhantho.com.vn'.$newLink);

    if ($code !== 200) {
        $deadLinks[] = $newLink;
        echo " ----> \e[0;31;42mDeadlink ".$code."!\e[0m\n";
    } else {
        echo " ----> OK 200\n";
    }

    /*echo 'Verifing: ' . $oldLink.' ... ';
    $redirectUrl = get_http_response_redirect_url('http://baovietnhantho.awe7.com'.$oldLink);

    if (!$redirectUrl || false === strpos($redirectUrl, $newLink) ) {
        $verifiedLinks[] = $oldLink;
        echo " ----> \e[0;31;42mFailed!\e[0m\n";
    } else {
        echo " ----> Verified!\n";
    }*/

    echo "\n";
}

echo 'Found '.count($deadLinks).' dead link(s).'."\n";
echo 'Found '.count($verifiedLinks).' non-verified link(s).'."\n";

var_dump($deadLinks);
var_dump($verifiedLinks);
