(function (Drupal, once) {

  'use strict';

  Drupal.behaviors.LostFound = {
    attach: function (context, settings) {
        // Generic toggle Menu
      once('.toggla', 'button', context).forEach(function (button) {
          button.addEventListener('click', function () {
            let t = 'true' === button.getAttribute('aria-expanded');
          button.setAttribute('aria-expanded', !t)
          })
          document.addEventListener('click', function (event) {
            const toggleTarget = button.nextElementSibling;
            if (toggleTarget && !toggleTarget.contains(event.target) && !button.contains(event.target)) {
              button.setAttribute('aria-expanded', 'false');
            }
          })
        });
    }
  };
})(Drupal, once);
