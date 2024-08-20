<?php

/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package MaxiBlocks_Library
 */

?>

<footer id="colophon" class="site-footer">
	<div class="site-info">

	</div><!-- .site-info -->
</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>
<script type="text/javascript">
	if (window.self !== window.top) { 
    	document.body.classList.add('hide-scrollbar');
} 
	
	// Function to prevent default click behavior
    const preventDefaultClick = function(event) {
      event.preventDefault();
      // event.stopPropagation(); // Uncomment if you want to stop the event from bubbling up
    };
	
// Function to disable link clicks based on current URL and href attribute
function disableLinksIfPatternInURLorHref() {
  // Disable clicks based on current URL
  if (window.location.href.includes('block-pattern')) {
    // Selector for links with specified classes inside 'maxi-column-block' divs
    const classSelector = '.maxi-column-block .maxi-link-wrapper, ' +
                          '.maxi-column-block .maxi-components-button, ' +
                          '.maxi-column-block .maxi-text-block--link';

    // Get all links matching the class selector
    const linksWithClasses = document.querySelectorAll(classSelector);

    
    
    // Iterate through each link and disable its click event
    linksWithClasses.forEach(link => {
      link.addEventListener('click', preventDefaultClick, false);
    });
  }

  // Disable clicks for any link with 'posts-for-patterns' in its href
  // Get all links on the page
  const allLinks = document.querySelectorAll('.maxi-column-block a');

  // Iterate through each link and disable click if href contains 'posts-for-patterns'
  allLinks.forEach(link => {
    if (link.href.includes('posts-for-patterns') || link.href.includes('blog-pattern')) {
      link.addEventListener('click', preventDefaultClick, false);
    }
  });
}

// Run the function when the window is loaded to ensure all elements are present
window.addEventListener('load', disableLinksIfPatternInURLorHref);

</script>

<style>
	/* Standard scrollbars when hide-scrollbar class is present */
@media (max-width: 1199px) {
    .hide-scrollbar {
        -ms-overflow-style: none;  /* For Internet Explorer and Edge */
        scrollbar-width: none; /* For Firefox */
    }
    .hide-scrollbar::-webkit-scrollbar {
        width: 0!important;  /* For WebKit browsers */
    }
}

/* For mobile devices that respect the `hover` media feature */
@media (hover: none) and (pointer: coarse), (max-width: 1199px) {
    .hide-scrollbar {
        -ms-overflow-style: none;  /* For Internet Explorer and Edge */
        scrollbar-width: none; /* For Firefox */
    }
    .hide-scrollbar::-webkit-scrollbar {
        width: 0!important;  /* For WebKit browsers */
    }
}
</style>
</body>

</html>
