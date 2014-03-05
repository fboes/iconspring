(function ($) {
	$.fn.main = function (options) {
		return this.each(function() {
			var main = {
				options : $.extend(
					{
					},
					options
				),
				elements : {
					parent : null
				},
				values : {

				},
				init : function (el) {
					this.elements.parent = el;
					// Your stuff here
					this.bindEvents();
					this.update();
				},
				bindEvents : function () {
					var that = this;
					// Your stuff here
					this.elements.parent.on('click', function (event) {
						event.preventDefault();
						event.stopPropagation();
						that.update(); // $(this)
					})
				},
				update : function () {
					// Your stuff here
					console.log('TheMachine wins');
				}
			}

			main.init($(this));
		});
	}
})(jQuery);