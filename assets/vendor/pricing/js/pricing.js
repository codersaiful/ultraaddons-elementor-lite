;(function ($, w) {
'use strict';

function advPricingTable(){
	var e = $(".filt-monthly"),
		d = $(".filt-hourly"),
		t = $(".switcher"),
		m = $(".monthly"),
		y = $(".hourly");
	
	if (e) {
		e.click(function () {
			t.checked = false;
			e.addClass("toggler--is-active");
			d.removeClass("toggler--is-active");
			m.removeClass("hide");
			y.addClass("hide");
		});
	}
	if (d) {
		d.click(function ()  {
			t.checked = true;
			d.addClass("toggler--is-active");
			e.removeClass("toggler--is-active");
			m.addClass("hide");
			y.removeClass("hide");
		});
	}
	if (t) {
		t.click(function ()  {
			d.toggleClass("toggler--is-active");
			e.toggleClass("toggler--is-active");
			m.toggleClass("hide");
			y.toggleClass("hide");
		})
	}
	}

	advPricingTable();
} (jQuery, window));
