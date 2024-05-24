(function($) {
  'use strict';

  /**
   * [isMobile description]
   * @type {Object}
   */
  window.isMobile = {
    Android: function Android() {
      return navigator.userAgent.match(/Android/i);
    },
    BlackBerry: function BlackBerry() {
      return navigator.userAgent.match(/BlackBerry/i);
    },
    iOS: function iOS() {
      return navigator.userAgent.match(/iPhone|iPad|iPod/i);
    },
    Opera: function Opera() {
      return navigator.userAgent.match(/Opera Mini/i);
    },
    Windows: function Windows() {
      return navigator.userAgent.match(/IEMobile/i);
    },
    any: function any() {
      return isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() ||
        isMobile.Opera() || isMobile.Windows();
    },
  };
  window.isIE = /(MSIE|Trident\/|Edge\/)/i.test(navigator.userAgent);
  window.windowHeight = window.innerHeight;
  window.windowWidth = window.innerWidth;

  $.fn.numberTextLine = function(opts) {
    $(this).each(function() {
      var el = $(this),
        defaults = {
          numberLine: 0,
        },
        data = el.data(),
        dataTemp = $.extend(defaults, opts),
        options = $.extend(dataTemp, data);

      if (!options.numberLine) return false;

      el.bind('customResize', function(event) {
        event.stopPropagation();
        reInit();
      }).trigger('customResize');
      $(window).resize(function() {
        el.trigger('customResize');
      });

      function reInit() {
        var fontSize = parseInt(el.css('font-size')),
          lineHeight = parseInt(el.css('line-height')),
          overflow = fontSize * (lineHeight / fontSize) * options.numberLine;

        el.css({
          'display': 'block',
          'max-height': overflow,
          'overflow': 'hidden',
        });
      }
    });
  };
  $('[data-number-line]').numberTextLine();

  /**
   * [debounce description]
   * @param  {[type]} func      [description]
   * @param  {[type]} wait      [description]
   * @param  {[type]} immediate [description]
   * @return {[type]}           [description]
   */
  function debounce(func, wait, immediate) {
    var timeout;
    return function() {
      var context = this,
        args = arguments;
      var later = function later() {
        timeout = null;
        if (!immediate) func.apply(context, args);
      };
      var callNow = immediate && !timeout;
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
      if (callNow) func.apply(context, args);
    };
  }

  /**
   * google_map
   */
  function google_map() {
    var initialize = function initialize() {
      var map;
      var brooklyn = new google.maps.LatLng(21.027764, 105.834160);
      var MY_MAPTYPE_ID = 'custom_style';

      function contentString(text) {
        var infoHtml = '\n\t\t\t\t<div class="map-location">\n\t\t\t\t\t<p>' +
          text + '</p>\n\t\t\t\t</div>\n\t\t\t';
        return infoHtml;
      }

      var mapOptions = {
        zoom: 18,
        zoomControl: false,
        scrollwheel: false,
        center: brooklyn,
        fullscreenControl: false,
        streetViewControl: false,
        mapTypeControl: false,
        styles: [
          {
            'featureType': 'all',
            'elementType': 'geometry.fill',
            'stylers': [
              {
                'saturation': '-9',
              }],
          }, {
            'featureType': 'landscape',
            'elementType': 'geometry.fill',
            'stylers': [
              {
                'color': '#dfdfdf',
              }],
          }, {
            'featureType': 'poi',
            'elementType': 'geometry.fill',
            'stylers': [
              {
                'color': '#dfdfdf',
              }],
          }, {
            'featureType': 'poi',
            'elementType': 'labels.text.fill',
            'stylers': [
              {
                'color': '#997f86',
              }],
          }, {
            'featureType': 'poi.business',
            'elementType': 'geometry.fill',
            'stylers': [
              {
                'color': '#e8c4cd',
              }],
          }, {
            'featureType': 'poi.medical',
            'elementType': 'geometry.fill',
            'stylers': [
              {
                'color': '#e8c4cd',
              }],
          }, {
            'featureType': 'poi.park',
            'elementType': 'geometry.fill',
            'stylers': [
              {
                'color': '#e8c4cd',
              }],
          }, {
            'featureType': 'poi.school',
            'elementType': 'geometry.fill',
            'stylers': [
              {
                'color': '#e8c4cd',
              }],
          }, {
            'featureType': 'poi.sports_complex',
            'elementType': 'geometry.fill',
            'stylers': [
              {
                'color': '#e8c4cd',
              }],
          }, {
            'featureType': 'road',
            'elementType': 'geometry',
            'stylers': [
              {
                'lightness': 100,
              }, {
                'visibility': 'simplified',
              }],
          }, {
            'featureType': 'road',
            'elementType': 'labels',
            'stylers': [
              {
                'visibility': 'off',
              }],
          }, {
            'featureType': 'transit',
            'elementType': 'labels.text.fill',
            'stylers': [
              {
                'color': '#723434',
              }],
          }, {
            'featureType': 'transit',
            'elementType': 'labels.icon',
            'stylers': [
              {
                'hue': '#ff0080',
              }, {
                'saturation': '100',
              }, {
                'lightness': '0',
              }],
          }, {
            'featureType': 'transit.line',
            'elementType': 'geometry.fill',
            'stylers': [
              {
                'color': '#d4d4d4',
              }],
          }, {
            'featureType': 'transit.line',
            'elementType': 'geometry.stroke',
            'stylers': [
              {
                'visibility': 'off',
              }],
          }, {
            'featureType': 'water',
            'elementType': 'geometry.fill',
            'stylers': [
              {
                'color': '#e8c4cd',
              }],
          }, {
            'featureType': 'water',
            'elementType': 'labels.text.fill',
            'stylers': [
              {
                'color': '#ffffff',
              }],
          }],
      };

      var module = $('.map'),
        itemMarker = $('.map__marker', module),
        markers = [];

      itemMarker.each(function() {
        var self = $(this);
        var defaut = {
          lat: 21.027764,
          lng: 105.834160,
          icon: 'img/icon-map.png',
          text: 'khanh',
        };
        var defaut2 = {
          lat: self.data('lat'),
          lng: self.data('lng'),
          icon: self.find('img').attr('src'),
          text: self.find('p').html(),
        };

        // console.log(self.find('p').html());

        var marker = $.extend(defaut, defaut2);
        markers.push(marker);
      });

      map = new google.maps.Map(document.getElementById('map-page'),
        mapOptions);

      for (var i = 0; i < markers.length; i++) {
        var latLng = new google.maps.LatLng(markers[i].lat, markers[i].lng);
        var icon = markers[i].icon;
        var text = markers[i].text;
        var marker = new google.maps.Marker({
          position: latLng,
          icon: icon,
          map: map,
        });

        var infowindow = new google.maps.InfoWindow({
          maxWidth: 200,
        });

        google.maps.event.addListener(marker, 'click', function(marker, i) {
          return function() {
            infowindow.setContent(contentString(markers[i].text));
            infowindow.open(map, marker);
          };
        }(marker, i));

        new google.maps.event.trigger(marker, 'click');
      }
    };

    $(window).on('load', function() {
      if ($('#map-page').length) {
        initialize();
      }
    });
  }

  google_map();

  /*
  * headerPage
  */
  function headerPage() {
    var _module = $('.header');

    function handleFix() {
      var fixheader = 'header__fixheight';

      var appendFix = debounce(function() {
        if (_module.length) {

          var hHeader = _module.outerHeight(),
            headerFix = '<div class="fixheight-header"></div>';

          if ($('.fixheight-header').length === 0) _module.closest(
            '.page-wrap').prepend(headerFix);
          $('.fixheight-header').css('height', hHeader + 'px');
        }
      }, 100);
      appendFix();
      $(window).on('resize', appendFix);
    }

    function toggleMenu() {
      var toggle = $('.header__iconmenu');

      toggle.on('click', function(e) {
        e.stopPropagation();
        var self = $(this);
        self.toggleClass('toggle-active');
        $('.menu-mobile').toggleClass('menu-mobile--active');
        $('body').toggleClass('body-fix-overflow');

        $('body').on('click', function() {
          self.removeClass('toggle-active');
          $('.menu-mobile').removeClass('menu-mobile--active');
        });
      });
    }

    function ScrollEffect() {
      var scrollfix = function scrollfix() {
        var scrollTop = $(window).scrollTop(),
          heightInfo = $('.header__info').height(),
          offset = 0;

        if (scrollTop <= heightInfo) {
          offset = scrollTop;
        } else {
          offset = heightInfo;
        }

        _module.css({
          'transform': 'translateY(-' + offset + 'px)',
        });
      };
      scrollfix();
      $(window).on('scroll', scrollfix);
    }

    function menuMobile() {
      $('.menu-mobile').on('click', function(e) {
        e.stopPropagation();
      });

      var selectors = '.menu-mobile .menu-item-has-children > a,' +
        '.menu-mobile .menu-item-has-children > .menu-item-icon';

      $(selectors).on('click', function(e) {
        e.preventDefault();
        var self = $(this);
        self.closest('li').toggleClass('active-submneu');
        self.closest('li').find('ul').slideToggle();
      });
    }

    function headerSearch() {
      $('.header-icon-search').on('click', function() {
        $('.header-search').addClass('header-search--active');

        $(this).closest('header').addClass('header-show-search');
        $('.header-search').find('.form-control').focus();
      });

      $('.header-search__form .form-close').on('click', function() {
        $('.header-search').removeClass('header-search--active');
        $('.header').removeClass('header-show-search');
      });
    }

    function headerScroll() {
      if (_module.length) {
        var headeroom = new Headroom(document.querySelector('header'), {
          tolerance: 4,
          offset: 100,
          classes: {
            pinned: 'header-pin',
            unpinned: 'header-unpin',
          },
          onPin: function onPin() {
            var scrollfix = function scrollfix() {
              var scrollTop = $(window).scrollTop(),
                heightInfo = $('.header__info').height(),
                offset = 0;

              if (scrollTop <= heightInfo) {
                offset = scrollTop;
              } else {
                offset = heightInfo;
              }

              _module.css({
                'transform': 'translateY(-' + offset + 'px)',
              });
            };
            scrollfix();
            $(window).on('scroll', scrollfix);

            if ($('.menubox-min-section').length) {
              $('.menubox-min-section').addClass('menubox-pin');
            }
          },
          onUnpin: function onUnpin() {
            if ($('.menubox-min-section').length) {
              $('.menubox-min-section').removeClass('menubox-pin');
            }
          },
        });
        headeroom.init();
      }
    }

    handleFix();
    headerScroll();
    toggleMenu();
    // ScrollEffect();
    menuMobile();
    headerSearch();
  }

  /**
   * swiperSlides
   */
  function swiperSlides() {
    $('.swiper__module').each(function() {
      var self = $(this),
        wrapper = $('.swiper-wrapper', self),
        optData = eval('(' + self.attr('data-options') + ')'),
        optDefault = {
          speed: 700,
          pagination: {
            el: self.find('.swiper-pagination-custom'),
            clickable: true,
          },
          navigation: {
            nextEl: self.find('.swiper-button-next-custom'),
            prevEl: self.find('.swiper-button-prev-custom'),
          },
          spaceBetween: 30,
        },
        options = $.extend(true, optDefault, optData);
      wrapper.children().wrap('<div class="swiper-slide"></div>');
      var swiper = new Swiper(self, options);

      function thumbnails(selector) {

        if (selector.length > 0) {
          var wrapperThumbs = selector.children('.swiper-wrapper'),
            optDataThumbs = eval('(' + selector.attr('data-options') + ')'),
            optDefaultThumbs = {
              spaceBetween: 10,
              speed: 700,
              centeredSlides: true,
              slidesPerView: 3,
              touchRatio: 0.3,
              slideToClickedSlide: true,
              pagination: {
                el: selector.find('.swiper-pagination-custom'),
                clickable: true,
              },
              navigation: {
                nextEl: selector.find('.swiper-button-next-custom'),
                prevEl: selector.find('.swiper-button-prev-custom'),
              },
            },
            optionsThumbs = $.extend(optDefaultThumbs, optDataThumbs);
          wrapperThumbs.children().wrap('<div class="swiper-slide"></div>');
          var swiperThumbs = new Swiper(selector, optionsThumbs);
          swiper.controller.control = swiperThumbs;
          swiperThumbs.controller.control = swiper;
        }
      }

      thumbnails(self.next('.swiper-thumbnails__module'));
    });
  }

  /**
   * HeroSlide
   */
  function heroSlide() {
    var slide = $('.hero__slide'),
      thumb = $('.hero__thumb');

    if (slide.length) {
      var slides = new Swiper(slide, {
        speed: 700,
        spaceBetween: 30,
        effect: 'fade',
        autoplay: {
          delay: 8000,
        },
      });

      var thumbs = new Swiper(thumb, {
        speed: 700,
        touchRatio: 0.3,
        slideToClickedSlide: true,
        autoplay: {
          delay: 8000,
        },
        pagination: {
          el: thumb.find('.swiper-pagination-custom'),
          clickable: true,
        },
      });

      slides.controller.control = thumbs;
      thumbs.controller.control = slides;
    }
  }

  /**
   * slideYearProcess
   */
  function slideYearProcess() {
    var wrap = $('.slide-year-process-wrapper'),
      slide = wrap.find('.slide-year-process');

    if (slide.length) {
      var slides = new Swiper(slide, {
        slidesPerView: 4,
        spaceBetween: 30,
        scrollbar: {
          el: wrap.find('.swiper-pagination-scrollbar'),
          draggable: true,
        },
        breakpoints: {
          1200: {
            slidesPerView: 3,
            spaceBetween: 30,
          },
          991: {
            slidesPerView: 2,
            spaceBetween: 20,
          },
          500: {
            slidesPerView: 1,
            spaceBetween: 10,
          },
        },
      });

      slide.find('.swiper-slide').matchHeight();

      var myEfficientFn = debounce(function() {
        slide.find('.swiper-slide').matchHeight();
      }, 250);

      window.addEventListener('resize', myEfficientFn);
    }
  }

  /**
   * gridJs
   */
  function gridJs() {
    $('.grid-css').mdGridJs();

    $('.grid__filter-project').each(function() {
      var self = $(this);
      var inner = self.closest('.md-section').find('.grid__inner');

      self.on('click', 'a', function(e) {
        e.preventDefault();
        var $el = $(this);
        var selector = $el.attr('data-filter');
        self.find('.current').removeClass('current');
        $el.parent().addClass('current');
        inner.isotope({
          filter: selector,
        });
      });
    });

    $('.grid-css--popup-album').each(function() {
      var self = $(this),
        inner = $('.grid__inner', self);

      inner.magnificPopup({
        delegate: 'a',
        type: 'image',
        tLoading: 'Loading image #%curr%...',
        overflowY: 'scroll',
        image: {
          markup: '<div class="mfp-figure">\n\t\t\t\t\t\t\t<div class="mfp-close"></div>\n\t\t\t\t            <div class="mfp-image">\n\t\t\t\t            \t<div class="mfp-img"></div>\n\t\t\t\t            </div>\n\t\t\t\t            <div class="mfp-bottom-bar">\n\t\t\t\t              \t<div class="mfp-title"></div>\n\t\t\t\t              \t<div class="mfp-counter"></div>\n\t\t\t\t           \t</div>\n\t\t\t\t        </div>',
          tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
          titleSrc: function titleSrc(item) {
            return item.el.attr('title');
          },
        },
        gallery: {
          enabled: true,
          navigateByImgClick: true,
          preload: [0, 1],
        },
        removalDelay: 500,
        showCloseBtn: true,
        closeOnContentClick: false,
        closeBtnInside: true,
        callbacks: {
          beforeOpen: function beforeOpen() {
            this.st.mainClass = this.st.el.attr('data-effect');
          },

          buildControls: function buildControls() {
            this.contentContainer.find('.mfp-image').append(this.arrowLeft);
            this.contentContainer.find('.mfp-image').append(this.arrowRight);
          },
        },
        midClick: true,
      });
    });
  }

  /**
   * popupJs
   */
  function popupJs() {
    var magnificPopupDefault = {
      type: 'image',
      overflowY: 'scroll',
      image: {
        markup: '<div class="mfp-figure">\n\t\t\t\t\t\t<div class="mfp-close"></div>\n\t\t\t            <div class="mfp-image">\n\t\t\t            \t<div class="mfp-img"></div>\n\t\t\t            </div>\n\t\t\t            <div class="mfp-bottom-bar">\n\t\t\t              \t<div class="mfp-title"></div>\n\t\t\t           \t</div>\n\t\t\t        </div>',
        tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
        tCounter: false,
        titleSrc: function titleSrc(item) {
          return item.el.attr('title');
        },
      },
      removalDelay: 500,
      showCloseBtn: false,
      closeOnBgClick: true,
      closeBtnInside: true,
      callbacks: {
        beforeOpen: function beforeOpen() {
          this.st.mainClass = this.st.el.attr('data-effect') ? this.st.el.attr(
            'data-effect') : 'mfp-zoom-in';
        },
      },
      midClick: true, // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
    };

    $('[data-init="magnificPopup"]').each(function(index, el) {
      var $el = $(this);

      $el.magnificPopup(magnificPopupDefault);
    });

    $('[data-init="magnificPopupVideo"]').each(function(index, el) {
      var $el = $(this);
      var option = {
        type: 'iframe',
        disableOn: 500,

        // Merge settings.
      };
      var settings = $.extend(true, magnificPopupDefault, option);

      $el.magnificPopup(settings);
    });

    $('[data-init="magnificPopupInline"]').each(function(index, el) {
      var $el = $(this);
      var option = {
        type: 'inline',
        removalDelay: 200,

        // Merge settings.
      };
      var settings = $.extend(true, magnificPopupDefault, option);

      $el.magnificPopup(settings);
    });

    $('[data-init="magnificPopupAjax"]').each(function(index, el) {
      var $el = $(this);
      var option = {
        type: 'ajax',

        // Merge settings.
      };
      var settings = $.extend(true, magnificPopupDefault, option);

      $el.magnificPopup(settings);
    });
  }

  /**
   * Call function
   */
  function countTos() {
    var module = $('.countTo');

    module.each(function() {
      var self = $(this),
        countNumber = $('.countTo__number', self),
        unit = self.data('unit');

      var optData = eval('(' + self.attr('data-options') + ')'),
        optDefault = {
          from: 0,
          to: 20,
          speed: 2000,
          refreshInterval: 50,
          formatter: function formatter(value, options) {
            if (value.toFixed(options.decimals) < 10) {
              return '0' + value.toFixed(options.decimals);
            } else {
              return value.toFixed(options.decimals);
            }
          },
        },
        options = $.extend(optDefault, optData);

      self.waypoint(function(direction) {
        countNumber.countTo(options);

        this.destroy();
      }, {
        offset: function offset() {
          return Waypoint.viewportHeight() - self.outerHeight() - 150;
        },
      });
    });
  }

  /**
   * Accordions
   */
  function accordionJs() {
    $('.ac-accordion').each(function() {
      var self = $(this),
        optData = eval('(' + self.attr('data-options') + ')'),
        optDefault = {
          active: 0,
          collapsible: false,
          activeEvent: 'click',
          heightStyle: 'content',
          duration: 200,
          onOffIcon: {
            enable: true,
            expandIcon: 'fa fa-chevron-up',
            collapseIcon: 'fa fa-chevron-down',
            position: 'right',
          },
        },
        options = $.extend(optDefault, optData);
      self.aweAccordion(options);
    });
  }

  /**
   * Tabs
   */
  function tabJs() {
    $('.tabs__module').each(function() {
      var self = $(this),
        optData = eval('(' + self.attr('data-options') + ')'),
        optDefault = {
          active: 0,
          activeEvent: 'click',
          navigatorPosition: 'left',
        },
        options = $.extend(optDefault, optData);
      self.aweTabs(options);
    });
  }

  /**
   * numberTextLines
   */
  function numberTextLines() {
    $.fn.numberTextLine = function(opts) {
      $(this).each(function() {
        var el = $(this),
          defaults = {
            numberLine: 0,
          },
          data = el.data(),
          dataTemp = $.extend(defaults, opts),
          options = $.extend(dataTemp, data);

        if (!options.numberLine) return false;

        el.bind('customResize', function(event) {
          event.stopPropagation();
          reInit();
        }).trigger('customResize');
        $(window).resize(function() {
          el.trigger('customResize');
        });

        function reInit() {
          var fontSize = parseInt(el.css('font-size')),
            lineHeight = parseInt(el.css('line-height')),
            overflow = fontSize * (lineHeight / fontSize) * options.numberLine;

          el.css({
            'display': 'block',
            'max-height': overflow,
            'overflow': 'hidden',
          });
        }
      });
    };

    $('[data-number-line]').numberTextLine();
  }

  /**
   * New hover Effect
   */
  function hoverNewEffect() {
    $('.new__fix').each(function() {
      var self = $(this),
        text = $('.new__text', self);

      var windowResize = function windowResize() {
        var wh = text.outerHeight();
        self.css({
          'transform': 'translateY(' + wh + 'px)',
        });
      };
      windowResize();
      $(window).on('resize', windowResize);
    });
  }

  /**
   * footerScroll
   */
  function footerScroll() {
    var fixeds = function fixeds() {
      var wh = $(window).height(),
        scroll = $(window).scrollTop();

      if (scroll >= wh + 100) {
        $('.footer-fixed').addClass('footer-fixed-show');
      } else {
        $('.footer-fixed').removeClass('footer-fixed-show');
      }
    };
    fixeds();
    $(window).on('scroll', fixeds);
  }

  /**
   * heroSelect
   */
  function heroSelect() {
    function formatState(state) {
      if (!state.id) {
        return state.text;
      }
      var baseUrl = $(state.element).attr('data-img');

      var $state = $(
        '<span><img src="' + baseUrl + '" class="img-flag" /> ' + state.text +
        '</span>');
      return $state;
    };

    $('.hero-search .form-control').select2({
      minimumResultsForSearch: Infinity,
      templateResult: formatState,
      templateSelection: formatState,
      theme: 'hero',
    });
  }

  /**
   * selecJs
   */
  function selecJs() {
    $('.select2Js').select2({
      minimumResultsForSearch: Infinity,
      theme: 'select',
    });
  }

  /**
   * Match height
   */
  function fixHiehgtJs() {
    $('.row-eq-height > [class*="col-"]').matchHeight();

    var myEfficientFn = debounce(function() {
      $('.row-eq-height > [class*="col-"]').matchHeight();
    }, 250);

    window.addEventListener('resize', myEfficientFn);
  }

  /**
   * Match height
   */
  function menuBoxMin() {
    var inner = $('.one-page-nav'),
      fix = $('.header__inner').height();

    inner.onePageNav({
      currentClass: 'current',
      changeHash: false,
      scrollSpeed: 750,
      scrollThreshold: 0.5,
      scrollOffset: fix,
      filter: '',
      easing: 'swing',
    });
  }

  /**
   * handlingAjax
   */
  function checkFormValidate() {
    $('.characterbox-max').each(function() {
      var self = $(this),
        input = $('.characterbox__input', self);

      input.on('change', function() {
        if (self.find('.characterbox__input:checked').length > 3) {
          this.checked = false;
        }
      });
    });
  }

  /**
   * rangerBox
   */
  function rangerBox() {
    $('.rangerbox').each(function() {
      var self = $(this),
        handle = $('.rangerbox-handle', self),
        mins = parseInt(self.attr('data-min')),
        maxs = parseInt(self.attr('data-max')),
        values = parseInt(self.attr('data-value')),
        unit = self.attr('data-unit') ? self.attr('data-unit') : '';

      self.slider({
        range: 'min',
        min: mins,
        max: maxs,
        value: values,
        create: function create() {
          handle.text($(this).slider('value') + unit);
        },
        slide: function slide(event, ui) {
          handle.text(ui.value + unit);
        },
      });
    });
  }

  /**
   * footerItemJs
   */
  function footerItemJs() {
    var appendFix = debounce(function() {
      var ww = $(window).width();

      if (ww < 768) {
        $('.footer-item-js').on('click', '.footer__itemtitle', function() {
          $(this).next().slideToggle();
        });
      }
    }, 400);

    appendFix();
    $(window).on('resize', function() {
      $('.footer-item-js').off('click', '.footer__itemtitle');
      appendFix();
    });

    $('.btn-click-category').on('click', function() {
      $(this).parent().toggleClass('active-box');
    });
  }

  /**
   * backtoTop
   */
  function backtoTop() {
    $('.footer__backtotop').on('click', function(e) {
      e.preventDefault();
      $('html,body').animate({
        scrollTop: 0,
      }, 700);
    });
  }

  /**
   * toolsosanhAcc
   */
  function toolsosanhAcc() {
    $('.tool-sosanh-acc').each(function() {
      var self = $(this),
        item = $('.tool-sosanh-acc__item', self),
        title = $('.tool-sosanh-acc__title', item);

      title.on('click', function() {
        $(this).next().slideToggle();
        $(this).toggleClass('tool-sosanh-acc__title--active');
      });
    });
  }

  /**
   * scrollCategoryMin
   */
  function scrollCategoryMin() {
    var fix = function fix() {
      var scroll = $(window).scrollTop(),
        wh = $(window).outerHeight() / 2;

      if (scroll >= wh) {
        $('.category-min').addClass('category-min--show');
      } else {
        $('.category-min').removeClass('category-min--show');
      }
    };
    fix();
    $(window).on('scroll', fix);
  }

  /**
   * menuboxScroll
   */
  function menuboxScroll() {
    var scrolls = function scrolls() {
      if ($('.menubox-min').length) {
        var scrollTop = $(window).scrollTop(),
          offset = $('.menubox-min-fix').offset().top;

        if (scrollTop >= offset) {
          $('.menubox-min-section').addClass('active-top');
        } else {
          $('.menubox-min-section').removeClass('active-top');
          $('.menubox-min-section').removeClass('menubox-pin');
        }
      }
    };
    scrolls();
    $(window).on('scroll', scrolls);
  }

  /**
   * Call function
   */
  heroSelect();
  selecJs();
  fixHiehgtJs();
  popupJs();
  checkFormValidate();
  rangerBox();
  footerItemJs();
  backtoTop();
  toolsosanhAcc();

  $(window).on('load', function() {
    headerPage();
    footerScroll();
    swiperSlides();
    slideYearProcess();
    heroSlide();
    gridJs();
    countTos();
    tabJs();
    accordionJs();
    numberTextLines();
    hoverNewEffect();
    menuBoxMin();
    scrollCategoryMin();
    menuboxScroll();
  });

})(jQuery);
