/*
Name: 			Landing Dashboard - Examples
Written by: 	Okler Themes - (http://www.okler.net)
Theme Version: 	1.6.0
*/
(function($) {

	'use strict';

	/*
	* Isotope
	*/
	var $wrapper = $('.sample-item-list'),
		$window  = $(window);

	$wrapper.isotope({
		itemSelector: ".isotope-item",
		layoutMode: 'fitRows'
	});

	// Recalculate Isotope items size on Sidebar left Toggle
	$window.on('sidebar-left-toggle', function(){
		$wrapper.isotope('layout');
	});

}).apply(this, [jQuery]);