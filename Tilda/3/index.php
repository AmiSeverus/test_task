<?php

$tel = '1234590';

$tel_pref = '';

$tel_prefix_dic = [
    '+7495'=> ['127.0.0.1', '197.22.3.9'],
    '+7812'=>['localhost', '95.142.196.32','89.113.79.255']
];

if (array_key_exists('HTTP_HOST', $_SERVER))
{
    $IP = explode(':', $_SERVER['HTTP_HOST'])[0];
    foreach ($tel_prefix_dic as $pref=>$IPs)
    {
        if (in_array($IP, $IPs))
        {
            $tel_pref = $pref;
            break;
        }
    }
}

if (!$tel_pref)
{
    $tel_pref = '8800';
}

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <title>Tilda</title>
</head>
<body>
    <header>
        Tel: <?php echo $tel_pref .  $tel ?>
    </header>
    <div class='content'></div>
    <footer>
        Tel: <?php echo $tel_pref .  $tel ?>
    </footer>
</body>
</html>