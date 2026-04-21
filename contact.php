<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
$sent = isset($_GET['sent']);
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us - OmniMart</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'includes/navbar.php'; ?>

<div class="page-hero">
  <h1>Get in Touch</h1>
  <p>We'd love to hear from you. Our team is here to help!</p>
</div>

<section style="max-width:900px;margin:0 auto">
  <?php if ($sent): ?>
  <div class="success-msg" style="margin-bottom:24px;text-align:center">
    ✅ Message sent! We'll get back to you within 24 hours.
  </div>
  <?php endif; ?>

  <?php if ($error): ?>
  <div style="background:rgba(239,68,68,0.15);border:1px solid #EF4444;border-radius:12px;padding:14px 20px;margin-bottom:24px;text-align:center;color:#EF4444;font-weight:600;">
    <?php if ($error === 'missing_fields'): ?>
      ⚠️ Please fill in all required fields.
    <?php else: ?>
      ❌ Something went wrong. Please try again.
    <?php endif; ?>
  </div>
  <?php endif; ?>

  <div class="contact-grid">
    <!-- Contact Info -->
    <div class="contact-info">
      <h3>Let's Talk 💬</h3>
      <p>Have a question about your order, a product, or just want to say hello? Reach out through any channel below.</p>
      <div class="contact-item">
        <span class="contact-icon">📧</span>
        <div class="contact-text">
          <strong>Email Us</strong>
          <span>support@omnimart.in</span>
        </div>
      </div>
      <div class="contact-item">
        <span class="contact-icon">📞</span>
        <div class="contact-text">
          <strong>Call Us</strong>
          <span>+91 98765 43210 (Mon–Sat, 9AM–6PM)</span>
        </div>
      </div>
      <div class="contact-item">
        <span class="contact-icon">📍</span>
        <div class="contact-text">
          <strong>Office</strong>
          <span>OmniMart HQ, BKC, Mumbai, 400051</span>
        </div>
      </div>
      <div class="contact-item">
        <span class="contact-icon">💬</span>
        <div class="contact-text">
          <strong>Live Chat</strong>
          <span>Available on the website 24/7</span>
        </div>
      </div>
    </div>

    <!-- Contact Form -->
    <div style="background:var(--dark-3);border:1px solid var(--border);border-radius:var(--radius-lg);padding:28px">
      <h3 style="font-family:var(--font-display);font-size:20px;margin-bottom:20px">Send a Message</h3>
      <form action="backend/api/contact.php" method="POST">
        <div class="form-group">
          <label>Your Name</label>
          <input type="text" name="name" required placeholder="John Doe"
            value="<?= isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : '' ?>">
        </div>
        <div class="form-group">
          <label>Email Address</label>
          <input type="email" name="email" required placeholder="you@example.com">
        </div>
        <div class="form-group">
          <label>Subject</label>
          <select name="subject">
            <option>Order Issue</option>
            <option>Product Inquiry</option>
            <option>Return / Refund</option>
            <option>Technical Support</option>
            <option>Other</option>
          </select>
        </div>
        <div class="form-group">
          <label>Message</label>
          <textarea name="message" required placeholder="Tell us how we can help..."></textarea>
        </div>
        <button type="submit" class="form-submit">Send Message →</button>
      </form>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
<script src="js/script.js"></script>
</body>
</html>
