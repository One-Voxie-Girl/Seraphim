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
	      <span class="navbar-toggler-icon" aria-hidden="true">
	        <span class="navbar-toggler-line"></span>
	        <span class="navbar-toggler-line"></span>
	        <span class="navbar-toggler-line"></span>
	      </span>
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
	          <a class="nav-link" href="#" id="productsDropdown" role="button"
	             data-bs-toggle="dropdown" aria-expanded="false">
	            Investor Relations
	          </a>
          <ul class="dropdown-menu" aria-labelledby="productsDropdown">
            <li><a class="dropdown-item" href="#">Item 1</a></li>
 
            <!-- Second-level dropdown (submenu) -->
            <li class="dropdown-submenu">
	              <a class="dropdown-item submenu-item" href="#" id="electronicsDropdown">Item 2</a>
              <ul class="dropdown-menu" aria-labelledby="electronicsDropdown">
                <li><a class="dropdown-item" href="#">One</a></li>
                <li><a class="dropdown-item" href="#">Two</a></li>
                <li><a class="dropdown-item" href="#">Three</a></li>
              </ul>
            </li>
 
            <li class="dropdown-submenu">
	              <a class="dropdown-item submenu-item" href="#" id="clothingDropdown">Item 3</a>
              <ul class="dropdown-menu" aria-labelledby="clothingDropdown">
                <li><a class="dropdown-item" href="#">One</a></li>
                <li><a class="dropdown-item" href="#">Two</a></li>
                <li><a class="dropdown-item" href="#">Three</a></li>
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
          <a class="nav-link" href="#">Why Space?</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="#">Contact</a>
        </li>

      </ul>
    </div>
  </div>
</nav>
