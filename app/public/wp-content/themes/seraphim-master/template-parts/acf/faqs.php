<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}	

?>
          
          

<?php if (have_rows('faqs')) : ?>
  <section class="mucFaqsSection">

    <div class="faq-list">
      <?php $i = 0; while (have_rows('faqs')) : the_row(); $i++; ?>
        <div class="faq-item" data-index="<?php echo $i; ?>">
          <button class="faq-question" aria-expanded="false">
            <span><?php the_sub_field('question'); ?></span>
            <svg class="faq-toggle" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M12 5v14M5 12h14"/>
            </svg>
          </button>
          <div class="faq-answer">
            <div class="inner">
              <?php the_sub_field('answer'); ?>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>

  </section>
<?php endif; ?>


<script>
 
 document.addEventListener("DOMContentLoaded", () => {
  const faqs = document.querySelectorAll(".faq-item");

  faqs.forEach(faq => {
    const btn = faq.querySelector(".faq-question");

    btn.addEventListener("click", () => {
      const isActive = faq.classList.contains("active");

      // Close all
      faqs.forEach(f => f.classList.remove("active"));

      // Reopen if it was closed
      if (!isActive) faq.classList.add("active");
    });
  });
});


</script>