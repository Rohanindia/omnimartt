<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Categories - OmniMart</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'includes/navbar.php'; ?>

<div class="page-hero">
  <h1>Shop by Category</h1>
  <p>Explore thousands of products across all categories</p>
</div>

<section>
  <div class="categories-full-grid">

    <a href="products.php?category=electronics" class="cat-full-card">
      <img class="cat-full-img" src="https://images.unsplash.com/photo-1498049794561-7780e7231661?w=700&q=80" alt="Electronics">
      <div class="cat-full-info">
        <h3>⚡ Electronics</h3>
        <p>Smartphones, laptops, headphones, smartwatches and the latest tech gadgets at the best prices.</p>
        <span class="cat-btn">Browse Electronics →</span>
      </div>
    </a>

    <a href="products.php?category=fashion" class="cat-full-card">
      <img class="cat-full-img" src="https://images.unsplash.com/photo-1445205170230-053b83016050?w=700&q=80" alt="Fashion">
      <div class="cat-full-info">
        <h3>👗 Fashion</h3>
        <p>Men's and women's clothing, footwear, accessories and the latest fashion trends.</p>
        <span class="cat-btn">Browse Fashion →</span>
      </div>
    </a>

    <a href="products.php?category=home" class="cat-full-card">
      <img class="cat-full-img" src="https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=700&q=80" alt="Home">
      <div class="cat-full-info">
        <h3>🏠 Home & Living</h3>
        <p>Furniture, home decor, kitchen essentials and everything to make your home beautiful.</p>
        <span class="cat-btn">Browse Home →</span>
      </div>
    </a>

    <a href="products.php?category=sports" class="cat-full-card">
      <img class="cat-full-img" src="https://images.unsplash.com/photo-1517836357463-d25dfeac3438?w=700&q=80" alt="Sports">
      <div class="cat-full-info">
        <h3>🏋️ Sports & Fitness</h3>
        <p>Gym equipment, yoga accessories, outdoor gear and sports nutrition for an active lifestyle.</p>
        <span class="cat-btn">Browse Sports →</span>
      </div>
    </a>

    <a href="products.php?category=books" class="cat-full-card">
      <img class="cat-full-img" src="https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=700&q=80" alt="Books">
      <div class="cat-full-info">
        <h3>📚 Books</h3>
        <p>Bestsellers, textbooks, self-help, fiction and non-fiction books for every reader.</p>
        <span class="cat-btn">Browse Books →</span>
      </div>
    </a>

    <a href="products.php?category=beauty" class="cat-full-card">
      <img class="cat-full-img" src="https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=700&q=80" alt="Beauty">
      <div class="cat-full-info">
        <h3>💄 Beauty & Care</h3>
        <p>Skincare, makeup, hair care and wellness products from top brands worldwide.</p>
        <span class="cat-btn">Browse Beauty →</span>
      </div>
    </a>

  </div>
</section>

<?php include 'includes/footer.php'; ?>
<script src="js/script.js"></script>
</body>
</html>
