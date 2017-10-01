<?php

require "bootstrap/autoload.php";

$app = require_once "bootstrap/app.php";

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$kernel->handle(Illuminate\Http\Request::capture());

$connection = mysqli_connect(config('database.connections.mysql.host'), config('database.connections.mysql.username'),
    config('database.connections.mysql.password'), null, config('database.connections.mysql.port'));

if (!$connection) {
    echo "DB insert error, your setting in config may have some error\n";
} else {
    import_file('database.sql');
    echo "";
}


function import_file($filename)
{
    global $connection;
    if ($file = file_get_contents($filename)) {
        foreach (explode(";", $file) as $query) {
            $query = trim($query);
            if (!empty($query) && $query != ";") {
                mysqli_query($connection, $query);
            }
        }
    }
}