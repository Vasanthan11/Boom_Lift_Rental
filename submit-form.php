<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Get and sanitize form data
  $fullName  = htmlspecialchars(trim($_POST['fullName'] ?? ''));
  $email     = htmlspecialchars(trim($_POST['email'] ?? ''));
  $phone     = htmlspecialchars(trim($_POST['phone'] ?? ''));
  $location  = htmlspecialchars(trim($_POST['location'] ?? ''));
  $equipment = htmlspecialchars(trim($_POST['equipment'] ?? ''));

  // Basic required validation (server-side)
  if ($fullName === '' || $phone === '') {
    echo "Error: Full Name and Contact Number are required.";
    exit;
  }

  // Receiver email (Kerala)
  $to = "info@boomliftrentkerala.com"; // change if needed

  $subject = "New Enquiry - Boom / Scissor Lift Rental (Kerala)";

  // Email content
  $body = "New enquiry received from Kerala website:\n\n"
    . "Full Name: $fullName\n"
    . "Email: $email\n"
    . "Phone: $phone\n"
    . "Location: $location\n"
    . "Equipment: $equipment\n\n";

  $headers  = "From: Kerala Enquiry <noreply@yourdomain.com>\r\n";
  if (!empty($email)) {
    $headers .= "Reply-To: $email\r\n";
  }
  $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

  // Try sending the email
  if (mail($to, $subject, $body, $headers)) {
    // Redirect to thank you page on success
    header("Location: thank-you.html");
    exit;
  } else {
    echo "There was an error sending your enquiry. Please try again later or contact us by phone.";
    exit;
  }
} else {
  echo "Invalid request method.";
  exit;
}
