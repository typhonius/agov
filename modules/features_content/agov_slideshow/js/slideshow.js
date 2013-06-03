/**
 * @file
 * Controls the slideshow for home page feature articles
 *
 */

(function ($) {
  Drupal.behaviors.featureArticles = {
    attach: function (context, settings) {

			/**
			* Global variables
			*/
			var region = '.agov-slideshow';
			var num_items = $(region + ' .views-row').length;
			var speed = 6000;
			var interval;
			
			if (num_items > 1) {
  			interval = setInterval(nextSlide, speed);
  			
  			/**
  			* Builds the pager
  			*/
  			var pager = '<span id="slideshow-controls">Slideshow controls</span><ul id="featured-article-pager">';
  			
  			
  			for (i = 1; i <= num_items; i++) {
  				pager += '<li' + ((i == 1) ? ' class="current"' : '') + '><a href="#views-row-' + i + '" title="View slide ' + i + '">' + i + '</a></li>';
  			}
  			
  			pager += '<li><a href="#pause" class="pause playing" data-paused="false" title="Pause slideshow">Pause</a></li></ul>';
  			
  			$(region).prepend(pager);
			}
			/**
			* Updates the active state on the pager
			*/
			function updatePager() {
				var itemIndex = $(region + ' .views-row:visible').index() + 1;

				$('#featured-article-pager li').removeClass('current');
				$('#featured-article-pager li a[href="#views-row-' + itemIndex + '"]').parent().addClass('current');	
			}

			/**
			* Used by 'interval'
			* Animates between featured articles
			*/
			function nextSlide() {
				var _currentSlide = $(region + ' .views-row:visible');
				var _nextSlide;
		
				// if the last row is visible, then switch back to the first row
				if ($(region + ' .views-row:last-child').is(':visible')) {
					_nextSlide = $(region + ' .views-row:first-child');
				}
				else {
					// otherwise just use the next .views-row in the stack
					_nextSlide 	= _currentSlide.next();
				}
				
				_currentSlide.fadeOut();
				_nextSlide.fadeIn(function() {
					updatePager();
				});
			}

			/**
			* Pauses/resumes the animation
			*/
			$('#featured-article-pager a.pause').click(function() {
				if ($(this).attr('data-paused') == 'false') {
					// is paused
					$(region + ' .views-row').stop(true, true);
					$(this).attr('data-paused', true);
					$(this).removeClass('playing').addClass('paused');
					$('#featured-article-pager li').removeClass('current');
					clearInterval(interval);
				}
				else {
					// is playing
					$(this).attr('data-paused', false);
					$(this).removeClass('paused').addClass('playing');
					interval = setInterval(nextSlide, speed);
					
					updatePager();
				}
			});
			
			/**
			* Switches to the selected item in the pager onclick
			*/
			$('#featured-article-pager li a').click(function(e) {
				e.preventDefault();
				
				if (!$(this).hasClass('pause')) {
					// make sure it's _not_ the pause button
					var row = $(this).attr('href').replace('#','');
					
					// pager active state
					$('#featured-article-pager li').removeClass('current');
					$(this).parent().addClass('current');
					
					clearInterval(interval);
					
					// make sure the pause button has an inactive state
					$('#featured-article-pager a.pause').attr('data-paused', false);
					$('#featured-article-pager a.pause').removeClass('paused').addClass('playing');
					
					// hide all
					$(region + ' .views-row').fadeOut();
					
					// show the selected row
					$(region + ' .' + row).fadeIn(function() {
						updatePager();
						interval = setInterval(nextSlide, speed);
					});
				}
			});
    }
  }
}(jQuery));