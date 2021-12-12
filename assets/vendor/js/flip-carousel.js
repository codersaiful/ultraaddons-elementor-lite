// Flip Carousel Plugin
// version 1.5
// written 12/13/12
// revamped and polished up 3/15/14
// copyright(c) 2012-2014 Ethan Herr
// http://herrmedia.com
// MIT License
(function ($) {
  'use strict';
$.fn.flipcarousel = function (options) {
  //default options
  var ops = $.extend(
    {
      itemsperpage: 3, //number of cards to display at once
      duration: 500, //flip css transition-duration property in ms
      randomizer: 0, //give randomness to card flip delay and duration (0 - 1)
      loader: false, //show loader when loading content
      arrows: true, //arrows for previous/next navigation
      //accrual : 1 //number of pages to preload (forthcoming)
      pagination: false,
    },
    options
  );

  //structural elements
  var $container = $('<div class="flip-carousel">'),
    $card = $('<div class="card">'),
    $faces = $('<div class="face front"></div><div class="face back"></div>'),
    $controls = $('<div class="controls">'),
    $dots_container = $('<div class="controls dots">'),
    $span = $('<span class="dot"></span>'),
    $arrowright = $('<div class="arrow right">'),
    $arrowleft = $('<div class="arrow left hide">'),
    $loader = $('<div class="loader hide">'),
    $origs = this,
    $cells = this.clone(),
    //control elements
    i = 0, //current page index
    face = ".front", //flag for which card to append new data to
    deg = 0, //track degree of flip
    disabled = true, //flag to prevent clicks while loading
    //master page/cell tracker
    pages = [];
  for (var p = 0; p < Math.ceil($cells.length / ops.itemsperpage); p++) {
    var start = ops.itemsperpage * p;
    pages[p] = $cells.slice(start, start + ops.itemsperpage);
  }

  function build() {
    //make base html structure

    var $li = $("<li>"),
      $ul = $("<ul>");

    $card.css({
      "-webkit-transition-duration": ops.duration + "ms",
      "-moz-transition-duration": ops.duration + "ms",
      "-ms-transition-duration": ops.duration + "ms",
      "transition-duration": ops.duration + "ms",
    });

    $container.append($ul);
    $card.append($faces);
    $li.append($card);

    for (var c = 0; c < ops.itemsperpage; c++) {
      $ul.append($li.clone());
    }

    $arrowright.click(function () {
      if (!disabled) go(i + 1);
    });
    $arrowleft.click(function () {
      if (!disabled) go(i - 1);
    });

    $controls.append($arrowleft).append($arrowright);
    $container.append($controls).append($loader);
    $container.insertBefore($origs[0]);
    $card = $container.parent().find(".card");

    if (ops.pagination) {
      for (var dot = 0; dot < pages.length; dot++) {
        if (dot != 0) $dots_container.append($span.clone().data("flipid", dot));
        else
          $dots_container.append(
            $span.clone().addClass("active").data("flipid", dot)
          );
      }
      $dots_container.find(".dot").click(function () {
        if (!disabled) {
          go($(this).data("flipid"));
        }
      });
      $container.append($dots_container);
    }

    $origs.remove();
  }

  function load(cells, callback) {
    //load cell content, check for images

    if (ops.loader) $loader.removeClass("hide");
    var imgs = [];
    var loaded = 0;

    cells.find("img").each(function (index) {
      imgs.push("yah!");
      img = new Image();
      img.onload = function () {
        proceed(null);
      };
      img.onerror = function () {
        proceed($(this));
      };
      img.src = $(this).attr("src");
    });

    if (!imgs.length) {
      $loader.addClass("hide");
      callback(cells);
    }

    function proceed(imgerror) {
      loaded++;
      if (imgerror) {
        cells.find('img[src="' + imgerror.attr("src") + '"]').addClass("err");
      }
      if (loaded == imgs.length) {
        callback(cells);
        $loader.addClass("hide");
      }
    }
  }

  function place(cells, face) {
    //place content into div.face (into either .front or .back)
    for (c = 0; c < ops.itemsperpage; c++) {
      var cell = cells[c] || '<div class="empty"></div>';
      $card.eq(c).find(face).html($(cell).clone());
    }
  }

  function go(to) {
    //what page we are going to
    $dots_container.find(".dot").removeClass("active");
    $dots_container
      .find(".dot")
      .filter(function () {
        return $(this).data("flipid") == to;
      })
      .addClass("active");
    arrows(to);
    face = face == ".front" ? ".back" : ".front";
    $(".back, .front").css({ "z-index": 0 });
    $card.find(face).css({ "z-index": 1 });

    place(pages[to], face);

    if (to > i) deg += 180;
    else if (to < i) deg -= 180;

    disabled = true;
    flip(deg, function () {
      if (to > i && pages[to + 1]) {
        //background-load 1 page ahead
        load(pages[to + 1], function (cells) {
          disabled = false;
        });
      } else {
        disabled = false;
      }
      i = to;
    });
  }

  function arrows(i) {
    //update/hide arrows
    $arrowright.add($arrowleft).removeClass("hide");
    if (i == 0) $arrowleft.addClass("hide");
    if (i == pages.length - 1) $arrowright.addClass("hide");
  }

  function flip(d, flipdone) {
    //visual flippancy, updated to avoid IE
    if (ops.randomizer) randomizer();
    $card.css({
      "-webkit-transform": "rotateY(" + d + "deg)",
      "-moz-transform": "rotateY(" + d + "deg)", //,
      //'-ms-transform':'rotateY('+ d +'deg)',
      //'transform':'rotateY('+ d +'deg)'
    });
    if (flipdone) setTimeout(flipdone, ops.duration);
  }

  function shuffle(a) {
    //shuffle array
    for (
      var j, x, i = a.length;
      i;
      j = parseInt(Math.random() * i), x = a[--i], a[i] = a[j], a[j] = x
    );
    return a;
  }

  function randomizer() {
    //ramdomizing! (kind of)
    var delays = []; //delay animation between 0ms - ops.duration
    var durations = []; //deviate -0.5 and +1.5 away from ops.duration when ops.randomizer = 1

    for (var a = 1; a <= ops.itemsperpage; a++) {
      var num = (a / ops.itemsperpage) % 2;
      num = Math.floor((num * ops.randomizer * ops.duration) / 2);
      delays.push(num);
      num = (a / 2) % 1 == 0 ? -num : num;
      durations.push(ops.duration + num);
    }

    delays = shuffle(delays);
    $card.each(function (index) {
      $(this).css({
        "-webkit-transition-delay": delays[index] + "ms",
        "-moz-transition-delay": delays[index] + "ms",
        "-ms-transition-delay": delays[index] + "ms",
        "transition-delay": delays[index] + "ms",
      });
    });

    durations = shuffle(durations);
    $card.each(function (index) {
      $(this).css({
        "-webkit-transition-duration": durations[index] + "ms",
        "-moz-transition-duration": durations[index] + "ms",
        "-ms-transition-duration": durations[index] + "ms",
        "transition-duration": durations[index] + "ms",
      });
    });
  }

  function init() {
    //initializor!
    build();
    var toload = pages[1] ? $.merge(pages[0], pages[1]) : pages[0];
    load(
      toload, //load page 1 and 2 if 2 exists
      function (cells) {
        place(cells.slice(0, ops.itemsperpage), ".front");
        place(cells.slice(ops.itemsperpage, ops.itemsperpage * 2), ".back");
        disabled = false;
        arrows(0);
        flip(0);
      }
    );
  }

  init();

  this.goTo = go;
  this.pages = pages;
  this.pageCount = pages.length;
  return this;
};
})(jQuery);
