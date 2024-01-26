<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Email Form</title>

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

  <!-- Custom CSS -->
  <style>
    body {
      padding-top: 56px; /* Adjust based on your navigation height */
      background-color: #f8f9fa;
      font-family: 'Arial', sans-serif;
    }

    .container {
      margin-top: 50px;
    }
  </style>
</head>
<body>

  <!-- Main Content -->
  <div class="container">
    <h2>Contact Us</h2>
    <form id="emailForm">
      <div class="form-group">
        <label for="emailTo">Recipient Email</label>
        <input type="email" class="form-control" id="emailTo" required>
      </div>
      <div class="form-group">
        <label for="subject">Subject</label>
        <input type="text" class="form-control" id="subject" required>
      </div>
      <div class="form-group">
        <label for="message">Message</label>
        <textarea class="form-control" id="message" rows="5" required></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Send Email</button>
    </form>
  </div>

  <!-- Bootstrap JS and Popper.js -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

  <!-- Custom JavaScript -->
  <script>
    document.getElementById('emailForm').addEventListener('submit', function (event) {
      event.preventDefault();

      const emailTo = document.getElementById('emailTo').value;
      const subject = document.getElementById('subject').value;
      const message = document.getElementById('message').value;

      // Send email using fetch
      fetch('send_email.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ emailTo, subject, message }),
      })
      .then(response => response.json())
      .then(data => {
        alert(data.message);
      })
      .catch(error => {
        console.error('Error:', error);
      });
    });
  </script>

<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Replace these values with your actual SMTP credentials
$smtpUsername = 'your_smtp_username@gmail.com';
$smtpPassword = 'your_smtp_password';
$smtpHost = 'smtp.gmail.com';
$smtpPort = 587;
$smtpEncryption = 'tls';

// Get data from the POST request
$data = json_decode(file_get_contents('php://input'), true);

$emailTo = $data['emailTo'];
$subject = $data['subject'];
$message = $data['message'];

// Create a PHPMailer instance
$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->SMTPDebug = 0;
    $mail->isSMTP();
    $mail->Host = $smtpHost;
    $mail->SMTPAuth = true;
    $mail->Username = $smtpUsername;
    $mail->Password = $smtpPassword;
    $mail->SMTPSecure = $smtpEncryption;
    $mail->Port = $smtpPort;

    // Recipients
    $mail->setFrom($smtpUsername, 'Your Name');
    $mail->addAddress($emailTo);

    // Content
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $message;

    // Send email
    $mail->send();
    echo json_encode(['message' => 'Email sent successfully']);
} catch (Exception $e) {
    echo json_encode(['message' => 'Email could not be sent.']);
}
?>


</body>
</html>
