/**
 * @file
 * A JavaScript file for the theme.
 *
 * In order for this JavaScript to be loaded on pages, see the instructions in
 * the README.txt next to this file.
 */

// JavaScript should be made compatible with libraries other than jQuery by
// wrapping it with an "anonymous closure". See:
// - https://drupal.org/node/1446420
// - http://www.adequatelygood.com/2010/3/JavaScript-Module-Pattern-In-Depth
(function ($, Drupal, window, document, undefined) {


// To understand behaviors, see https://drupal.org/node/756722#behaviors
Drupal.behaviors.mainMenuTinyNav = {
  attach: function(context, settings) {
    $(".region-navigation .block__content > .menu").tinyNav();
  }
};

Drupal.behaviors.mainMenuSuperfish = {
  attach: function(context, settings) {
	  
	  var superfish_menu = $(".region-navigation .block__content > .menu");
	  
		superfish_menu.addClass('sf-menu');
    superfish_menu.superfish({ 
			autoArrows:  false                           
		});
  }
};

Drupal.behaviors.responsiveSlides = {
    attach: function(context, settings) {

        $(".view-slideshow ul:not(.contextual-links)").responsiveSlides({
            "auto": true,
            "pager": true,           // Boolean: Show pager, true or false
            "pauseButton": true,   // Boolean: Create Pause Button
        });

    }
};


Drupal.behaviors.switchTheme = {
    attach: function(context, settings) {


        // Retrieve the object from storage
        var retrievedObject = localStorage.getItem('testObject');
        var currentTheme = JSON.parse(retrievedObject);

        if (currentTheme == null) {
            currentThemeClass = "default";
        } else {
            currentThemeClass = currentTheme.current_theme;
        }

        if(currentThemeClass) {
            $("body").addClass("theme-" + currentThemeClass);
            $(".theme-switch li.switch-" + currentThemeClass).addClass('active');
        }

        $(".theme-switch a").click(function(e) {

            theme_class = $(this).text();

            $(".theme-switch li").removeClass('active');

            $("body").removeClass (function (index, css) {
                return (css.match (/\btheme-\S+/g) || []).join(' ');
            });

            $("body").addClass("theme-" + theme_class);

            var $parent = $(this).parent();
            if (!$parent.hasClass('active')) {
                $parent.addClass('active');
            }
            var testObject = { 'current_theme': theme_class };

            // Put the object into storage
            localStorage.setItem('testObject', JSON.stringify(testObject));

            e.preventDefault();

        });

    }
};


})(jQuery, Drupal, this, this.document);
