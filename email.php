<!DOCTYPE html>
<html>

<head>
    <title>Test Mail</title>
</head>

<body>
    <?php
    $to = "francois.loue@ynov.com";
    $subject = "Test mail";
    $message = "Hello! This is a simple email message.";
    $headers = "From: habitenforcer66@gmail.com";
    mail($to, $subject, $message, $headers);
    echo "Mail Sent.";
    ?>
</body>

</html>