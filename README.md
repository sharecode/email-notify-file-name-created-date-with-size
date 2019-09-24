# email-notify-file-name-created-date-with-size
email-notify-file-name-created-date-with-size
This script is PHP script.
it use for send an email to customer when the system generate file (or something change.)

you need change detail in notify.php

$site = 'example.com';
> this is header in report

$backup_dir = "/home/user/domains/example.com/public_html/subfolder/";
> real dir for scan file info

$display_dir = "/backup/example.com/";
> display dir for customer

$customer_email = 'customer@example.com';
> customer email

$customer_name = 'customer name';
> display customer name in email

$host = 'smtp.example.com';
> SMTP server host name
$port = 25;
> SMTP server port
$user = 'email-sender@example.com';
> username for SMTP authentication
$pass = '************************';
> password for SMTP authentication
$fmail = 'no-reply@example.com';
> display email for email
$fname = 'Backup Notification Service';
> display name for email

use cron to activate email notify
>/usr/bin/wget -O /dev/null http://www.example.com/notify.php
