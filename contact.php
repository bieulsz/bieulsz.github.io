<?php
// Defina o endereço de e-mail de recebimento
$receiving_email_address = '0000998257@senaimgaluno.com.br';

// Verifique se os dados do formulário foram enviados e o método de requisição é POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coletar dados do formulário e aplicar escapes
    $from_name = htmlspecialchars(trim($_POST['name']));
    $from_email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message = htmlspecialchars(trim($_POST['message']));
    
    // Validar os dados do formulário
    if (empty($from_name) || empty($from_email) || empty($subject) || empty($message) || !filter_var($from_email, FILTER_VALIDATE_EMAIL)) {
        // Dados inválidos, enviar resposta adequada
        http_response_code(400); // Bad Request
        echo "Por favor, preencha o formulário corretamente.";
        exit;
    }
    
    // Limitar o tamanho máximo do campo de mensagem
    $message = substr($message, 0, 1000);

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
        http_response_code(200); // OK
        echo "Obrigado! Sua mensagem foi enviada.";
    } else {
        http_response_code(500); // Internal Server Error
        echo "Oops! Algo deu errado e não conseguimos enviar sua mensagem.";
    }
} else {
    // Não é uma solicitação POST, enviar resposta adequada
    http_response_code(405); // Method Not Allowed
    echo "Por favor, envie o formulário.";
}
?>
