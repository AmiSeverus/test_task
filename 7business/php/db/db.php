<?php

function get_all($pg)
{
    $sql = 'select * from categories';

    $query = pg_query($pg, $sql);

    $result = pg_fetch_all($query);

    return $result;
}

function insert_records($pg, $nested_set)
{
    if (clear_table($pg) === false)
    {
        return false;
    };
    $sql = 'insert into categories (id,category,parent_id,level,left_key,right_key)values';
    for($i = 0; $i < count($nested_set); $i++)
    {
        $sql .= '(';
        foreach($nested_set[$i] as $key=> $value)
        {
            if ($key == 'category')
            {
                $sql .= "'" . pg_escape_string($pg,$value) . "',";
            } else
            {
                $sql .= (int) $value . ',';
            } 

        }
        $sql = rtrim($sql, ',') . '),';
    }
    $sql = rtrim($sql, ',') . ';';
    
    return pg_query($pg, $sql);
}

function clear_table($pg)
{
    pg_query($pg, 'truncate categories');
}