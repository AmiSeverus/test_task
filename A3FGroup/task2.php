<?php

#Задание 2

$array = [4, 5, 8, 9, 1, 7, 2];

function array_swap(&$arr, $num)
{
    $reverse_keys = [$arr[0], $arr[$num]];
    $arr[0] = $reverse_keys[1];
    $arr[$num] = $reverse_keys[0];
    return $arr;
}

#Решение 1

$test_arr = [];

while (count($array) > 1)
{
    for ($i=0; $i < count($array); $i++)
    {
        if ($array[0] > $array[$i])
        {
            $array = array_swap($array, $i);
        }
    }
    $test_arr[] = array_shift($array);
}

$array = array_merge($test_arr, $array);

print_r($array);

#Решение 2

for($cnt = count($array); $cnt > 1; $cnt--)
{
    for($i=0; $i < $cnt; $i++)
    {
        if ($array[0] < $array[$i])
        {
            $array = array_swap($array, $i);
        }
    }
    $array = array_swap($array, $cnt-1);
}

print_r($array);