!function(a){a(window).load(function(){var b=new Array,c=a(".same-height");c&&c.each(function(c,d){var e=a(this);if(e.data("target")){var f=e.data("target"),g=e.data("inside");a(f).each(function(c,d){b.push(a(this).height())}),a(g).css("min-height",Math.max.apply(Math,b)+"px")}})}),a(".owl-carousel").owlCarousel()}(jQuery);
//# sourceMappingURL=main.js.map