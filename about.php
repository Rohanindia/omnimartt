<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us - OmniMart</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'includes/navbar.php'; ?>

<div class="about-hero">
  <h1>We are <span>OmniMart</span></h1>
  <p>India's fastest growing e-commerce platform. We believe everyone deserves access to premium products at honest prices.</p>
</div>

<section style="max-width:1100px;margin:0 auto">
  <!-- Stats -->
  <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:20px;margin-bottom:60px">
    <?php foreach ([
      ['10M+', 'Happy Customers'],
      ['5L+', 'Products Listed'],
      ['500+', 'Brand Partners'],
      ['98%', 'Satisfaction Rate']
    ] as $stat): ?>
    <div style="background:var(--dark-3);border:1px solid var(--border);border-radius:var(--radius-lg);padding:30px;text-align:center">
      <div style="font-family:var(--font-display);font-size:36px;font-weight:800;background:linear-gradient(135deg,#FF4F18,#FF9A00);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text"><?= $stat[0] ?></div>
      <div style="color:var(--text-secondary);font-size:14px;margin-top:6px"><?= $stat[1] ?></div>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Story -->
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:40px;align-items:center;margin-bottom:60px">
    <div>
      <div style="font-size:13px;color:var(--primary);text-transform:uppercase;letter-spacing:2px;margin-bottom:12px">OUR STORY</div>
      <h2 style="font-family:var(--font-display);font-size:36px;font-weight:700;letter-spacing:-0.5px;margin-bottom:16px">Built for India,<br>Made for Everyone</h2>
      <p style="color:var(--text-secondary);line-height:1.8;margin-bottom:16px">
        OmniMart was founded with a simple mission: make premium products accessible to every Indian household. From bustling metros to tier-3 cities, we deliver quality and reliability at your doorstep.
      </p>
      <p style="color:var(--text-secondary);line-height:1.8">
        Our curated catalog spans electronics, fashion, home decor, sports, books and beauty — everything you need, all in one place. With over 500 brand partners and millions of satisfied customers, we're India's most trusted shopping destination.
      </p>
    </div>
    <div style="border-radius:var(--radius-lg);overflow:hidden;height:300px">
      <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?w=700&q=80" style="width:100%;height:100%;object-fit:cover" alt="Team">
    </div>
  </div>

  <!-- Values -->
  <div style="margin-bottom:60px">
    <h2 style="font-family:var(--font-display);font-size:28px;font-weight:700;text-align:center;margin-bottom:32px">Our Values</h2>
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:20px">
      <?php foreach ([
        ['🛡️', 'Trust & Safety', 'Every product is verified. Every transaction is secured. Your trust is our #1 priority.'],
        ['🚀', 'Fast Delivery', 'Express delivery options across India. Same-day delivery in select cities.'],
        ['💚', 'Sustainability', 'We partner with eco-conscious brands and use sustainable packaging where possible.']
      ] as $val): ?>
      <div style="background:var(--dark-3);border:1px solid var(--border);border-radius:var(--radius-lg);padding:28px">
        <div style="font-size:36px;margin-bottom:14px"><?= $val[0] ?></div>
        <h3 style="font-family:var(--font-display);font-size:18px;margin-bottom:8px"><?= $val[1] ?></h3>
        <p style="color:var(--text-secondary);font-size:14px;line-height:1.6"><?= $val[2] ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
<script src="js/script.js"></script>
</body>
</html>
