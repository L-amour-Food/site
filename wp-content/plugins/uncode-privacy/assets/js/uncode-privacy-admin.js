(function( $ ) {
	'use strict';

	$(function() {
		$(document).on('click', '.uncode-privacy-settings-form .notice-dismiss', function() {
			$(this).parent().parent().remove();
		});

		function string_to_slug (str) {
			str = str.replace(/^\s+|\s+$/g, ''); // trim
			str = str.toLowerCase();

			// remove accents, swap ñ for n, etc
			var from = "àáäâèéëêìíïîòóöôùúüûñç·/_,:;";
			var to   = "aaaaeeeeiiiioooouuuunc------";
			for (var i=0, l=from.length ; i<l ; i++) {
			str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
	    }

	    str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
			.replace(/\s+/g, '-') // collapse whitespace and replace by -
			.replace(/-+/g, '-'); // collapse dashes

			return str;
		}

		$('.add-consent').click(function(e) {
			e.preventDefault();
			var field = $('#type-of-consent');
			if ( field.val() === '' ) {
				return;
			}
			var consentID = string_to_slug( field.val() );
			var consentName = field.val();
			var template = wp.template( 'consents' );
			$('#consent-tabs').append( template( {
				key: consentID,
				name: consentName,
				option_name: 'uncode_privacy_consent_types'
			} ) );
			field.val('');
		});

		$('#consent-tabs, #tabs').sortable();

		$(document).on('click', '#tabs .notice-dismiss', function(e) {
			e.preventDefault();
			$(this).closest('.postbox').remove();
		});

		if (uncode_privacy_admin_params.pagenow.indexOf('uncode-privacy-logs') === -1) {
			$(document).on('click', '.uncode-privacy-wrap .nav-tab-wrapper a', function(e) {
				if ($(this).hasClass('nav-tab--logs')) {
					return true;
				}
				var target = $(this).attr('href');
				target = target.replace('#', '');
				$(this).addClass('nav-tab-active');
				$(this).siblings().removeClass('nav-tab-active');
				$('.uncode-privacy-wrap .uncode-privacy-tab').addClass('hidden');
				$('.uncode-privacy-wrap .uncode-privacy-tab[data-id='+ target +']').removeClass('hidden');

				if ( -1 !== location.search.indexOf( 'page=uncode-privacy-settings' ) ) {
					var referer = $('.uncode-privacy-wrap form input[name="_wp_http_referer"]');
					var cleanReferer = referer.val().split('#')[0];
					referer.val( cleanReferer + '#' + target );
				}
			});

			var hash = window.location.hash;

			if ( hash ) {
				$('.uncode-privacy-wrap .nav-tab-wrapper a[href="'+ hash +'"]').addClass('nav-tab-active');
				$('.uncode-privacy-wrap .uncode-privacy-tab[data-id="'+ hash.replace('#', '') +'"]').removeClass('hidden');
				if ( -1 !== location.search.indexOf( 'page=uncode-privacy-settings' ) ) {
					var referer = $('.uncode-privacy-wrap form input[name="_wp_http_referer"]');
					var cleanReferer = referer.val().split('#')[0];
					referer.val( cleanReferer + hash);
				}
			} else {
				$('.uncode-privacy-wrap .nav-tab-wrapper a:eq(0)').addClass('nav-tab-active');
				$('.uncode-privacy-wrap .uncode-privacy-tab:eq(0)').removeClass('hidden');
			}
		}

		$('.uncode-privacy-switch-checkbox').each(function() {
			var checkbox = $(this);
			var stateControl = checkbox.closest('tr').next();

			if (checkbox.prop('checked')) {
				stateControl.hide();
			} else {
				stateControl.show();
			}
		});

		$(document).on('click', '.uncode-privacy-switch-checkbox', '.uncode-privacy-settings-form', function() {
			var checkbox = $(this);
			var stateControl = checkbox.closest('tr').next();

			if (checkbox.prop('checked')) {
				stateControl.hide();
			} else {
				stateControl.show();
			}
		});

		$('#uncode_privacy_record_logs').on('change', function() {
			var _this = $(this);
			var selected_value = _this.val();

			if (selected_value === 'yes') {
				$('.record_logs_for_registered_users-row').show();
			} else {
				$('.record_logs_for_registered_users-row').hide();
			}
		});

		$('#uncode_privacy_record_logs').trigger('change');

		$('#search_type_for_privacy_request').on('change', function() {
			var _this = $(this);
			var selected_value = _this.val();

			$('.log-search-type').hide();
			$('.log-search-type[data-row="' + selected_value +'"]').show();
		});

		$('#search_type_for_privacy_request').trigger('change');

		if (uncode_privacy_admin_params.has_datepicker === '1') {
			$(document).ready(function() {
				$('#log_start_date_for_privacy_request').datepicker({
					dateFormat : 'yy-mm-dd'
				});

				$('#log_end_date_for_privacy_request').datepicker({
					dateFormat : 'yy-mm-dd'
				});
			});
		}
	});
})( jQuery );
