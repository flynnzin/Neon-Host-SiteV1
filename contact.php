<?php
// Only process POST requests.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form fields and remove whitespace.
    $name = strip_tags(trim($_POST["name"]));
    $name = str_replace(array("\r", "\n"), array(" ", " "), $name);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST["message"]);

    // Validate the input data.
    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Set a 400 (bad request) response code.
        http_response_code(400);
        echo "Por favor, preencha todos os campos corretamente.";
        exit;
    }

    // Set the recipient email address.
    $recipient = "contato@neonhost.com.br";

    // Set the email subject.
    $subject = "Nova mensagem de contato: $name";

    // Build the email content.
    $email_content = "Nome: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Mensagem:\n$message\n";

    // Build the email headers.
    $email_headers = "From: $name <$email>\r\n";
    $email_headers .= "Reply-To: $email\r\n";

    // Send the email.
    if (mail($recipient, $subject, $email_content, $email_headers)) {
        // Set a 200 (okay) response code.
        http_response_code(200);
        echo "Obrigado! Sua mensagem foi enviada com sucesso.";
    } else {
        // Set a 500 (internal server error) response code.
        http_response_code(500);
        echo "Ops! Algo deu errado e não conseguimos enviar sua mensagem.";
    }
} else {
    // Not a POST request, set a 403 (forbidden) response code.
    http_response_code(403);
    echo "Houve um problema com sua submissão. Tente novamente.";
}
?>
