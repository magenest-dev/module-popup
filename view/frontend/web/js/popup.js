/**
 * Created by magenest on 12/01/2019.
 */
define([
    'jquery',
    'Magento_Customer/js/customer-data',
    'Magento_Ui/js/modal/modal',
    'mage/url',
    'Magento_Checkout/js/model/full-screen-loader',
    'mage/storage',
    'jquery/ui',
    'bioEp'
],function ($,customerData,modal,urlBuilder,fullScreenLoader,storage) {
    'use strict';
    $.widget('magenest.popup', {
        options: {
            dataPopup: {}
        },
        _create: function () {
            var self = this;
            this._showPopup();
        },
        _showPopup: function () {
            var self = this,
                popupElem = $('#magenest-popup').length,
                html_content = this.options.dataPopup.html_content,
                popup_id = this.options.dataPopup.popup_id,
                cookie_lifetime = this.options.dataPopup.cookie_lifetime,
                cookies = self._getCookie('magenest_cookie_popup'),
                values = {},
                view_page = 1;

            if(cookies != null && cookies != ""){
                var cookieArr = JSON.parse(cookies);
                values = cookieArr;
                if(typeof cookieArr.view_page !== 'undefined' || cookieArr.view_page != null){
                    view_page += cookieArr.view_page;
                    cookieArr.view_page = view_page;
                    values = cookieArr;
                }else{
                    values['view_page'] = view_page;
                }
            }else{
                values['view_page'] = view_page;
            }
            self._eraseCookie('magenest_cookie_popup');
            self._createCookie('magenest_cookie_popup',JSON.stringify(values),cookie_lifetime);
            if (popupElem <= 1) {
                var popup_trigger = this.options.dataPopup.popup_trigger,
                    number_x = this.options.dataPopup.number_x*1;
                if(typeof this.options.dataPopup.preview != 'undefined' && this.options.dataPopup.preview){
                    bioEp.init({
                        html: html_content,
                        css: this.options.dataPopup.css_style,
                        popup_id: popup_id,
                        showOnDelay: true,
                        delay: 0
                    });
                }else if(popup_trigger == 1){
                    var id = 'magenest-popup popup-bio_ep';
                    $('#magenest-popup').attr('id', id);
                    bioEp.init({
                        html: html_content,
                        showOnDelay: true,
                        delay: number_x,
                        css: this.options.dataPopup.css_style,
                        popup_id: popup_id,
                        cookie_lifetime: cookie_lifetime
                    });
                }else if(popup_trigger == 2){
                    var id = 'magenest-popup popup-bio_ep';
                    $('#magenest-popup').attr('id', id);
                    self._scrollToShow();
                }else if(popup_trigger == 3){
                    var id = 'magenest-popup popup-bio_ep';
                    $('#magenest-popup').attr('id', id);
                    if(view_page == number_x){
                        bioEp.init({
                            html: html_content,
                            css: this.options.dataPopup.css_style,
                            popup_id: popup_id,
                            cookie_lifetime: cookie_lifetime,
                            showOnDelay: true,
                            delay: 0
                        });
                    }
                }else if(popup_trigger == 4){
                    var id = 'magenest-popup popup-bio_ep';
                    $('#magenest-popup').attr('id', id);
                    bioEp.init({
                        html: html_content,
                        css: this.options.dataPopup.css_style,
                        popup_id: popup_id,
                        cookie_lifetime: cookie_lifetime
                    });
                    $(document).off('mouseleave');
                }
            }
            self._clickSuccess();
            self._addClassDefault();
            self._addAnimation();
            self._closeVideo();
        },
        // Create a cookie
        _createCookie: function(name, value, days, sessionOnly) {
            var expires = "";
            if(sessionOnly) {
                expires = "; expires=0"
            }else if(days) {
                var date = new Date();
                date.setTime(date.getTime() + (days * 1000));
                expires = "; expires=" + date.toGMTString();
            }
            document.cookie = name + "=" + value + expires + "; path=/";
        },
        // Get the value of a cookie
        _getCookie: function(name) {
            var nameEQ = name + "=";
            var ca = document.cookie.split(";");
            for(var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == " ") c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
            }
            return null;
        },
        // Delete a cookie
        _eraseCookie: function(name) {
            this._createCookie(name, "", -1);
        },
        /**
         * Scroll to show popup
         * @private
         */
        _scrollToShow: function () {
            var self = this;
            $(window).scroll(function () {
                var scrollTop = $(window).scrollTop(),
                    docHeight = $(document).height(),
                    winHeight = $(window).height(),
                    scrollPercent = (scrollTop) / (docHeight - winHeight),
                    optionScroll = (self.options.dataPopup.number_x*1) / 100,
                    popup_id = self.options.dataPopup.popup_id,
                    html_content = self.options.dataPopup.html_content,
                    cookie_lifetime = self.options.dataPopup.cookie_lifetime;
                if(scrollPercent > optionScroll){
                    bioEp.init({
                        html: html_content,
                        showOnDelay: true,
                        delay: 0,
                        css: self.options.dataPopup.css_style,
                        popup_id: popup_id,
                        cookie_lifetime: cookie_lifetime
                    });
                    $(window).off('scroll');
                    self._addClassDefault();
                    self._addAnimation();
                    self._clickSuccess();
                    self._closeVideo();
                }
            });



        },
        /**
         * Event click submit button
         * @private
         */
        _clickSuccess: function () {
            var self = this,
                coupon = self.options.dataPopup.coupon_code,
                thankyou = self.options.dataPopup.thankyou_message,
                form = $('#form-newsletter'),
                popup_type = self.options.dataPopup.popup_type,
                popup_id = self.options.dataPopup.popup_id;
            $('#popup-submit-button').click(function () {
                var newsletter = $('#popup-newsletter').val();
                if(newsletter == ''){

                }else {
                    $('#popup-submit-button strong').hide();
                    $('#popup-submit-button i').hide();
                    $('#popup-submit-button').append('<i class="fa fa-spinner fa-spin" aria-hidden="true"></i>');
                    var url = urlBuilder.build('magenest_popup/popup/subscriber');
                    var data = new Array(),
                        payload,
                        popupObj = new Object();
                    popupObj.name = popup_id;
                    popupObj.value = popup_type;
                    data.push(popupObj);
                    $('.popup-newsletter').each(function(i) {
                        var obj = new Object();
                        obj.name = $(this).attr('name');
                        obj.value = $(this).val();
                        data.push(obj);
                    });
                    payload = {
                        dataPopup: data
                    };
                    storage.post(
                        url, JSON.stringify(payload)
                    ).done(
                        function (response) {
                            $('.popup-step-1').hide();
                            $('.popup-step-1').after('<div class="popup-step-2"></div>');
                            $('.popup-step-2').prepend('<div class="popup-content"></div>');
                            var obj_response = JSON.parse(response);
                            if(obj_response.status == 0){
                                $('.popup-step-2 .popup-content').prepend(function () {
                                    return '<div class="popup-message"><span>' + obj_response.message + '</span></span></div>';
                                });
                            }else{
                                if(coupon){
                                    $('.popup-step-2 .popup-content').prepend(function () {
                                        return '<div class="popup-coupon"><span> Your coupon code is <span class="coupon"> ' + coupon + '</span></span></div>';
                                    });
                                }
                                if(thankyou){
                                    $('.popup-step-2 .popup-content').append(function () {
                                        return '<div class="popup-thankyou"><span>' + thankyou + '</span></div>';
                                    });
                                }else{
                                    $('.popup-step-2 .popup-content').append(function () {
                                        return '<div class="popup-thankyou"><span>Thank you</span></div>';
                                    });
                                }
                            }
                        }
                    ).fail(
                        function (response) {
                            console.log(response);
                        }
                    );
                }
            })
        },

        /**
         * Event add class template default
         * @private
         */
        _addClassDefault: function () {
            var self = this,
                popup_template_id = self.options.dataPopup.popup_template_id,
                template_id = self.options.dataPopup.template_id,
                class_default =  self.options.dataPopup.class;
            $('.cursor-pointer').click(function () {
                $('.popup-box-1').hide();
                $('.popup-box-2').show();
            });

            if(popup_template_id || typeof template_id != 'undefined'){
                $('#bio_ep').addClass(class_default);
                if(class_default == 'popup-default-3'){
                    $('#bio_ep_bg').addClass('popup-bg-3');
                }else {

                }
            }else{
                $('#bio_ep').addClass('popup-default-1');
            }
        },

        /**
         * Event add animation for popup
         * @private
         */
        _addAnimation: function () {
            var self = this,
                animation = self.options.dataPopup.popup_animation;

            if(animation == 1) {
                $('#bio_ep').addClass('zoomIn');
            }else if(animation == 2){
                $('#bio_ep').addClass('zoomInBig');
            }else if(animation == 3){
                $('#bio_ep').addClass('bounceInLeft');
            }else if(animation == 4){
                $('#bio_ep').addClass('bounceInRight');
            }else if(animation == 5){
                $('#bio_ep').addClass('bounceInDown');
            }else if(animation == 6){
                $('#bio_ep').addClass('bounceInUp');
            }
        },

        /**
         * Turn off the video if the popup's has it
         * @private
         */
        _closeVideo: function () {
            $('#bio_ep_close').click(function(e) {
                e.preventDefault();
                $('.popup-video').children('iframe').attr('src', '');
            });
        }

    });
    return $.magenest.popup;
});