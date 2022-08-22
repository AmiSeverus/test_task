<?php

// Задачка с интервью:
// Функция ArrayChallenge() принимает на вход массив с двумя элементами.
// Первый - набор символов. Второй - длинная строка разделенных запятой слов, отсортированных в алфавитном порядке.
// Например ['hellocat', 'apple,bat,cat,goodbye,hello,yellow,why'].
// Задача определить, может ли первый элемент массива быть разбит на два слова из второго элемента массива.
// Для набора слов из примера это будут слова "cat" и "hello".
// 
// Функция должна возвращать 2 слова разделенных запятой. Таким образом для входных данных из примера ответ будет "hello,cat".
// Если в наборе есть способ разбить слово правильно, то только один. В случае если способа ращбить слово нет, необходимо вернуть "not possible"
// Первый элемент массива не может присутствовать во втором.
 
function ArrayChallenge(array $input=['hellocat', 'apple,bat,cat,goodbye,hello,yellow,why']) {
    $res_arr = [];

    $arr = explode(',', $input[1]);

    if (!$arr)
    {
        return 'строка не задана';
    }

    $i = 0;

    while($i < count($arr))
    {
        $arr[$i] = trim($arr[$i]);
        if($arr[$i])
        {
            $i++;
        } else {
            unset($arr[$i]);
        }
    }

    if (!$arr)
    {
        return 'строка не содержит слов';
    }

    $arr = array_unique($arr);

    $str = $input[0];

    $cnt = strlen($str);

    $found = false;

    $i = 0;

    while ($i < $cnt)
    {
        $letter = $str[$i];
        foreach($arr as $test_str)
        {
            if ($letter === $test_str[0])
            {
                $res = checkWord($test_str, $str, $i);
                if ($res)
                {
                    $i = $res['i'];
                    $res_arr[] = $res['res_str'];
                    $found = true;
                    break;
                }
            }
        }
        if ($found)
        {
            $found = false;
        } else {
            $i++;
        }
    }

    return implode(',', $res_arr);
}

function checkWord(string $test_str, string $str, int $i)
{
    $str = substr($str, $i, strlen($test_str));
    if ($str !== $test_str)
    {
        return false;
    }
    return [
        'i'=> $i + strlen($test_str),
        'res_str'=>$test_str,
    ];
}

print_r(ArrayChallenge());
