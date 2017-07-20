These files should be inside /var/www/html/ directory on the rpi along with
"SmartHome" project the offline version
They work for the online version or hosted on my PC
[update.php, temp.php, exec.php]
update.php and temp.php just receive the date from the pi {cpp script} and call the
corresponding pages online [rpi/update.php and rpi/temp.php] to update the database
and make notifications
and [exec.php] is a redis listener to receive any data updated by the user

mobile/set.php  [[offline]
execute the cpp script and pass the data directly

but for [[online]]
push in redis list to be popped by /www/html/exec.php and then exec.php executes the
cpp script like offline set.php