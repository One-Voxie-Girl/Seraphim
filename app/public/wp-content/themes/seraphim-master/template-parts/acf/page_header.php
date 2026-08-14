<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}	
	
$title = get_sub_field('title');
$header_body_copy = get_sub_field('header_body_copy');
$background_colour = get_sub_field('background_colour');
$show_project_overview = get_sub_field('show_project_overview');



if ($background_colour == "multi") {
  $background_colour == "#FFF0E6";
 
} else { ?>

 <style>
  .heart {--accent: #000 !important; }
  .mucSectorsSection .text-col .sector-list li.active, .quoteCon .marks {color: <?php echo $background_colour; ?>; }
  </style>

<?php 
}

?>


<!-- HEADER -->

<section class="pageHeaderCon">

    <div class="headerVideoCon">
        <video autoplay muted loop playsinline preload="metadata" aria-hidden="true">
            <source src="https://cdn.skiv.com/u/BkGdVMp/dd31cbbf798d6e3a0e43c427628b0a5d77e57e63058eee1a1539f1efd61ae4c7/videos/video.mp4" type="video/mp4">
        </video>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-7">

                <h2 class="title prefix">
                    The world's first listed SpaceTech fund
                </h2>
                <h1>invest in the companies building the space economy</h1>
                <p>A publicly listed fund giving you access to the multi-trillion-dollar global SpaceTech sector through a single LSE-listed share.</p>
                
                <div class="buttonsCon">
                    <a href="#" class="button">
                        For retail investor
                    </a>

                    <a href="#" class="button secondary">
                        For retail investor <i class="ci-expand"></i>
                    </a>

                </div>

            </div>

            <div class="col-12 col-lg-5 tickerCol">

                <div class="tickerCon">
                    <div class="tickerPanel">
                        <div class="tickerPanel__item">
                            <div class="tickerPanel__value">
                                SSIT: <span>130.5p</span> <span class="tickerPanel__divider">|</span> SSIC: <span>230.5p</span>
                            </div>
                            <div class="tickerPanel__meta">23 Mar 2026 &middot; LSE close</div>
                        </div>

                        <div class="tickerPanel__item">
                            <p class="tickerPanel__value">
                                NAV per share: <span>142.3p</span>
                            </p>
                            <p class="tickerPanel__meta">As at 31 Dec 2025</p>
                        </div>
                    </div>

                    <div class="tickerMeta">
                        <span class="caption">LSE:SSIT</span>
                        <span class="caption">ISIN: GB00BKPG0138</span>
                        <span class="caption">FCA REGULATED</span>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <div class="container">
        <div class="row statsRowCon">
    
            <div class="activeCorners">
                <div class="top"></div>
                <div class="bottom"></div>
            </div>

        
            <div class="col-12 col-md-3">
                <h2>$332m</h2>
                <p>Value</p>
            </div>
            <div class="col-12 col-md-3">
                <h2>20+</h2>
                <p>Value</p>
            </div>
            <div class="col-12 col-md-3">
                <h2>198%</h2>
                <p>Value</p>
            </div>
            <div class="col-12 col-md-3">
                <h2>12</h2>
                <p>Value</p>
            </div>
        </div>
    </div>

</section>

<!-- HEADER END -->