/*********** Function viewport ***********/
function viewport() {
	var e = window,
		a = 'inner';
	if (!('innerWidth' in window)) {
		a = 'client';
		e = document.documentElement || document.body;
	}
	return {
		width: e[a + 'Width'],
		height: e[a + 'Height']
	};
}

/*********** Function preloader ***********/

function masked(element, status) {
	if (status == true) {
		$('body').append('<div class="fm-preloader-overlay d-flex justify-content-center align-items-center"><div class="fm-preloader spinner-grow" style="width: 4rem; height: 4rem;"><span class="sr-only">Loading...</span></div></div>');
	} else {
		setTimeout(function(){
		  $('.fm-preloader-overlay').remove();
		}, 600);
	}
}

/*********** Function Show Location Map ***********/

function octShowMap(content) {

	var octMap = $('#oct-contact-map');

	if (octMap.hasClass('not_in')) {
		octMap.html(content);
		octMap.removeClass('not_in');
	}

}

/*********** Function popups ***********/

function octPopupCallPhone() {
	masked('body', true);
	$(".modal-backdrop").remove();
	$.ajax({
		type: 'post',
		dataType: 'html',
		url: 'index.php?route=octemplates/module/oct_popup_call_phone',
		cache: false,
		success: function (data) {
			masked('body', false);
			$(".modal-holder").html(data);
			$("#fm-callback-modal").modal("show");
		}
	});
}

function octPopupSubscribe() {
	if ($(".modal-backdrop").length > 0) {
		return;
	}
	masked('body', true);
	$(".modal-backdrop").remove();
	$.ajax({
		type: 'post',
		dataType: 'html',
		url: 'index.php?route=octemplates/module/oct_subscribe',
		cache: false,
		success: function (data) {
			masked('body', false);
			$(".modal-holder").html(data);
			$("#fm-subscribe-modal").modal("show");
		}
	});
}

function octPopupFoundCheaper(product_id) {
	masked('body', true);
	$(".modal-backdrop").remove();
	$.ajax({
		type: 'post',
		dataType: 'html',
		url: 'index.php?route=octemplates/module/oct_popup_found_cheaper',
		data: 'product_id=' + product_id,
		cache: false,
		success: function (data) {
			masked('body', false);
			$(".modal-holder").html(data);
			$("#fm-cheaper-modal").modal("show");
		}
	});
}

function octPopupLogin() {
	masked('body', true);
	$(".modal-backdrop").remove();
	$.ajax({
		type: "post",
		url: 'index.php?route=octemplates/module/oct_popup_login',
		data: $(this).serialize(),
		cache: false,
		success: function (data) {
			masked('body', false);
			$(".modal-holder").html(data);
			$("#loginModal").modal("show");
		}
	});
}

function octPopUpView(product_id) {
	masked('body', true);
	$(".modal-backdrop").remove();
	$.ajax({
		type: 'post',
		dataType: 'html',
		url: 'index.php?route=octemplates/module/oct_popup_view',
		data: 'product_id=' + product_id,
		cache: false,
		success: function (data) {
			masked('body', false);
			$(".modal-holder").html(data);
			$("#fm-quickview-modal").modal("show");
		}
	});
}

function octPopPurchase(product_id) {
	masked('body', true);
	$(".modal-backdrop").remove();
	$.ajax({
		type: 'post',
		dataType: 'html',
		url: 'index.php?route=octemplates/module/oct_popup_purchase',
		data: 'product_id=' + product_id,
		cache: false,
		success: function (data) {
			masked('body', false);
			$(".modal-holder").html(data);
			$("#fm-one-click-modal").modal("show");
		}
	});
}

function octPopupCart() {
	masked('body', true);
	$(".modal-backdrop").remove();
	$.ajax({
        type: 'get',
        dataType: 'html',
        url: 'index.php?route=octemplates/module/oct_popup_cart&isPopup=1',
        cache: false,
        success: function(data) {
	        masked('body', false);
            $(".modal-holder").html(data);
			$("#fm-popup-cart").modal("show");
        }
    });
}

/*********** Button column ***********/

function octShowColumnProducts(octButtonPrevID, octButtonNextID, octModuleID) {
	const buttonPrevID = octButtonPrevID;
	const buttonNextID = octButtonNextID;
	const moduleID = octModuleID + ' > .fm-item';
	$("#" + moduleID).slice(0, 1).show();
	$("#" + octButtonNextID).click(function () {
		const visibleProduct = $("#" + moduleID + ":visible");
		const NextProduct = visibleProduct.next();
		if (NextProduct.length > 0) {
			visibleProduct.css('display', 'none');
			NextProduct.fadeIn("slow");
		} else {
			visibleProduct.css('display', 'none');
			$("#" + moduleID + ":hidden:first").fadeIn('slow');
		}
	});
	$("#" + buttonPrevID).click(function () {
		const visibleProduct = $("#" + moduleID + ":visible");
		const NextProduct = visibleProduct.prev();
		if (NextProduct.length > 0) {
			visibleProduct.css('display', 'none');
			NextProduct.fadeIn("slow");
		} else {
			visibleProduct.css('display', 'none');
			$("#" + moduleID + ":hidden:last").fadeIn('slow');
		}
	});
}

function getOCTCookie(name) {
	var matches = document.cookie.match(new RegExp("(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"));

	return matches ? decodeURIComponent(matches[1]) : 'undefined';
}

/*********** Animate scroll to element function ***********/

function scrollToElement(hrefTo) {

	const currentWidth = viewport().width;
	var topPosition = $(hrefTo).offset().top - 50;

	if (currentWidth < 769) {
		topPosition = $(hrefTo).offset().top;
	}

	$('body,html').animate({
		scrollTop: topPosition
	}, 1000);
}

/*********** Notify function ***********/

function fmNotify(type, text) {
	var iconType = 'info';
	switch (type) {
		case 'success':
			iconType = 'fas fa-check';
			break;
		case 'danger':
			iconType = 'fas fa-times';
			break;
		case 'warning':
			iconType = 'fas fa-exclamation';
			break;
	}
	$.notify({
		message: text,
		icon: iconType
	}, {
		type: type
	});
}

/*********** Mask function ***********/
function fmInputMask(selector, mask) {
	$(selector).inputmask({
		'mask': mask
	});
}

/*********** Sidebar & menus ***********/
function fmSidebar(title, type) {
	if (!title && !type) return;

	var dataQuery, queryUrl, isMenu = 0;

	$('.fm_sidebar-title-text').html(title);

	switch (type) {
		// show viewed products
		case 'viewed':
			// ajax query url
			queryUrl = "index.php?route=octemplates/module/oct_megamenu/mobileProductViews";
			break;

			// show main menu
		case 'menu':
			queryUrl = "index.php?route=octemplates/module/oct_megamenu/mobileMenu";
			isMenu = 1;
			break;

			// show cart
		case 'cart':
			queryUrl = "index.php?route=octemplates/module/oct_popup_cart";
			break;

			// show account
		case 'login':
			queryUrl = "index.php?route=octemplates/module/oct_popup_login";
			dataQuery = 'mobile=1';
			break;

		case 'account':
			queryUrl = "index.php?route=octemplates/module/oct_popup_login/account";
			break;
	}

	// main ajax query
	masked('#fm_sidebar', true);
	$.ajax({
		type: "post",
		url: queryUrl,
		data: dataQuery,
		cache: false,
		success: function (data) {
			$('#fm_sidebar_content').html(data);
			fmSidebarInit();
			masked('#fm_sidebar', false);
			$('#fm_sidebar').addClass('active');
			$('#fm_sidebar_overlay').addClass('visible');
			$('#fm_mobile_nav').addClass('hidden');
			$('body').addClass('no-scroll');

			if (isMenu === 1) {
				$("#language").prependTo("#oct_mobile_language");
				$("#currency").prependTo("#oct_mobile_currency");
			}
		}
	});
}


function fmSidebarInit() {
	var width = document.documentElement.clientWidth;

	if (width < 992) {
		/*********** Mobile scripts ***********/
		$('#product_mobile_top').append($('#fm_product_right'));

		// Mobile menu
		// First level
		$('#fm_mobile_menu_toggle_button').on('click', function () {
			$(this).parent().css('transform', 'translateX(-100%)').next().css('transform', 'translateX(0)');
			$('.fm_sidebar-content').addClass('noscroll')
		});

		$('.fm_mobile_menu_first_back').on('click', function () {
			$(this).parent().css('transform', 'translateX(100%)').prev().css('transform', 'translateX(0)');
			$('.fm_sidebar-content').removeClass('noscroll');
			$(this).next().scrollTop(0);
		});

		// Second level
		$('.fm_mobile_menu_second_button').on('click', function () {
			$(this).parent().parent().parent().css('transform', 'translateX(-100%)').prev().css('transform', 'translateX(-200%)');
			$(this).next().css('visibility', 'visible');
		});

		$('.fm_mobile_menu_second_back').on('click', function () {
			$(this).parent().css('visibility', 'hidden').parent().parent().parent().css('transform', 'translateX(0)').prev().css('transform', 'translateX(-100%)');
			$(this).next().scrollTop(0);
		});

		//Third level
		$('.fm_mobile_menu_third_button').on('click', function () {
			$(this).parent().parent().parent().css('transform', 'translateX(-100%)');
			$(this).next().css('transform', 'translateX(-100%)').css('visibility', 'visible');
		});

		$('.fm_mobile_menu_third_back').on('click', function () {
			$(this).parent().css('visibility', 'hidden').parent().parent().parent().css('transform', 'translateX(0)');
			$(this).next().scrollTop(0);
		});

		$('.third-level-landing-button').on('click', function () {
			$(this).toggleClass('active').next().toggleClass('expanded');
		});

		/*********** End of Mobile scripts ***********/
	} else {
		$('#fm_product_right_inner').append($('#fm_product_right'));
	}
}

window.addEventListener("orientationchange", function() {
    fmSidebarInit();
}, false);

$(function () {
	/*********** Category qauntity ***********/
	$('.fm-plus').on('click', function(){
        const oldVal = $(this).prev().val();
        var newVal = (parseInt($(this).prev().val(),10) +1);
		$(this).prev().val(newVal);
    });

    $('.fm-minus').on('click', function(){
        const oldVal = $(this).next().val();
        const minimum = $(this).parent().find('.min-qty').val();
        if (oldVal > 1) {
            var newVal = (parseInt($(this).next().val(),10) -1);
        } else {
	        newVal = 1;
        }
        if (newVal < minimum) {
	        newVal = minimum;
        }
        $(this).next().val(newVal);
    });

    $('body').on('click', '.fm-cat-button-cart', function(){
        const productID = $(this).prev().find('input[name="product_id"]').val();
        const quantity = $(this).prev().find('.form-control').val();
        cart.add(productID, quantity);
    });

	/*********** Category column module ***********/
	$('.fm-categories-toggle').on('click', function () {
		if ($(this).hasClass('clicked') || $(this).parent().parent().hasClass('active')) {
			$(this).parent().parent().removeClass('active');
			$(this).parent().next().removeClass('expanded');
			$(this).removeClass('clicked');
		} else {
			$(this).toggleClass('clicked').parent().next().toggleClass('expanded');
		}
	});
	/*********** End of Category column module ***********/

	/*********** Header dropdowns ***********/
	$('.header-dropdown-box').on('click', function(){
		$(this).addClass('active');
		$('#fm_sidebar_overlay').addClass('visible');
	});
	/*********** End of Header dropdowns ***********/

	/*********** Footer scripts ***********/
	$('.fm-main-footer-title-toggle').on('click', function () {
		$(this).toggleClass('clicked').next().toggleClass('expanded');
		scrollToElement(this);
	});
	/*********** End of Footer scripts ***********/

	/*********** Fixed contacts ***********/

	$('#fm_fixed_contact_button').on('click', function () {
		$(this).toggleClass('clicked');
		$('.fm-fixed-contact-dropdown').toggleClass('expanded');
		$('.fm-fixed-contact-icon .fa-comment-dots').toggleClass('d-none');
		$('.fm-fixed-contact-icon .fa-times').toggleClass('d-none');
		$('#fm_fixed_contact_substrate').toggleClass('active');
	});

	$('#fm_fixed_contact_substrate').on('click', function () {
		$(this).removeClass('active');
		$('.fm-fixed-contact-dropdown').removeClass('expanded');
		$('.fm-fixed-contact-icon .fa-comment-dots').removeClass('d-none');
		$('.fm-fixed-contact-icon .fa-times').toggleClass('d-none');
		$('#fm_fixed_contact_button').removeClass('clicked');
	});

	$('.fm-fixed-contact-dropdown').click(function (e) {
		e.stopPropagation();
	});
	/*********** End of Fixed contacts ***********/

	/*********** To top button ***********/
	$("#back-top").hide(),
		$(function () {
			$(window).scroll(function () {
					$(this).scrollTop() > 450 ? $("#back-top").fadeIn() : $("#back-top").fadeOut()
				}),
				$("#back-top a").click(function () {
					return $("body,html").animate({
						scrollTop: 0
					}, 800), !1
				})
		});

	/*********** End of To top button ***********/
	$('.fm_sidebar-title-close').on('click', function () {
		$('#fm_sidebar').removeClass('active');
		$('#fm_sidebar_overlay').removeClass('visible');
		$('#fm_mobile_nav').removeClass('hidden');
		$('body').removeClass('no-scroll');
		$('.fm_sidebar-content').removeClass('noscroll');
		$("#language, #currency").prependTo("#top-links");
		setTimeout((function () {
			$('.fm_sidebar-content').scrollTop(0);
		}), 500);
	});

	$('#fm_sidebar_overlay').on('click', function () {
		clearLiveSearch();
		$('.header-dropdown-box').removeClass('active');
		if ($("#fm_sidebar").hasClass('active')) {
			$('#fm_sidebar').removeClass('active');
			$('#fm_sidebar_overlay').removeClass('visible');
			$('#fm_mobile_nav').removeClass('hidden');
			$('body').removeClass('no-scroll');
			$('.fm_sidebar-content').removeClass('noscroll');
			$("#language, #currency").prependTo("#top-links");
			setTimeout((function () {
				$('.fm_sidebar-content').scrollTop(0);
			}), 500);
		}
	});
	/*********** End of Sidebar ***********/

	var width = document.documentElement.clientWidth;

	if (width > 992) {
		window.addEventListener("resize", fmSidebarInit);
	}

	fmSidebarInit();

	/* Ocfilter overlay */
	$('#fm_overlay').on('click', function() {
		$(this).removeClass('active');
		$('.ocf-offcanvas').removeClass('active');
		$('body').removeClass('modal-open');
	});
});
