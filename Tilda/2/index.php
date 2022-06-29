<?php
define('MIN_NUM', 1);
define('MAX_NUM', 1000);
define('W', 5);
define('H', 7);

$arr = [];

$i = 0;

function get_key($arr)
{
    if (count($arr) < 1)
    {
        return 0;
    } else {
        foreach ($arr as $index=>$row)
        {
            if (count($row) < W)
            {
                return $index;
            }
        };
        return count($arr);
    }
}

function check_double($num, $arr)
{
    if (count($arr) < 1)
    {
        return false;
    };

    foreach ($arr as $row)
    {
        if(in_array($num, $row))
        {
            return true;
        }
    }

    return false;
}

while ($i < W * H)
{
    $test_num = rand(MIN_NUM, MAX_NUM);
    if (check_double($test_num, $arr))
    {
        continue;
    }
    $i++;
    $key = get_key($arr);
    if (array_key_exists($key, $arr))
    {
        $arr[$key][] = $test_num;
    } else {
        $arr[$key] = [$test_num];
    }
}

$col_sum = [];

for ($i = 0; $i < W; $i++)
{
    $sum = 0;
    for ($j = 0; $j < H; $j++)
    {
        $sum += $arr[$j][$i];
    }
    $col_sum[] = $sum;
}

foreach($arr as $row)
{
    echo implode(' ', $row) . ' сумма ряда - ' . array_sum($row) . "\n";
}

echo 'сумма колонок' . "\n" . implode(' ', $col_sum) . "\n";