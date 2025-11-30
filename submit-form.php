<?php
// ---------------------------------------------
// submit-form.php
// Handles enquiries from the Kerala form
// ---------------------------------------------

// Change this to the email where you want to receive enquiries:
$recipientEmail = "info@boomliftrentalskerala.com";
$recipientName  = "BoomLift Rentals Kerala";

// Helper: sanitize input
function clean($value) {
    return trim(filter_var($value, FILTER_SANITIZE_STRING));
}

// If not a POST request, stop here
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405); // Method Not Allowed
    echo "Method not allowed.";
    exit;
}

// Collect and sanitize fields
$fullName  = isset($_POST["fullName"])  ? clean($_POST["fullName"])  : "";
$email     = isset($_POST["email"])     ? trim($_POST["email"])       : "";
$phone     = isset($_POST["phone"])     ? clean($_POST["phone"])     : "";
$location  = isset($_POST["location"])  ? clean($_POST["location"])  : "";
$equipment = isset($_POST["equipment"]) ? clean($_POST["equipment"]) : "";
$message   = isset($_POST["message"])   ? trim($_POST["message"])    : "";

// Basic validation
$errors = [];

if ($fullName === "") {
    $errors[] = "Full Name is required.";
}

if ($email === "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "A valid Email Address is required.";
}

if ($phone === "") {
    $errors[] = "Contact Number is required.";
}

if ($location === "") {
    $errors[] = "Area / Location is required.";
}

if ($equipment === "") {
    $errors[] = "Please select an equipment type.";
}

// Build email content only if no errors
$mailSent = false;
if (empty($errors)) {
    $subject = "New Rental Enquiry - BoomLift Rentals Kerala";

    $bodyLines = [
        "You have received a new rental enquiry from the website.",
        "",
        "Full Name:  " . $fullName,
        "Email:      " . $email,
        "Phone:      " . $phone,
        "Location:   " . $location,
        "Equipment:  " . $equipment,
        "",
        "Message / Request:",
        $message !== "" ? $message : "(No additional message provided)",
        "",
        "Time: " . date("Y-m-d H:i:s")
    ];

    $body = implode("\n", $bodyLines);

    // Email headers
    $headers   = "From: Website Enquiry <no-reply@boomliftrentalskerala.com>\r\n";
    $headers  .= "Reply-To: " . $email . "\r\n";
    $headers  .= "X-Mailer: PHP/" . phpversion();

    // Try to send email
    $mailSent = @mail($recipientEmail, $subject, $body, $headers);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Enquiry Status - BoomLift Rentals Kerala</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Oswald Font -->
  <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    *, *::before, *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: "Oswald", sans-serif;
    }

    body {
      background-color: #F9F6F3;
      color: #111;
      line-height: 1.4;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      padding: 2rem 1rem;
    }

    .status-wrapper {
      max-width: 600px;
      width: 100%;
      background-color: #FFFFFF;
      border-radius: 22px;
      padding: 2rem 1.8rem 2.3rem;
      box-shadow: 0 18px 45px rgba(0, 0, 0, 0.18);
      border: 1px solid #E1DDD5;
    }

    .status-tag {
      font-size: 0.78rem;
      letter-spacing: 0.22em;
      text-transform: uppercase;
      color: #872341;
      margin-bottom: 0.6rem;
    }

    .status-title {
      font-size: 1.6rem;
      text-transform: uppercase;
      margin-bottom: 0.6rem;
    }

    .status-text {
      font-size: 0.95rem;
      color: #444;
      margin-bottom: 1.2rem;
    }

    .status-list {
      margin: 0 0 1.2rem 1.1rem;
      font-size: 0.9rem;
      color: #872341;
    }

    .status-list li {
      margin-bottom: 0.25rem;
    }

    .status-meta {
      font-size: 0.8rem;
      color: #777;
      margin-bottom: 1.3rem;
    }

    .btn {
      display: inline-block;
      padding: 0.6rem 1.4rem;
      border-radius: 999px;
      border: none;
      outline: none;
      text-decoration: none;
      font-size: 0.9rem;
      font-weight: 600;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      cursor: pointer;
      background-color: #872341;
      color: #F9F6F3;
      transition: background-color 0.25s ease, color 0.25s ease;
      white-space: nowrap;
    }

    .btn:hover {
      background-color: #A6B28B;
      color: #111;
    }

    .status-contact {
      margin-top: 1.2rem;
      font-size: 0.9rem;
    }

    .status-contact a {
      color: #872341;
      text-decoration: none;
    }

    .status-contact a:hover {
      text-decoration: underline;
    }

    @media (max-width: 480px) {
      .status-wrapper {
        padding: 1.7rem 1.3rem 2rem;
      }

      .status-title {
        font-size: 1.4rem;
      }
    }
  </style>
</head>
<body>
  <div class="status-wrapper">
    <?php if (!empty($errors)): ?>
      <!-- Validation errors -->
      <div class="status-tag">Form not sent</div>
      <h1 class="status-title">Please check the details and try again.</h1>
      <p class="status-text">
        We couldn’t process your enquiry because some required information is missing or invalid.
        Please review the points below and go back to the form.
      </p>

      <ul class="status-list">
        <?php foreach ($errors as $error): ?>
          <li><?php echo htmlspecialchars($error); ?></li>
        <?php endforeach; ?>
      </ul>

      <p class="status-meta">
        Use your browser’s Back button to return to the form and update the highlighted fields.
      </p>

      <button class="btn" onclick="history.back();">
        Click here to go back to the form
      </button>

    <?php elseif ($mailSent): ?>
      <!-- Success -->
      <div class="status-tag">Enquiry sent successfully</div>
      <h1 class="status-title">Thank you for contacting BoomLift Rentals Kerala.</h1>
      <p class="status-text">
        We’ve received your enquiry and will get back to you as soon as possible with details on boom lift,
        scissor lift or man lift options that match your requirement.
      </p>

      <p class="status-meta">
        You can close this window or return to the website to continue browsing.
      </p>

      <button class="btn" onclick="window.location.href='index.html';">
        Click here to go back to the homepage
      </button>

    <?php else: ?>
      <!-- Mail failed -->
      <div class="status-tag">Enquiry not sent</div>
      <h1 class="status-title">We’re sorry, something went wrong.</h1>
      <p class="status-text">
        Your details were captured but we could not send the email right now. This may be due to a temporary
        server configuration or email issue.
      </p>

      <p class="status-meta">
        Please try again in a few minutes. If the issue continues, contact us directly using the phone number
        or email below.
      </p>

      <button class="btn" onclick="history.back();">
        Click here to try the form again
      </button>

    <?php endif; ?>

    <div class="status-contact">
      CALL: <a href="tel:+919999999999">+91 99999 99999</a><br>
      EMAIL: <a href="mailto:info@boomliftrentalskerala.com">info@boomliftrentalskerala.com</a>
    </div>
  </div>
</body>
</html>
