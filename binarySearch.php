<?php

/*Реализовать  функцию бинарного поиска 

function binarySearch($arr, $target) {}

$arr = [1, 4, 2, 33, 5, 3, 11, 100, 14, 18];

binarySearch($arr, $target) // Вернет индекс найденого элемента

*/

$target = 18;

$arr = [1, 4, 2, 33, 5, 3, 11, 100, 14, 18];

function binarySearch ($arr, $target) {
    
    if(count($arr) === 0) {
        return null;
    };
    
    if(count($arr) < 3) {
        foreach($arr as $index => $value) {
            if ($value === $target) {
                return $index;
            }
        };
        
        return null;
    };
    
    asort($arr);
    
    $cnt = (int) ceil(count($arr)/2);
    
    $arr = array_chunk($arr, $cnt, true);
    
    $value = end ($arr[0]);
    
    if($target === $value) {
        return array_key_last($arr[0]);
    };
        
    if($target > $value) {
        return binarySearch($arr[1], $target);
    };
        
    if($target < $value) {
        return binarySearch($arr[0], $target);            
    };
};

var_dump(binarySearch($arr, $target));
