import mediumZoom from 'medium-zoom';
import Cookies from 'js-cookie';
import scrollToElement from 'scroll-to-element';

import './bootstrap';
import { sendRequest } from './utils/request';
import initializeAutocomplete from './site/search';

require('jquery-hoverintent');
window.bsn = require('bootstrap.native/dist/bootstrap-native-v4');

function initMegamenuMenu() {
  const $menu = $('.header__desktop .menu-list');

  if (!window.isMobile.iOS() && !window.isMobile.Android()) {
    $menu
      .find('li.menu-item-megamenu')
      .hoverIntent({
        timeout: 150,
        interval: 50,
        sensitivity: 7,

        over() {
          $menu.find('li.open')
            .removeClass('open');
          $(this)
            .addClass('open');
        },

        out() {
          $(this)
            .removeClass('open');
        },
      });
  }
}

function initMediumZoom() {
  const images = document.querySelectorAll(
    '.new-detail__entry .blocks-gallery-item > img,' +
    '.new-detail__entry .blocks-gallery-item > figure > img,' +
    '.new-detail__entry .wp-block-image > img,' +
    '.new-detail__entry .wp-block-image > figure > img',
  );

  if (images.length > 0) {
    mediumZoom(images, { background: 'rgba(255, 255, 255, 0.97)' });
  }
}

function initSharePopup() {
  const popupSize = {
    width: 780,
    height: 550,
  };

  $(document)
    .on('click', '.social-button', function(e) {
      let verticalPos = Math.floor(
        ($(window).width() - popupSize.width) / 2,
      );

      let horisontalPos = Math.floor((
        $(window).height() - popupSize.height) / 2,
      );

      const popup = window.open($(this).prop('href'), 'social',
        'width=' + popupSize.width + ',height=' + popupSize.height +
        ',left=' + verticalPos + ',top=' + horisontalPos +
        ',location=0,menubar=0,toolbar=0,status=0,scrollbars=1,resizable=1');

      if (popup) {
        popup.focus();
        e.preventDefault();
      }
    });
}

function initScrollToTocs() {
  $(document)
    .on('click', '.layout-content__toc a', (e) => {
      scrollToElement(e.currentTarget.getAttribute('href'), {
        offset: -15,
        duration: 350,
      });
    });
}

function ajaxSubmitContactForm(formElement) {
  let isLoading = false;

  $(formElement).on('submit', async (e) => {
    e.preventDefault();

    if (!formElement.reportValidity()) {
      return;
    }

    if (isLoading) {
      return;
    }

    let form = new FormData(formElement);
    isLoading = true;

    try {
      await sendRequest(formElement.getAttribute('action'), form);
      setTimeout(() => { window.location.href = '/thank-you'; }, 250);
    } catch (e) {
      console.log(e);
    }

    isLoading = false;
  });
}

function initCategoryMinToggle() {
  const $categoryMin = $('.category-min');
  if (!$categoryMin.length) {
    return;
  }

  const $button = $categoryMin.find('.category-min__toggle-button');
  const isCategoryMinOpen = Cookies.get('category_min_open');

  if (isCategoryMinOpen === 'true' && !$categoryMin.hasClass('isOpen')) {
    $categoryMin.addClass('isOpen');
  } else if (isCategoryMinOpen === 'false') {
    $categoryMin.addClass('isHidden');
  }

  $button.on('click', (e) => {
    e.preventDefault();

    if ($categoryMin.hasClass('isOpen')) {
      $categoryMin.removeClass('isOpen').addClass('isHidden');
      Cookies.set('category_min_open', 'false');
    } else {
      $categoryMin.removeClass('isHidden').addClass('isOpen');
      Cookies.set('category_min_open', 'true');
    }
  });
}

(function() {
  initializeAutocomplete();
  initMegamenuMenu();
  initMediumZoom();
  initSharePopup();
  initScrollToTocs();
  initCategoryMinToggle();

  const tables = $('.table-phamviapdung');
  const updateTbodyTitle = function(tr, headings) {
    $(tr).find('> td').each((i, td) => {
      td.setAttribute('data-title', headings[i] || '');
    });
  };

  if (tables.length) {
    tables.each(function(index, table) {
      let headings = [];

      $(table)
        .find('thead > tr > th')
        .each((index, element) => {
          headings[index] = $(element).text();
        });

      $(table)
        .find('tbody > tr')
        .each((i, tr) => {
          updateTbodyTitle(tr, headings);
        });
    });
  }

  const contactFormElements = document.querySelectorAll('form#contact-form, .contact-form__inner');
  if (contactFormElements.length) {
    contactFormElements.forEach((element) => {
      ajaxSubmitContactForm(element);
    });
  }
})();
