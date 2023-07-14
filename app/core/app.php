<?php

define("APP", dirname(__DIR__) . DIRECTORY_SEPARATOR);
define("CORE", APP . "core" . DIRECTORY_SEPARATOR . "app.php");
define("LIB", APP . "lib" . DIRECTORY_SEPARATOR);
define('VIEW', APP . "view" . DIRECTORY_SEPARATOR);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require LIB . "PHPMailer/src/Exception.php";
require LIB . "PHPMailer/src/PHPMailer.php";
require LIB . "PHPMailer/src/SMTP.php";

$err = [];
$name = '';
$email = '';
$subject = '';
$phone = '';
$msg = '';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (!empty($_POST['name'])) {
        $name = validate($_POST['name']);
        if (strlen($name) <= 4) {
            $err[] = "name must more than 4 chars!";
        }

    } else {
        $err[] = "name required!!";
    }

    if (!empty($_POST['Email'])) {
        $email = validate($_POST['Email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $err[] = "Email is not Valid!!";
        }
    } else {
        $err[] = "Email required!!";
    }

    if (!empty($_POST['phone'])) {
        $phone = validate($_POST['phone']);
        if (strlen($phone) != 11) {
            $err[] = 'Phone must be 11 Numbers';
            if (!filter_var($name, FILTER_VALIDATE_INT)) {
                $err[] = 'Phone not Valid!!';
            }
        }
    } else {
        $err[] = "Phone required!!";
    }

    if (!empty($_POST['website'])) {
        $subject = validate($_POST['website']);
        if (strlen($subject) >= 20) {
            $err[] = "Subject must less than 20 chars!!";
        }
    } else {
        $err[] = "Subject required!!";
    }
    $msg = validate($_POST['msg']);
    if (!empty($msg)) {
        if (strlen($msg) <= 20 || strlen($msg) >= 150) {
            $err[] = "Message must between (20 - 150) chars!!";
        }
    } else {
        $err[] = "Message required!!";
    }
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = "ssl";
    $mail->Port = 465;

    $mail->Username = "mohamed.nasr20977@gmail.com";
    $mail->Password = "vvkvatddbdyzaacg";

    if (empty($err)) {
        $mail->Body = $msg;
        $mail->setFrom($email, $name);
        $mail->addAddress("mohamed.nasr20977@gmail.com", $name);
        $mail->Subject = $subject;
        $mail->msgHTML("From::" . $mail->From);
        if ($mail->send()) {
            $err[] = "Email send";
        } else {
            $err[] = "Email Not Send :(";
        }
    } else {
        $err[] = "Email Not Send :(";
    }

    $data = json_encode($err);
    echo $data;
}

function validate($data)
{
    $data = htmlspecialchars($data);
    $data = strip_tags($data);
    $data = trim($data, " ");
    return $data;
}
