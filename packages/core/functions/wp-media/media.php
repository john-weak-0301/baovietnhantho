<?php

function _e($content)
{
    echo __($content);
}

function esc_html_e($content)
{
    echo esc_html($content);
}

function esc_attr_e($attr)
{
    echo esc_attr($attr);
}

function _ex($text, $context = 'default')
{
    echo $text;
}

function _device_can_upload()
{
    return true;
}
