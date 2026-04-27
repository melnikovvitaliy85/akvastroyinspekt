<?php
// mail.php — обработчик форм для АкваСтройИнспект
// Работает на любом хостинге с PHP, не требует MODX

$method = $_SERVER['REQUEST_METHOD'];
$c = true;

if ($method === 'POST') {
    $project_name = isset($_POST['project_name']) ? trim($_POST['project_name']) : 'АкваСтройИнспект';
    $admin_email  = isset($_POST['admin_email']) ? trim($_POST['admin_email']) : 'akvastroyinspekt@mail.ru';
    $form_subject = isset($_POST['form_subject']) ? trim($_POST['form_subject']) : 'Заявка с сайта';
    
    $message = '';
    foreach ($_POST as $key => $value) {
        if ($value != "" && !in_array($key, ['project_name', 'admin_email', 'form_subject'])) {
            $message .= "
            " . ( ($c = !$c) ? '<tr>':'<tr style="background-color: #f8f8f8;">' ) . "
            <td style='padding: 10px; border: #e9e9e9 1px solid;'><b>" . htmlspecialchars($key) . "</b></td>
            <td style='padding: 10px; border: #e9e9e9 1px solid;'>" . htmlspecialchars($value) . "</td>
            </tr>
            ";
        }
    }
} elseif ($method === 'GET') {
    $project_name = isset($_GET['project_name']) ? trim($_GET['project_name']) : 'АкваСтройИнспект';
    $admin_email  = isset($_GET['admin_email']) ? trim($_GET['admin_email']) : 'akvastroyinspekt@mail.ru';
    $form_subject = isset($_GET['form_subject']) ? trim($_GET['form_subject']) : 'Заявка с сайта';
    
    $message = '';
    foreach ($_GET as $key => $value) {
        if ($value != "" && !in_array($key, ['project_name', 'admin_email', 'form_subject'])) {
            $message .= "
            " . ( ($c = !$c) ? '<tr>':'<tr style="background-color: #f8f8f8;">' ) . "
            <td style='padding: 10px; border: #e9e9e9 1px solid;'><b>" . htmlspecialchars($key) . "</b></td>
            <td style='padding: 10px; border: #e9e9e9 1px solid;'>" . htmlspecialchars($value) . "</td>
            </tr>
            ";
        }
    }
} else {
    http_response_code(405);
    echo 'Method Not Allowed';
    exit;
}

$message = "<table style='width: 100%; border-collapse: collapse;'>$message</table>";

function adopt($text) { 
    return '=?UTF-8?B?'.base64_encode($text).'?='; 
}

$headers = "MIME-Version: 1.0" . "\r\n" .
           "Content-Type: text/html; charset=utf-8" . "\r\n" .
           "From: " . adopt($project_name) . " <" . $admin_email . ">" . "\r\n" .
           "Reply-To: " . $admin_email . "\r\n" .
           "X-Mailer: PHP/" . phpversion();

// Отправка письма
if (mail($admin_email, adopt($form_subject), $message, $headers)) {
    http_response_code(200);
    echo 'OK';
} else {
    http_response_code(500);
    echo 'Mail error';
}
?>