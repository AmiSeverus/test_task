<?php

function from_csv_to_array($file) {

    $csv = array_map('str_getcsv', file($file));

    if (!$csv || (count($csv) == 1 && $csv[0][0] === 'id'))
    {
        return false;
    }

    if ($csv[0][0] === 'id')
    {
        array_shift($csv);
    }

    return $csv;
}

function has_sub_cat($array, $id = 0, $level = 0)
{
    $sub_cats = [];
    $next_level = ++$level;
    foreach ($array as $row)
    {
        if ($id && count($row) === 3 && $row[2] == $id )
        {
            $sub_cats[] = ['id'=> $row[0], 'category'=>$row[1], 'parent_id'=>$row[2], 'level'=>$next_level];
        } elseif ( !$id && count($row) < 3 ) {
            $sub_cats[] = ['id'=> $row[0], 'category'=>$row[1], 'parent_id'=>0, 'level'=>$next_level];
        }
    }

    return $sub_cats;
}

function set_nested_set($array, $row, $nested_set = [])
{

    $sub_cats = has_sub_cat($array, $row['id'], $row['level']);

    if ($sub_cats)
    {
        for ($i = 0; $i < count($sub_cats); $i++)
        {
            $sub_row = $sub_cats[$i];
            if (!$i)
            {
                $sub_row['left_key'] = $row['left_key']+1;
            } else {
                $sub_row['left_key'] = end($nested_set)['right_key']+1;
            }
            $nested_set = set_nested_set($array, $sub_row, $nested_set);;
        }
        $row['right_key'] = end($nested_set)['right_key'] + 1;
        $nested_set[] = $row;
    } else {
        $row['right_key'] = $row['left_key'] + 1;
        $nested_set[] = $row;
    }

    return $nested_set;
};


function csv_to_nested_set($file)
{
    $array = from_csv_to_array($file);
    if (!$array)
    {
        return false;
    }
    $row = ['id'=>0, 'category'=>'Техника', 'parent_id'=>0, 'level'=> 0, 'left_key'=>1];

    return set_nested_set($array, $row);
}