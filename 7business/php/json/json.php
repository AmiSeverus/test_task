<?php

function json_to_nested_set($file)
{
    $tree = json_decode(file_get_contents($file), true);
    $nested_set = from_tree_to_nested_set($tree);

    if ($nested_set)
    {
        $nested_set[] = ['id'=>0, 'category'=> 'Техника', 'parent_id'=>0, 'level'=>0, 'left_key'=> 1, 'right_key'=>end($nested_set)['right_key'] + 1];
    }

    return $nested_set;

}

function from_tree_to_nested_set($tree, $nested_set = [], $level = 1, $parent_id = 0, $left_key = 1)
{
    for ($i = 0; $i < count($tree); $i++)
    {
        $row = ['id'=> $tree[$i]['id'], 'category'=>$tree[$i]['category'], 'parent_id'=> $parent_id, 'level'=> $level];
        if ($i < 1)
        {
            $row['left_key'] = $left_key + 1;
        } else {
            $row['left_key'] = end($nested_set)['right_key']+1;
        };
        if (array_key_exists('subcategories', $tree[$i]))
        {
            $nested_set = from_tree_to_nested_set($tree[$i]['subcategories'], $nested_set, $level + 1, $row['id'], $row['left_key']);
            $row['right_key'] = end($nested_set)['right_key'] + 1;
        } else {
            $row['right_key'] = $row['left_key'] + 1;
        };

        $nested_set[] = $row;
    }
    return $nested_set;
}