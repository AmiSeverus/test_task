<?php

define('END_NUM', 100);

$max = 0;

$l = 1;

while ($max <= END_NUM)
{
    $res = print_row($max, $l);
    $max = $res['max'];
    $l = $res['l'];
}

function print_row($max, $l)
{
    $str = '';
    for ($i = 0; $i < $l; $i++)
    {
        if (++$max > END_NUM)
        {
            break;
        }
        $str .= $max . ' ';
    }

    if ($str)
    {
        echo $str . "\n";
    }

    return (['l'=>++$l, 'max'=>$max]);
}