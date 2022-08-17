(function ($, Drupal, drupalSettings) {

  'use strict';

  Drupal.behaviors.LostFound = {
    attach: function (context, settings) {
        // Generic toggle Menu
        $('.toggla', context).once('myToggla').each(function () {
          this.addEventListener('click', function () {
            let t = 'true' === this.getAttribute('aria-expanded');
            this.setAttribute('aria-expanded', !t)
          })
          const toggla = this;
          document.addEventListener('click', function (event) {
            const toggleTarget = toggla.nextElementSibling;
            if (!toggleTarget.contains(event.target) && !toggla.contains(event.target)) {
                toggla.setAttribute('aria-expanded', 'false');
            }
          })
        });
    }
  };
})(jQuery, Drupal, drupalSettings);
