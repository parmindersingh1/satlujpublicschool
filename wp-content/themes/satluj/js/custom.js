jQuery(document).ready(function(){	

	jQuery(".flexslider").flexslider({
		animation: "slide",
		controlNav: false
	});
	
	jQuery('#gallery img').adipoli({
		'startEffect' : 'transparent', 'hoverEffect' : 'boxRainGrowReverse',
	});
	
	jQuery('.fancybox').fancybox();


});