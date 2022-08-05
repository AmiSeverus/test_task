<?php

// 1 задание - вагон

$array1 = [
    'seats_count'=>36,
    'side_seats_flag'=>false,
];

$array2 = [
    'seats_count'=>54,
    'side_seats_flag'=>true,
];

function getSectionNo(array $array, int $seat_number) {
    if (!checkArray($array))
    {
        return ('Не корректный массив');
    };

    if ($seat_number > $array['seats_count'])
    {
        return ('Не корректный номер сидения');
    };

    if (!$array['side_seats_flag'])
    {
        $sections = $array['seats_count'] / 4;

        return getSection($sections, $seat_number, 4);

    };

    $full_seats = $array['seats_count'] / 3 * 2;
    $sections = $full_seats / 4;

    if ($seat_number > $full_seats)
    {
        return $sections - getSection($sections, $seat_number - $full_seats, 2) + 1;
    } else {
        return getSection($sections, $seat_number, 4);
    }
}

function checkArray(array $array) {
    if (!$array['side_seats_flag'])
    {
        return $array['seats_count'] % 4 === 0;
    }

    if ($array['seats_count'] % 3 > 0 || $array['seats_count'] % 2 > 0)
    {
        return false;
    };

    $full_seats = $array['seats_count'] / 3 * 2;

    if (!is_int($full_seats))
    {
        return false;
    };

    $side_seats = $array['seats_count'] - $full_seats;

    return ($full_seats % 4 === 0 && $side_seats % 2 === 0 && $side_seats * 2 === $full_seats);
};

function getSection($sections, $seat_number, $seats_count)
{
    for($i = 1; $i <= $sections; $i++)
    {
        if ($i*$seats_count >= $seat_number)
        {
            return $i;
        }
    }
}

// var_dump(getSectionNo($array2, 30));

// 2 задание - парс

$data = 'a,b,c,"d,e",f,g';
$parsed = parseData($data, ',');

function parseData(string $data, string $delimiter)
{
    //TODO: реализовать функцию парсинга

    $array = explode('"', $data);
    $res = [];

    foreach($array as $key=>$item)
    {
        $item = trim($item, ',');
        if ($key % 2 !== 0)
        {
            $res[] = $item;
        } else {
            $res = array_merge($res, explode($delimiter, $item));
        }
    }

    return $res;
    // return array();
}

// Проверка:
var_dump($parsed);
if (@count($parsed) === 6) {
    echo "GOOD\n";
} else {
    echo "BAD\n";
}
if (@$parsed[3] === 'd,e') {
    echo "GOOD\n";
} else {
    echo "BAD\n";
}
