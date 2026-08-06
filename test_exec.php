<?php
$db = [
    'username' => 'root',
    'password' => '',
    'host' => '127.0.0.1',
    'database' => 'management_inventory_toko'
];
$mysql = 'C:\\xampp\\mysql\\bin\\mysql.exe';
$fullPath = 'E:\\LARAVEL\\management-inventory-toko\\storage\\app/public/backup/restore-temp.sql';
file_put_contents($fullPath, 'SELECT 1;'); // create dummy file

$command = "\"{$mysql}\" --user={$db['username']} --password=\"{$db['password']}\" --host={$db['host']} {$db['database']} < \"{$fullPath}\"";

echo "Command: $command\n";
exec($command . ' 2>&1', $output, $result);
echo "Result: $result\n";
echo "Output: \n";
print_r($output);

// Now test create
$mysqldump = 'C:\\xampp\\mysql\\bin\\mysqldump.exe';
$path2 = 'E:\\LARAVEL\\management-inventory-toko\\storage\\app/public/backup/dummy.sql';
$command2 = "\"{$mysqldump}\" --user={$db['username']} --password=\"{$db['password']}\" --host={$db['host']} {$db['database']} > \"{$path2}\"";
exec($command2 . ' 2>&1', $out2, $res2);
echo "Result2: $res2\n";
print_r($out2);
