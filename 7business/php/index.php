<?php

require_once('setting.php');
require_once('csv/csv.php');
require_once('json/json.php');
require_once('db/db.php');

$files = scandir($dir);

foreach($files as $file)
{
    if ($file == '..' || $file == '.')
    {
        continue;
    }
    $ext = explode('.',$file);
    $ext = array_pop($ext);
};

$nested_set = false;

switch ($ext)
{
    case 'csv':
        $nested_set = csv_to_nested_set($dir . DS . $file);
        break;
    case 'json':
        $nested_set = json_to_nested_set($dir . DS . $file);
        break;
}

if ($nested_set)
{
    $nested_set = sort_by_id($nested_set);
}

$records = get_all($pg);

if (!$nested_set)
{
    echo('Некорректный формат файла');
} elseif ($records == $nested_set) 
{
    echo('Изменений нет');
} else {
    insert_records($pg, $nested_set);
    echo('Изменения внесены');
};


function sort_by_id($nested_set)
{
    $res = [];
    foreach($nested_set as $row)
    {
        $res[$row['id']] = $row;
    }
    ksort($res);

    return array_values($res);
}
