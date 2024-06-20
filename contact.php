<?php
// Defina o endereço de e-mail de recebimento
$receiving_email_address = 'reformulaofc@gmail.com';

// Verifique se os dados do formulário foram enviados
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coletar dados do formulário e aplicar escapes
    $from_name = htmlspecialchars(trim($_POST['name']));
    $from_email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message = htmlspecialchars(trim($_POST['message']));
    
    // Limitar o tamanho máximo do campo de mensagem
    $message = substr($message, 0, 1000);

    // Validar os dados do formulário
    if (empty($from_name) || empty($from_email) || empty($subject) || empty($message) || !filter_var($from_email, FILTER_VALIDATE_EMAIL)) {
        // Dados inválidos, você pode configurar uma resposta adequada
        echo "Por favor, preencha o formulário corretamente.";
        exit;
    }

    // Construir o corpo do e-mail
    $email_content = "Nome: $from_name\n";
    $email_content .= "Email: $from_email\n\n";
    $email_content .= "Mensagem:\n$message\n";

    // Construir o cabeçalho do e-mail
    $email_headers = "MIME-Version: 1.0" . "\r\n";
    $email_headers .= "Content-type:text/plain;charset=UTF-8" . "\r\n";
    $email_headers .= "From: $from_name <$from_email>" . "\r\n";

    // Enviar o e-mail
    if (mail($receiving_email_address, $subject, $email_content, $email_headers)) {
        echo "Obrigado! Sua mensagem foi enviada.";
    } else {
        echo "Oops! Algo deu errado e não conseguimos enviar sua mensagem.";
    }
} else {
    // Não é uma solicitação POST, você pode configurar uma resposta adequada
    echo "Por favor, envie o formulário.";
}
?>
