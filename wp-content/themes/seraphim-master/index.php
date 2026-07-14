<?php get_header(); ?>


    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
      <?php get_template_part('template-parts/acf-loop'); ?>
    <?php endwhile; the_posts_pagination(); else: ?>
      <p><?php _e('No posts found.', 'make-us-care-3-0'); ?></p>
    <?php endif; ?>


        <!-- LATEST RESULTS -->

<div class="container">
    <div class="row">
        <div class="col-12 col-md-6">

            <div class="resultsDownloadCon">
                <div class="resultsDownloadHeader">
                    <h2>Latest results</h2>
                    <a href="#">View all results</a>
                </div>

                <div class="resultsDownloadList">
                    <div class="resultsDownloadItem">
                        <div class="resultsDownloadItem__content">
                            <h4>Interim Report (full) to 31 December 2025</h4>
                            <span class="caption">28 Mar 2026</span>
                        </div>

                        <a href="#" class="button secondary resultsDownloadItem__button">
                            Open document <i class="ci-Download"></i>
                        </a>
                    </div>

                    <div class="resultsDownloadItem">
                        <div class="resultsDownloadItem__content">
                            <h4>Quarterly Factsheet Q2 Fy2025/26</h4>
                            <span class="caption">28 Mar 2026</span>
                        </div>

                        <a href="#" class="button secondary resultsDownloadItem__button">
                            Open document <i class="ci-Download"></i>
                        </a>
                    </div>

                    <div class="resultsDownloadItem">
                        <div class="resultsDownloadItem__content">
                            <h4>Annual Report</h4>
                            <span class="caption">28 Mar 2026</span>
                        </div>

                        <a href="#" class="button secondary resultsDownloadItem__button">
                            Open document <i class="ci-Download"></i>
                        </a>
                    </div>

                    <div class="resultsDownloadItem">
                        <div class="resultsDownloadItem__content">
                            <h4>Interim Report (full) to 31 December 2025</h4>
                            <span class="caption">28 Mar 2026</span>
                        </div>

                        <a href="#" class="button secondary resultsDownloadItem__button">
                            Open document <i class="ci-Download"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-12 col-md-6">
            <div class="thirdPartyResearchCon">
                <div class="thirdPartyResearchHeader">
                    <h2>Third party research</h2>
                    <a href="#">View all research</a>
                </div>

                <div class="thirdPartyResearchList">
                    <a href="#" class="thirdPartyResearchItem">
                        <div class="thirdPartyResearchItem__image" style="background-image: url('src/img/card-background-ssit-v1.jpg');"></div>
                        <div class="thirdPartyResearchItem__content">
                            <div class="thirdPartyResearchItem__meta">
                                <span class="caption">Research</span>
                                <span class="caption">26 Mar 2026</span>
                            </div>
                            <h4>Third-Party Research on SSIT by Edison</h4>
                        </div>
                    </a>

                    <a href="#" class="thirdPartyResearchItem">
                        <div class="thirdPartyResearchItem__image" style="background-image: url('src/img/card-background-vc-v2.jpg');"></div>
                        <div class="thirdPartyResearchItem__content">
                            <div class="thirdPartyResearchItem__meta">
                                <span class="caption">Research</span>
                                <span class="caption">26 Mar 2026</span>
                            </div>
                            <h4>Third-Party Research on SSIT by Edison</h4>
                        </div>
                    </a>

                    <a href="#" class="thirdPartyResearchItem">
                        <div class="thirdPartyResearchItem__image" style="background-image: url('src/img/card-background-accelerator-v2.jpg');"></div>
                        <div class="thirdPartyResearchItem__content">
                            <div class="thirdPartyResearchItem__meta">
                                <span class="caption">Research</span>
                                <span class="caption">26 Mar 2026</span>
                            </div>
                            <h4>Third-Party Research on SSIT by Edison</h4>
                        </div>
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- LATEST RESULTS END -->



        <div class="container">
            <div class="row">
                <div class="col-7">

                    <h2 class="title prefix">
                        Our portfolio
                    </h2>
                    <h1>The companies in the SSIT portfolio</h1>
                    <p>Our entrepreneurs see the infinite possibilities of Space and transform those possibilities into game changing companies. Our portfolio companies are at the frontier of tomorrow shaping a better future on Earth. <a href="#" class="nonactive" rel="bookmark">Link name</a></p>

                    
                    <p class="label">
                        Label
                    </p>

                    <p>
                        <a href="#" rel="bookmark">Link name</a>
                    </p>

                    <p>
                        <a href="#" class="secondary" rel="bookmark">Secondary</a>
                    </p>

                    <button>For retail investor</button>

                    <button class="secondary">For retail investor <i class="ci-expand"></i></button>
                    
                    <button class="tertiary">For retail investor</button>

                    <button class="tertiary small">For retail investor</button>

                    <div>
                        <span class="caption">LSE: SSIT</span>
                    </div>

                </div>
            </div>

        </div>


        <div class="container">
            <div class="row">
                <div class="col-12 col-md-4">

                    <div class="notch-card">
                        <span class="notch-card__tab" aria-hidden="true"></span>
                        <div class="badge">
                            <span class="text">IPO</span>
                        </div>
                        <div class="imageCon" style="background-image: url('https://images.unsplash.com/photo-1610296669228-602fa827fc1f?w=900&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NXx8c3BhY2V8ZW58MHx8MHx8fDI%3D');">
                            <div class="logoCon">
                                <svg  viewBox="0 0 90 25"  style="width: 100%; height: auto;" fill="white" xmlns="http://www.w3.org/2000/svg"><path class="header-icon--fill" clip-rule="evenodd" d="m16.4592 13.2193c0 3.9134 2.2587 6.4579 5.0324 7.4741l-1.5862 4.0562c-1.8186-.5795-3.2054-1.5369-4.3846-2.6706-2.6657-2.5865-3.7038-5.4922-3.7038-8.9185 0-4.23256 1.7356-7.27259 3.7038-9.12853 2.3169-2.20864 5.1819-3.082019 8.4704-3.082019 1.4034 0 3.0476.243539 4.8248 1.041339l-1.5114 3.86303c-1.4034-.69703-2.674-.78101-3.2055-.78101-4.6338 0-7.6399 3.84623-7.6399 8.14599zm-11.9668-11.75708v23.32938h-4.4924v-23.32938zm55.2904 12.67238-7.9223-12.66398h5.323l4.9494 8.11236 4.9494-8.11236h5.323l-8.1299 12.66398v10.6654h-4.4926zm29.6298 6.7519h-6.4857v-6.374h4.692l1.5363-3.9134h-6.2283v-5.21506h4.9743l1.5363-3.92182h-11.0115v23.33778h9.4586zm-49.3027 0h6.2033l-1.5363 3.9135h-9.1597v-23.33778h10.7126l-1.5363 3.92182h-4.6836v5.21506h5.9375l-1.5363 3.9134h-4.4012z" fill-rule="evenodd"></path></svg>
                            </div>
                        </div>
                        <div class="contentCon">
                            <h4 class="title small">Company Name</h4>
                            <span class="white65">Short description of the company. Max 120 characters or two lines of copy.</span>
                        </div>

                        <div class="detailsCon">
                            
                            <div class="captionCon">
                                <span class="caption">Taxonomy</span>
                                <span class="caption">subcategory</span>
                               
                            </div>
                            
                            <div class="buttonCon">
                                <button class="tertiary small">Learn more</button>
                            </div>
                            
                        </div>

                    </div>

                </div>

                <div class="col-12 col-md-4">

                    <div class="notch-card">
                        <span class="notch-card__tab" aria-hidden="true"></span>
                        Our entrepreneurs see the infinite possibilities of Space and transform those possibilities into game changing companies. Our portfolio companies are at the frontier of tomorrow shaping a better future on Earth. Our entrepreneurs see the infinite possibilities of Space and transform those possibilities into game changing companies. Our portfolio companies are at the frontier of tomorrow shaping a better future on Earth. Our entrepreneurs see the infinite possibilities of Space and transform those possibilities into game changing companies. Our portfolio companies are at the frontier of tomorrow shaping a better future on Earth. Our entrepreneurs see the infinite possibilities of Space and transform those possibilities into game changing companies. Our portfolio companies are at the frontier of tomorrow shaping a better future on Earth.
                    </div>

                </div>

                <div class="col-12 col-md-4">

                    <div class="notch-card portfolio_card">
                        <span class="notch-card__tab" aria-hidden="true"></span>
                        <div class="badge">
                            <span class="text">IPO</span>
                        </div>
                        Our entrepreneurs see the infinite possibilities of Space and transform those possibilities into game changing companies. Our portfolio companies are at the frontier of tomorrow shaping a better future on Earth. 
                    </div>

                </div>
            </div>
        </div>




        <div class="container" style="padding-top: 200px; padding-bottom: 200px;">
            <div class="row">
                <div class="col-12">
                    <div class="card team-card big-card noHover">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="imageCon" style="background-image: url('https://seraphim.vc/wp-content/uploads/2024/11/Seraphim-MarkBoggett_1745_c-1024x683.jpg');"></div>
                            </div>
                            <div class="col-12 col-md-6 contentSection">
                               
                                <div class="contentCon">
                                    <h4 class="title">Mark Boggett</h4>
                                </div>

                                <div class="detailsCon">                            
                                    <span class="caption">Investment Manager, CEO & Managing Partner</span>
                                </div>

                                <div class="contentCon">
                                    <span class="white65">Mark is a pioneer in Space Tech investment having co-founded the Seraphim Space Fund and invested into a portfolio which includes three companies that have achieved billion-dollar valuations. Previously, Mark was a director at YFM Equity Partners, the firm behind the high profile British Smaller Companies VCT 1 & 2. He also worked at Brewin Dolphin and Williams de Broe. He completed his undergraduate degree in Accounting & Finance, Masters in Economics and Finance from the University of Leeds.</span>
                                    
                                    <a href="#" class="iconLink featuredTeamLinkedin">
                                        <div class="iconCon">
                                            <div class="circleIcon linkedin"></div>
                                            View LinkedIn profile
                                        </div>
                                    </a>

                                </div>

                                
                                    
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-4">

                    <div class="notch-card team-card">
                        <span class="notch-card__tab" aria-hidden="true"></span>
                        <div class="imageCon" style="background-image: url('https://seraphim.vc/wp-content/uploads/2024/11/Seraphim-MarkBoggett_1745_c-1024x683.jpg');"></div>
                        <div class="contentCon">
                            <h4 class="title small">Mark Boggett</h4>
                        </div>

                        <div class="detailsCon">                            
                            <span class="caption">Investment Manager, CEO & Managing Partner</span>
                        </div>
                        
                        <div class="textCtaCon">
                            <a href="#" class="openDrawer">Read more  <i class="ci-expand"></i></a>
                        </div>
                    </div>

                </div>

                <div class="col-12 col-md-4">

                    <div class="notch-card team-card">
                        <span class="notch-card__tab" aria-hidden="true"></span>
                        <div class="imageCon" style="background-image: url('https://seraphim.vc/wp-content/uploads/2024/11/Seraphim-MarkBoggett_1745_c-1024x683.jpg');"></div>
                        <div class="contentCon">
                            <h4 class="title small">Mark Boggett</h4>
                        </div>

                        <div class="detailsCon">                            
                            <span class="caption">Investment Manager, CEO & Managing Partner</span>
                        </div>
                        
                        <div class="textCtaCon">
                            <a href="#" class="openDrawer">Read more  <i class="ci-expand"></i></a>
                        </div>
                    </div>

                </div>

                <div class="col-12 col-md-4">

                    <div class="notch-card team-card">
                        <span class="notch-card__tab" aria-hidden="true"></span>
                        <div class="imageCon" style="background-image: url('https://seraphim.vc/wp-content/uploads/2024/11/Seraphim-MarkBoggett_1745_c-1024x683.jpg');"></div>
                        <div class="contentCon">
                            <h4 class="title small">Mark Boggett</h4>
                        </div>

                        <div class="detailsCon">                            
                            <span class="caption">Investment Manager, CEO & Managing Partner</span>
                        </div>
                        
                        <div class="textCtaCon">
                            <a href="#" class="openDrawer">Read more  <i class="ci-expand"></i></a>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="drawerCon">
            <div class="drawer">

                <div class="iconCon close closeDrawer" type="button" aria-label="Close drawer">
                    <div class="circleIcon grey"><i class="ci-close_LG"></i></div>
                </div>
                
                <div class="container-fluid">
                    <div class="row topRow">
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="imageCon" style="background-image: url('https://seraphim.vc/wp-content/uploads/2024/11/Seraphim-MarkBoggett_1745_c-1024x683.jpg');"></div>
                        </div>
                        <div class="col-12 col-md-9">
                            <h4 class="title">Mark Boggett</h4>
                            <span class="caption white65">Investment Manager, CEO & Managing Partner</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="bio white65">
                                Will Whitehorn was formerly a director of Virgin Group and President of Virgin Galactic until 2010. He has since pursued a private equity and non-executive career. He is the President of UKSpace, the trade body that represents the space industry in the UK, Chairman of AAC Clydespace, a listed satellite manufacturing company, Good Energy PLC, Scottish Event Campus Limited and Craneware PLC. He also sits on the board of the Royal Air Force and has recently retired as Deputy Chairman of Stagecoach Group PLC after serving on its board for nine years. Will has been a Fellow of the Royal Aeronautical Society since 2013.
                            </div>

                            <a href="#" class="iconLink">
                                <div class="iconCon">
                                    <div class="circleIcon linkedin"></div>
                                    View LinkedIn profile
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

              


            </div>
        </div>


        <div class="container" style="padding-bottom: 300px;">
            <div class="row">

                <div class="col-12 col-md-4">
                    <div class="ecosystemBox solar">
                        
                        <div class="activeCorners">
                            <div class="top"></div>
                            <div class="bottom"></div>
                        </div>

                        <div class="ecosystemTitle">Launchpad</div>
                        <div class="contentCon">
                            <h4 class="title small">Company Name</h4>
                            <span class="white65">Short description of the company. Max 120 characters or two lines of copy.</span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="ecosystemBox aqua">
                        
                        <div class="activeCorners">
                            <div class="top"></div>
                            <div class="bottom"></div>
                        </div>

                        <div class="ecosystemTitle">Launchpad</div>
                        <div class="contentCon">
                            <h4 class="title small">Company Name</h4>
                            <span class="white65">Short description of the company. Max 120 characters or two lines of copy.</span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="ecosystemBox terrain">
                        
                        <div class="activeCorners">
                            <div class="top"></div>
                            <div class="bottom"></div>
                        </div>

                        <div class="ecosystemTitle">Launchpad</div>
                        <div class="contentCon">
                            <h4 class="title small">Company Name</h4>
                            <span class="white65">Short description of the company. Max 120 characters or two lines of copy.</span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="ecosystemBox manager">
                        
                        <div class="activeCorners">
                            <div class="top"></div>
                            <div class="bottom"></div>
                        </div>

                        <div class="ecosystemTitle">Launchpad</div>
                        <div class="contentCon">
                            <h4 class="title small">Company Name</h4>
                            <span class="ecosystemBody white65">Short description of the company. Max 120 characters or two lines of copy.</span>
                        </div>
                    </div>
                </div>

                

            </div>
        </div>



     <script src="src/script.js" type="text/javascript"></script>


<?php get_footer(); ?>
