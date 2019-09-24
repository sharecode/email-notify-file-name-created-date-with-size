<?php
//customer detail
$site = 'example.com';
$backup_dir = "/home/user/domains/example.com/public_html/subfolder/";
$display_dir = "/backup/example.com/";
$customer_email = 'customer@example.com';
$customer_name = 'customer name';

//email sender
$host = 'smtp.example.com';
$port = 25;
$user = 'email-sender@example.com';
$pass = '************************';
$fmail = 'no-reply@example.com';
$fname = 'Backup Notification Service';

require dirname(__FILE__) . '/lib/lib.php';