<?php
/**
 * Header template
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>

</head>
<body <?php body_class(); ?>>


<nav class="navbar navbar-expand-lg navbar-dark fixed-top headerCon">
  <div class="container-fluid">

    <a class="navbar-brand" href="#">
        <img src="https://investors.seraphim.vc/wp-content/themes/seraphimvc/src/images/logo.png" />
    </a>
 
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#mainNavbar" aria-controls="mainNavbar"
            aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
 
    <div class="collapse navbar-collapse" id="mainNavbar">
        
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
 
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Portfolio</a>
        </li>
 
        <li class="nav-item">
          <a class="nav-link" href="#">Share Data</a>
        </li>

        <!-- Top-level dropdown -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-item" href="#" id="productsDropdown" role="button"
             data-bs-toggle="dropdown" aria-expanded="false">
            Investor Relations
          </a>
          <ul class="dropdown-menu" aria-labelledby="productsDropdown">
            <li><a class="dropdown-item" href="#">All Products</a></li>
 
            <!-- Second-level dropdown (submenu) -->
            <li class="dropdown-submenu">
              <a class="dropdown-item dropdown-item" href="#" id="electronicsDropdown">Electronics</a>
              <ul class="dropdown-menu" aria-labelledby="electronicsDropdown">
                <li><a class="dropdown-item" href="#">Phones</a></li>
                <li><a class="dropdown-item" href="#">Laptops</a></li>
                <li><a class="dropdown-item" href="#">Accessories</a></li>
              </ul>
            </li>
 
            <li class="dropdown-submenu">
              <a class="dropdown-item dropdown-item" href="#" id="clothingDropdown">Clothing</a>
              <ul class="dropdown-menu" aria-labelledby="clothingDropdown">
                <li><a class="dropdown-item" href="#">Men</a></li>
                <li><a class="dropdown-item" href="#">Women</a></li>
                <li><a class="dropdown-item" href="#">Kids</a></li>
              </ul>
            </li>
          </ul>
        </li>

        
        <li class="nav-item">
          <a class="nav-link" href="#">Insights</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="#">About</a>
        </li>
    </ul>

    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="#">SSIT</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="#">Contact</a>
        </li>

      </ul>
    </div>
  </div>
</nav>


<main id="content" class="site-content">