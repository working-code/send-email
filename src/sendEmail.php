<?php

require_once realpath('../vendor/autoload.php');

$pathConfig = realpath('../config.php');
if (!is_readable($pathConfig)) {
    die("Не удалось загрузить config.php<br /> Переименуйте config-example.php в config.php");
}
require_once $pathConfig;

$emailMessage = "Привет.\n Это письмо отправлено с помощью библиотеки swift mailer";
$emailFrom = [EMAIL_LOGIN => 'Personal server'];
$emailTo = EMAIL_LIST;
$emailTheme = 'Уведомление от сервера';

$countSend = 0;
try {
    $transport = (new Swift_SmtpTransport(EMAIL_SMTP, EMAIL_PORT, 'ssl'))
        ->setUsername(EMAIL_LOGIN)
        ->setPassword(EMAIL_PASSWORD);
    $mailer = new Swift_Mailer($transport);
    $message = (new Swift_Message($emailTheme))
        ->setFrom($emailFrom)
        ->setTo($emailTo)
        ->setBody($emailMessage);
    $countSend = $mailer->send($message);
} catch (Exception $e) {
    echo "<b>Произошла ошибка:</b><br />";
    echo $e->getMessage();
}

if ($countSend) {
    echo "Почта отправлена";
} else {
    echo "Не удалось отправить почту";
}
