/**
 * File navigation.js.
 *
 * Handles toggling the navigation menu for small screens and enables TAB key
 * navigation support for dropdown menus.
 */
(function () {
  const siteNavigation = document.getElementById('site-navigation');

  // Return early if the navigation don't exist.
  if (!siteNavigation) {
    return;
  }

  const button = siteNavigation.getElementsByTagName('button')[0];

  // Return early if the button don't exist.
  if (typeof button === 'undefined') {
    return;
  }

  const menu = siteNavigation.getElementsByTagName('ul')[0];

  // Hide menu toggle button if menu is empty and return early.
  if (typeof menu === 'undefined') {
    button.style.display = 'none';
    return;
  }

  if (!menu.classList.contains('nav-menu')) {
    menu.classList.add('nav-menu');
  }

  // Toggle the .toggled class and the aria-expanded value each time the button is clicked.
  button.addEventListener('click', () => {
    siteNavigation.classList.toggle('toggled');

    if (button.getAttribute('aria-expanded') === 'true') {
      button.setAttribute('aria-expanded', 'false');
    } else {
      button.setAttribute('aria-expanded', 'true');
    }
  });

  // Remove the .toggled class and set aria-expanded to false when the user clicks outside the navigation.
  document.addEventListener('click', (event) => {
    const isClickInside = siteNavigation.contains(event.target);

    if (!isClickInside) {
      siteNavigation.classList.remove('toggled');
      button.setAttribute('aria-expanded', 'false');
    }
  });

  // Get all the link elements within the menu.
  const links = menu.getElementsByTagName('a');

  // Get all the link elements with children within the menu.
  const linksWithChildren = menu.querySelectorAll(
    '.menu-item-has-children > a, .page_item_has_children > a',
  );

  // Toggle focus each time a menu link is focused or blurred.
  for (const link of links) {
    link.addEventListener('focus', toggleFocus, true);
    link.addEventListener('blur', toggleFocus, true);
  }

  // Toggle focus each time a menu link with children receive a touch event.
  for (const link of linksWithChildren) {
    link.addEventListener('touchstart', toggleFocus, false);
  }

  /**
     * Sets or removes .focus class on an element.
     */
  function toggleFocus() {
    if (event.type === 'focus' || event.type === 'blur') {
      let self = this;
      // Move up through the ancestors of the current link until we hit .nav-menu.
      while (!self.classList.contains('nav-menu')) {
        // On li elements toggle the class .focus.
        if (self.tagName.toLowerCase() === 'li') {
          self.classList.toggle('focus');
        }
        self = self.parentNode;
      }
    }

    if (event.type === 'touchstart') {
      const menuItem = this.parentNode;
      event.preventDefault();
      for (const link of menuItem.parentNode.children) {
        if (menuItem !== link) {
          link.classList.remove('focus');
        }
      }
      menuItem.classList.toggle('focus');
    }
  }
}());

if (window.self !== window.top) {
  document.body.classList.add('hide-scrollbar');
}

// Function to prevent default click behavior
const preventDefaultClick = function (event) {
  event.preventDefault();
  // event.stopPropagation(); // Uncomment if you want to stop the event from bubbling up
};

// Function to disable link clicks based on current URL and href attribute
function disableLinksIfPatternInURLorHref() {
  // Disable clicks based on current URL
  if (window.location.href.includes('block-pattern')) {
    // Selector for links with specified classes inside 'maxi-column-block' divs
    const classSelector = '.maxi-column-block .maxi-link-wrapper, '
            + '.maxi-column-block .maxi-components-button, '
            + '.maxi-column-block .maxi-text-block--link';

    // Get all links matching the class selector
    const linksWithClasses = document.querySelectorAll(classSelector);

    // Iterate through each link and disable its click event
    linksWithClasses.forEach((link) => {
      link.addEventListener('click', preventDefaultClick, false);
    });
  }

  // Disable clicks for any link with 'posts-for-patterns' in its href
  // Get all links on the page
  const allLinks = document.querySelectorAll('.maxi-column-block a');

  // Iterate through each link and disable click if href contains 'posts-for-patterns'
  allLinks.forEach((link) => {
    if (
      link.href.includes('posts-for-patterns')
            || link.href.includes('blog-pattern')
    ) {
      link.addEventListener('click', preventDefaultClick, false);
    }
  });
}

// Run the function when the window is loaded to ensure all elements are present
window.addEventListener('load', disableLinksIfPatternInURLorHref);
