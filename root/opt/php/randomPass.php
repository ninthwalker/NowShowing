<?php
function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
{
    $str = '';
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $str .= $keyspace[random_int(0, $max)];
    }
    return $str;
}
# Set userame/password to random
$user = random_str(8);
$pass = random_str(12);
exec("sed -i \"10s/.*/\'$user\\' => \'$pass\'/\" /config/cfg/secure.php");
?>