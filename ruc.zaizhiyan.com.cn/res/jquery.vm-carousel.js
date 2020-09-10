

/* ========================================================================= *\
	vmcarousel plugin by Vedmant
\* ========================================================================= */

(function ($, window, document, undefined) {
	"use strict";

	var pluginName = 'vmcarousel';

	/**
	 * Defaults
	 *
	 * @type {}
	 */
	var defaults = {
		delay: 4000,
		speed: 500,
		autoplay: true,
		items_to_show: 0, // 0 for auto calc
		min_items_to_show: 2,
		items_to_slide: 1,
		dont_cut: true,
		centered: false,
		start_item: 0,
		start_item_centered: false,
		infinite: false,
		changed_slide: $.noop()
	};

	/**
	 * Plugin constructor
	 *
	 * @param element
	 * @param options
	 * @constructor
	 */
	function Plugin(element, options)
	{
		this._name = pluginName;
		this.element = element;
		this.$element = $(element);
		var data_options = parse_data_options(this.$element.data('options'));
		this.options = $.extend({}, defaults, options);
		this.options = $.extend({}, this.options, data_options);
		this.init();
	}

	/**
	 * Parse data-options attribute options
	 *
	 * @param data_options_raw
	 * @returns {Array}
	 */
	function parse_data_options(data_options_raw)
	{
		if(data_options_raw === undefined) return [];
		var options = [];
		data_options_raw.split(';').forEach(function(el){
			var pair = el.split(':');
			if(pair.length == 2) options[pair[0].trim()] = pair[1].trim();
		});
		return options;
	}


	/**
	 * Plugin functions
	 */
	Plugin.prototype = {

		/**
		 * Plugin init
		 */
		init: function ()
		{
			var that = this;

			// Add class
			this.$element.addClass('vmcarousel');

			// Wrap
			this.$viewport = this.$element.wrap('<div class="vmc-viewport"></div>').parent();
			this.$container = this.$viewport.wrap('<div class="vmc-container"></div>').parent();

			// Some initial procedures with slides
			this.init_slides();

			// Items vars
			this.$orig_items = this.$element.find('>li');
			this.$items = this.$orig_items;
			this.orig_items_count = this.$orig_items.length;
			this.items_count = this.$items.length;
			this.orig_item_width = this.$items.outerWidth(true);
			this.item_width = this.orig_item_width;

			// Other vars
			this.current_position = 0;

			// Init functions
			this.calc_variables();
			this.init_infinite(this.options.start_item);
			this.init_controls();
			this.update_state();

			// Reorder slides to make start item at the center
			if(this.options.start_item_centered)
				this.reorder_to_center(this.options.start_item);

			// Initial set slide
			if( ! this.options.infinite)
				this.set_slide(this.options.start_item);
			else
				this.set_active_infinite(this.options.start_item);

			// Start timer
			if (this.options.autoplay) this.start_timer();

			// Window resize event
			$(window).resize(function () { that.resize() });
		},

		/**
		 * Calculate all needed variables
		 */
		calc_variables: function()
		{
			this.viewport_width = this.$viewport.width();

			// Calc items to show
			this.items_to_show = this.options.items_to_show;
			if( ! this.options.items_to_show || (this.orig_item_width * this.items_to_show) > this.viewport_width) {
				this.items_to_show = Math.floor(this.viewport_width / this.orig_item_width);
			}

			// Set odd number for centered type for not to cut items
			if(this.options.centered && this.options.dont_cut) {
				this.items_to_show = this.items_to_show % 2 ? this.items_to_show : this.items_to_show - 1;
			}

			// Min items to show
			if(this.items_to_show < this.options.min_items_to_show) this.items_to_show = this.options.min_items_to_show;

			// Calc item width for centered or dont_cut
			if(this.options.centered || this.options.dont_cut) {
				this.item_width = Math.floor(this.viewport_width / this.items_to_show);
				if(this.item_width < this.orig_item_width) this.item_width = this.orig_item_width;
				this.$items.width(this.item_width);
				this.full_items_width = this.item_width * this.items_count;
				this.$element.css({width: this.full_items_width + 'px'});
			}

			// Calc items to slide
			this.items_to_slide = this.options.items_to_slide;
			if( ! this.options.items_to_slide)
				this.items_to_slide = Math.floor(this.viewport_width / this.item_width);
			if(this.items_to_slide > this.items_to_show) this.items_to_slide = this.items_to_show;

			if(this.items_to_slide <= 0) this.items_to_slide = 1;

			this.hide_controls = this.items_count <= this.items_to_show;

			this.infinite_initial_margin = - this.item_width;
			if(this.items_to_show % 2 == 0) this.infinite_initial_margin += this.item_width / 2;

		},

		/**
		 * Update carousel state (clases, so on)
		 */
		update_state: function()
		{
			this.$element.css({transition: 'transform ' + this.options.speed / 1000 + 's'});

			if(this.hide_controls) this.$container.addClass('hide-controls');
			else this.$container.removeClass('hide-controls');
		},

		/**
		 * Set slides properties
		 */
		init_slides: function()
		{
			this.$element.find('>li').each(function(i){
				$(this).attr('data-slide', i);
			});
		},

		/**
		 * Init controls
		 */
		init_controls: function()
		{
			var that = this;

			// Controls
			// this.$btn_left = this.$container.append('<a href="" class="vmc-arrow-left"></a>').find('.vmc-arrow-left');
			// this.$btn_right = this.$container.append('<a href="" class="vmc-arrow-right"></a>').find('.vmc-arrow-right');
			this.$btn_left = $('.vmc-arrow-left');
			this.$btn_right= $('.vmc-arrow-right');
			// Bind controls
			this.$btn_left.click(function (e) {
				e.preventDefault();
				that.slide_relative(-1);
			});
			this.$btn_right.click(function (e) {