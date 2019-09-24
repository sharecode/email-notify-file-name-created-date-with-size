<?php
function showBackupInfo($file)
{
    $GLOBALS['emailHTML'] .= '<tr><td>';
    $GLOBALS['emailHTML'] .= str_replace($GLOBALS['backup_dir'], $GLOBALS['display_dir'], $file);
    $GLOBALS['emailHTML'] .= '</td><td>';
    $GLOBALS['emailHTML'] .= date("d/m/", filemtime($file));
    $Y = date("Y", filemtime($file));
    $GLOBALS['emailHTML'] .= $Y + 543;
    $GLOBALS['emailHTML'] .= date(" H:i:s ", filemtime($file));
    $GLOBALS['emailHTML'] .= '</td><td>' . FileSizeConvert(filesize($file)) . '</td></tr>';
}

function recursiveScan($dir)
{
    $tree = glob(rtrim($dir, '/') . '/*');
    if (is_array($tree)) {
        foreach ($tree as $file) {
            if (is_dir($file)) {
                //echo '->'.$file . '<br/>';
                recursiveScan($file);
            } elseif (is_file($file)) {
                showBackupInfo($file);
            }
        }
    }
}

function FileSizeConvert($bytes)
{
    $result = '';
    $bytes = floatval($bytes);
    $arBytes = array(
        0 => array(
            "UNIT" => "TB",
            "VALUE" => pow(1024, 4),
        ),
        1 => array(
            "UNIT" => "GB",
            "VALUE" => pow(1024, 3),
        ),
        2 => array(
            "UNIT" => "MB",
            "VALUE" => pow(1024, 2),
        ),
        3 => array(
            "UNIT" => "KB",
            "VALUE" => 1024,
        ),
        4 => array(
            "UNIT" => "B",
            "VALUE" => 1,
        ),
    );

    foreach ($arBytes as $arItem) {
        if ($bytes >= $arItem["VALUE"]) {
            $result = $bytes / $arItem["VALUE"];
            $result = strval(round($result, 2)) . " " . $arItem["UNIT"];
            break;
        }
    }
    return $result;
}

/**
 * This example shows making an SMTP connection with authentication.
 */

//SMTP needs accurate times, and the PHP time zone MUST be set
//This should be done in your php.ini, but this is how to do it if you don't have access to that
date_default_timezone_set('Asia/Bangkok');

use PHPMailer\PHPMailer\PHPMailer;

require dirname(__FILE__) . '/../PHPMailer/src/Exception.php';
require dirname(__FILE__) . '/../PHPMailer/src/PHPMailer.php';
require dirname(__FILE__) . '/../PHPMailer/src/SMTP.php';

//Create a new PHPMailer instance
$mail = new PHPMailer;
//Tell PHPMailer to use SMTP
$mail->isSMTP();
//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug = 0;
//Ask for HTML-friendly debug output
$mail->Debugoutput = 'html';
//Set the hostname of the mail server
$mail->Host = $host;
//Set the SMTP port number - likely to be 25, 465 or 587
$mail->Port = $port;
//Set the encryption system to use - ssl (deprecated) or tls
$mail->SMTPSecure = 'tls';
//Whether to use SMTP authentication
$mail->SMTPAuth = true;
//Username to use for SMTP authentication
$mail->Username = $user;
//Password to use for SMTP authentication
$mail->Password = $pass;
//Set who the message is to be sent from
$mail->setFrom($fmail, $fname);
//Set who the message is to be sent to
$mail->addAddress($customer_email, $customer_name);
//Set the subject line
$mail->Subject = 'Backup :: ' . $site;
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
//$mail->msgHTML(file_get_contents('content.html'), dirname(__FILE__));
static $emailHTML = '';
// Open a directory, and read its contents
$emailHTML .= '<table style="width:100%;padding: 6px;border: 2px solid #000;">';
$emailHTML .= '<tr><td colspan="3" style="border-bottom: 2px solid #000;padding-top: 20px;font-size: 20px;"><h3>Backup ' . $site . '<h3></td></tr>';
$emailHTML .= "<tr><td>Backup</td><td>created date</td><td>file size</td></tr>";
recursiveScan($backup_dir);
$emailHTML .= "</table>";
echo $emailHTML;
$mail->msgHTML($emailHTML);
if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
}