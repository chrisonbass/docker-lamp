<?php

echo "<pre>";
echo "Working PHP \n";
print_r($_SERVER);
print_r(file_get_contents("/etc/hosts") . "\n");
echo "</pre>";
?>
