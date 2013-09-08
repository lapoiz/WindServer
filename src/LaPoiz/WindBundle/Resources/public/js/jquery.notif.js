
jQuery(document).ready(function ($) {
	$.fn.notif = function(options) {
		
		var settings = {
			html: '<div class="notification animated fadeInLeft {{cls}}">\
					<div class="left">\
						<div class="icon">\
							{{{icon}}}\
						</div>\
					</div>\
					<div class="right">\
						<h2>{{title}}</h2>\
						<p>{{content}}</p>\
					</div>\
				</div>',
				icon: '&#8505;',
				timeout: false
		}
		if (options.cls=='error') {
			settings.icon='&#10060;';
		}
		if (options.cls=='success') {
			settings.icon='&#10003;';
		}
		var options = $.extend(settings,options);
		
		return this.each(function(){
			var $this = $(this);
			var $notifs = $('> .notifications', this);
			var $notif = $(Mustache.render(options.html,options));
			
			if ($notifs.length==0) {
				$notifs = $('<div class="notifications animated flipInX" />');
				$this.prepend($notifs);
			}
			$notifs.append($notif);
			if (options.timeout) {
				setTimeout(function() {
					$notif.trigger('click');
				},options.timeout);
			}
			$notif.click(function(event) {
				event.preventDefault();
				$notif.addClass('fadeOutLeft').delay(500).slideUp(300, function(){
					if ($notifs.length==0) {
						$notifs.remove();
					}
					$notif.remove();					
				});
			});
		});
	};

	

});