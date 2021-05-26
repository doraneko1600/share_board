"use strict";

$(function () {
  var $scrollAuto = $('#scroll');
  $scrollAuto.animate({
    scrollTop: $scrollAuto[0].scrollHeight
  }, 0);
});