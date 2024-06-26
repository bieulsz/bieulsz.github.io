<?php
// Defina o endereço de e-mail de recebimento
$receiving_email_address = '0000998257@senaimgaluno.com.br';

// Verifique se os dados do formulário foram enviados e o método de requisição é POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coletar dados do formulário e aplicar escapes para segurança
    $from_name = htmlspecialchars(trim($_POST['name']));
    $from_email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message = htmlspecialchars(trim($_POST['message']));
    
    // Validar os dados do formulário
    if (empty($from_name) || empty($from_email) || empty($subject) || empty($message) || !filter_var($from_email, FILTER_VALIDATE_EMAIL)) {
        // Se algum campo estiver vazio ou email inválido, retorna erro 400
        http_response_code(400);
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

    // Enviar o e-mail usando a função mail()
    if (mail($receiving_email_address, $subject, $email_content, $email_headers)) {
        // Se o email foi enviado com sucesso, retorna código 200
        http_response_code(200);
        echo "Obrigado! Sua mensagem foi enviada.";
    } else {
        // Se houve algum erro ao enviar o email, retorna erro 500
        http_response_code(500);
        echo "Oops! Algo deu errado e não conseguimos enviar sua mensagem.";
    }
} else {
    // Se a requisição não foi feita via POST, retorna erro 405
    http_response_code(405);
    echo "Por favor, envie o formulário.";
}
?>
