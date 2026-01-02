<h1>Kontakt</h1>
<div class="contact-layout">
  <form class="contact-form" method="post" action="">
    <label>Ime
      <input type="text" name="name" required>
    </label>

    <label>Email
      <input type="email" name="email" required>
    </label>

    <label>Poruka
      <textarea name="message" rows="5" required></textarea>
    </label>

    <label class="hp-field" aria-hidden="true">Website
      <input type="text" name="website" tabindex="-1" autocomplete="off" inputmode="none">
    </label>

    <button class="btn" type="submit">Pošalji</button>
  </form>

  <div class="contact-media">
    <img src="assets/img/products/mis_plavi.png" alt="Kontakt">
  </div>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $message = trim($_POST['message'] ?? '');
  $honeypot = trim($_POST['website'] ?? ($_POST['company'] ?? ''));

  if ($honeypot !== '') {
    echo "<p class='notice'>Poruka je poslana.</p>";
    return;
  }

  if ($name !== '' && filter_var($email, FILTER_VALIDATE_EMAIL) && $message !== '') {
    $to = 'valerija.olic@gmail.com';
    $subject = 'Kontakt forma - hej!loop';
    $body = "Ime: {$name}\nEmail: {$email}\n\nPoruka:\n{$message}";
    $headers = "From: {$name} <{$email}>\r\nReply-To: {$email}\r\n";
    $sent = mail($to, $subject, $body, $headers);

    require __DIR__ . '/../config/db.php';

    $saved = false;
    $mailStatus = $sent ? 'sent' : 'failed';
    try {
      $stmt = $pdo->prepare('INSERT INTO contact_messages (name, email, message, mail_sent, mail_status) VALUES (:name, :email, :message, :mail_sent, :mail_status)');
      $stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':message' => $message,
        ':mail_sent' => $sent ? 1 : 0,
        ':mail_status' => $mailStatus,
      ]);
      $saved = true;
    } catch (Throwable $e) {
      $saved = false;
    }

    if ($sent && $saved) {
      echo "<p class='notice'>Poruka je poslana.</p>";
    } elseif ($sent) {
      echo "<p class='notice'>Poruka je poslana, ali nije spremljena.</p>";
    } elseif ($saved) {
      echo "<p class='notice'>Poruka je spremljena, ali mail nije poslan.</p>";
    } else {
      echo "<p class='notice'>Poruku nije moguće poslati. Pokušajte kasnije.</p>";
    }
  } else {
    echo "<p class='notice'>Molimo ispunite sva polja ispravno.</p>";
  }
}
?>
