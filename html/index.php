<?php

require_once '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

try {
    if (!file_exists('..' . DIRECTORY_SEPARATOR . 'config.php')) {
        throw new Exception("Переименуйте config-example.php в config.php и задайте свои настройки");
    }
    require_once '..' . DIRECTORY_SEPARATOR . 'config.php';
    if (!file_exists(EMAIL_LIST_FROM_FILE)) {
        throw new Exception("Нет файла со списком email адресов " . EMAIL_LIST_FROM_FILE .
            '<br />Переименуйте emailList-example.txt в emailList.txt и укажите email адреса для рассылки');
    }

    $transport = (new Swift_SmtpTransport(EMAIL_SMTP, EMAIL_PORT, 'ssl'))
        ->setUsername(EMAIL_LOGIN)
        ->setPassword(EMAIL_PASSWORD);
    $mailer = new Swift_Mailer($transport);

    $emailMessage = "Привет.\n Это письмо отправлено с помощью библиотеки swift mailer";
    $emailFrom = [EMAIL_LOGIN => 'Personal server'];
    $emailTo = array_map('trim', file(EMAIL_LIST_FROM_FILE));
    $emailTheme = 'Уведомление от сервера';

    $message = new Swift_Message($emailTheme);
    $message->setFrom($emailFrom)->setTo($emailTo)->setBody($emailMessage);

    if ($mailer->send($message)) {
        echo "Почта отправлена";
    }
} catch (Exception $e) {
    echo "<b>Произошла ошибка:</b><br />";
    echo $e->getMessage();
}
