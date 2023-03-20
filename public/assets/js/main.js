/*  ---------------------------------------------------
    Template Name: Ogani
    Description:  Ogani eCommerce  HTML Template
    Author: Colorlib
    Author URI: https://colorlib.com
    Version: 1.0
    Created: Colorlib
---------------------------------------------------------  */

"use strict";

$(document).ready(function() {

  /*------------------
        Preloader
    --------------------*/
  $(window).on("load", function () {
    $(".loader").fadeOut();
    $("#preloder").delay(200).fadeOut("slow");

    /*------------------
            Gallery filter
        --------------------*/
    $(".featured__controls li").on("click", function () {
      $(".featured__controls li").removeClass("active");
      $(this).addClass("active");
    });
    if ($(".featured__filter").length > 0) {
      var containerEl = document.querySelector(".featured__filter");
      var mixer = mixitup(containerEl);
    }
  });

  /*------------------
        Background Set
    --------------------*/
  // $(".set-bg").each(function () {
  //   var bg = $(this).data("setbg");
  //   $(this).css("background-image", "url(" + bg + ")");
  //   $(this).css("width", "292.5px");
  // });

  //Humberger Menu

  $(".humberger__open").on("click", function () {
    console.log('clicked');
    $(".humberger__menu__wrapper").addClass("show__humberger__menu__wrapper");
    $(".humberger__menu__overlay").addClass("active");
    $("body").addClass("over_hid");
  });

  $(".humberger__menu__overlay").on("click", function () {
    $(".humberger__menu__wrapper").removeClass(
      "show__humberger__menu__wrapper"
    );
    $(".humberger__menu__overlay").removeClass("active");
    $("body").removeClass("over_hid");
  });

  /*------------------
  Navigation
--------------------*/
  $(".mobile-menu").slicknav({
    prependTo: "#mobile-menu-wrap",
    allowParentLinks: true,
  });

  /*-----------------------
        Categories Slider
    ------------------------*/
  $(".categories__slider").owlCarousel({
    loop: true,
    margin: 0,
    items: 4,
    dots: false,
    nav: true,
    navText: [
      "<span class='fa fa-angle-left'><span/>",
      "<span class='fa fa-angle-right'><span/>",
    ],
    animateOut: "fadeOut",
    animateIn: "fadeIn",
    smartSpeed: 1200,
    autoHeight: false,
    autoplay: true,
    responsive: {
      0: {
        items: 1,
      },

      480: {
        items: 2,
      },

      768: {
        items: 3,
      },

      992: {
        items: 4,
      },
    },
  });

  $(".hero__categories__all").on("click", function () {
    $(".hero__categories ul").slideToggle(400);
  });

  /*--------------------------
        Latest Product Slider
    ----------------------------*/
    $(".latest-product__slider").owlCarousel({
      loop: true,
      margin: 0,
      items: 3,
      dots: false,
      nav: true,
      navText: ["<span class='fa fa-angle-left'><span/>", "<span class='fa fa-angle-right'><span/>"],
      smartSpeed: 1200,
      autoHeight: false,
      autoplay: true
  });

  /*-----------------------------
        Product Discount Slider
    -------------------------------*/
  $(".product__discount__slider").owlCarousel({
    loop: true,
    margin: 0,
    items: 1,
    dots: true,
    smartSpeed: 1200,
    autoHeight: false,
    autoplay: true,
    responsive: {
      320: {
        items: 1,
      },

      480: {
        items: 2,
      },

      768: {
        items: 2,
      },

      992: {
        items: 3,
      },
    },
  });

  $(".testpp").owlCarousel({
    items: 3,
  });

  /*---------------------------------
        Product Details Pic Slider
    ----------------------------------*/
  $(".product__details__pic__slider").owlCarousel({
    loop: true,
    margin: 20,
    items: 4,
    dots: true,
    smartSpeed: 1200,
    autoHeight: false,
    autoplay: true,
  });

  /*-----------------------
		Price Range Slider
	------------------------ */
  var rangeSlider = $(".price-range"),
    minamount = $("#minamount"),
    maxamount = $("#maxamount"),
    minPrice = rangeSlider.data("min"),
    maxPrice = rangeSlider.data("max");
  rangeSlider.slider({
    range: true,
    min: minPrice,
    max: maxPrice,
    values: [minPrice, maxPrice],
    slide: function (event, ui) {
      minamount.val("$" + ui.values[0]);
      maxamount.val("$" + ui.values[1]);
    },
  });
  minamount.val("$" + rangeSlider.slider("values", 0));
  maxamount.val("$" + rangeSlider.slider("values", 1));

  /*--------------------------
        Select
    ----------------------------*/
  $("select").niceSelect();

  /*------------------
		Single Product
	--------------------*/
  $(".product__details__pic__slider img").on("click", function () {
    var imgurl = $(this).data("imgbigurl");
    var bigImg = $(".product__details__pic__item--large").attr("src");
    if (imgurl != bigImg) {
      $(".product__details__pic__item--large").attr({
        src: imgurl,
      });
    }
  });

  /*-------------------
		Quantity change
	--------------------- */
  var proQty = $(".pro-qty");
  proQty.prepend('<span class="dec qtybtn">-</span>');
  proQty.append('<span class="inc qtybtn">+</span>');
  proQty.on("click", ".qtybtn", function () {
    var $button = $(this);
    var oldValue = $button.parent().find("input").val();
    if ($button.hasClass("inc")) {
      var newVal = parseFloat(oldValue) + 1;
    } else {
      // Don't allow decrementing below zero
      if (oldValue > 0) {
        var newVal = parseFloat(oldValue) - 1;
      } else {
        newVal = 0;
      }
    }
    $button.parent().find("input").val(newVal);
  });
});

$(document).ready(function () {
  $(".owl-carousel").owlCarousel({
    loop: true,
    margin: 0,
    items: 4,
    dots: true,
    smartSpeed: 1200,
    autoHeight: false,
    autoplay: true,
    responsive: {
      320: {
        items: 1,
      },

      480: {
        items: 2,
      },

      768: {
        items: 2,
      },

      992: {
        items: 3,
      },
    },
  });

  $(".product__discount__slider owl-carousel").owlCarousel({
    loop: true,
    margin: 0,
    items: 4,
    dots: true,
    smartSpeed: 1200,
    autoHeight: false,
    autoplay: true,
    responsive: {
      320: {
        items: 1,
      },

      480: {
        items: 2,
      },

      768: {
        items: 2,
      },

      992: {
        items: 3,
      },
    },
  });
});

jQuery;

/*-------------------------
JS
---------------------------*/

function changeCat(catName) {
  let listProd = document.getElementsByClassName("mix");

  for (let prod of listProd) {
    console.log(prod);

    prod.hidden = true;

    console.log(prod.id === "Digestion");

    if (prod.id === catName) {
      prod.hidden = false;
    }
  }
}

// function calculateTotal() {
//   var carrierSelect = document.getElementById("carrier-select");
//   var carrierPrice = document.getElementById("carrier-price");
//   var totalPrice = document.getElementById("total-price");
//   var subtotalttc = document.getElementById("subtotalttc");
//   carrierPrice.textContent = carrierSelect.value + " €";
//   totalPrice.textContent = parseFloat(subtotalttc.value) + parseFloat(carrierSelect.value) + " €";
// }

function calculateTotal() {
  var carrierSelect = document.getElementById("carrier-select");
  var carrierPrice = document.getElementById("carrier-price");
  var totalPrice = document.getElementById("total-price");
  var subtotalttc = document.getElementById("subtotalttc");

  var selectedCarrierOption =
    carrierSelect.options[carrierSelect.selectedIndex];
  var carrierPriceValue =
    selectedCarrierOption.getAttribute("data-carrier-price");

  carrierPrice.textContent = carrierPriceValue / 100 + " €";
  totalPrice.textContent =
    (
      parseFloat(subtotalttc.value) + parseFloat(carrierPriceValue / 100)
    ).toFixed(2) + " €";
}

// Ajouter un gestionnaire d'événements au clic sur le bouton "Ajouter à la liste de souhaits"
document
  .getElementById("add-to-wishlist-button")
  .addEventListener("click", function () {
    // Récupérer l'ID du produit à ajouter à la liste de souhaits
    var productId = document.getElementById("product-id").value;

    // Envoyer une requête AJAX à la fonction addToWishlist
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "/add");
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
      if (xhr.status === 200) {
        // L'ajout à la liste de souhaits a réussi, vous pouvez afficher un message de réussite ici si vous le souhaitez
      } else {
        // L'ajout à la liste de souhaits a échoué, vous pouvez afficher un message d'erreur ici si vous le souhaitez
      }
    };
    xhr.send("productId=" + encodeURIComponent(productId));
  });

