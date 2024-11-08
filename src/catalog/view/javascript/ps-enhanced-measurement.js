$(document).ready(function () {
  var addToWishlists = $('[data-ps-track-event="add_to_wishlist"]');
  var selectItems = $('[data-ps-track-event="select_item"]');
  var selectPromotions = $('[data-ps-track-event="select_promotion"]');

  function linkClick(e) {
    e.preventDefault();

    var self = $(this);
    var productId = self.data("ps-track-id");

    console.log(productId);

    setTimeout(function () {
      location = location = self.attr("href");
    }, 200);
  }

  function wishlistClick(e) {
    e.preventDefault();

    var self = $(this);
    var productId = self.data("ps-track-id");

    console.log(productId);

    self.off("click", wishlistClick);

    self.trigger("click");

    self.on("click", wishlistClick);
  }

  function removeFromCartClick(e) {
    e.preventDefault();

    var self = $(this);
    var productId = self.data("ps-track-id");

    self.removeAttr("data-ps-track-event");

    console.log(productId);

    setTimeout(function () {
      self.trigger("click");
    }, 200);
  }

  if (selectItems.length > 0) {
    selectItems.on("click", linkClick);
  }

  if (selectPromotions.length > 0) {
    selectPromotions.on("click", linkClick);
  }

  if (addToWishlists.length > 0) {
    addToWishlists.on("click", wishlistClick);
  }

  $(document).on(
    "click",
    '[data-ps-track-event="remove_from_cart"]',
    removeFromCartClick
  );
});

(function ($) {
  var originalAjax = $.ajax;

  $.ajax = function (options) {
    var requestUrl = options.url;
    var dataString = typeof options.data === "string" ? options.data : $.param(options.data);
    var productId = getParameterByName("product_id", dataString);

    var jqXHR = originalAjax.call(this, options);

    jqXHR.done(function (data) {
      if (
        requestUrl.indexOf("route=checkout/cart.add") > 0 &&
        productId &&
        typeof data === "object" &&
        "success" in data
      ) {
        console.log(
          "Successfully added product to cart, product_id: " + productId
        );
      }
    });

    return jqXHR;
  };

  function getParameterByName(name, data) {
    if (data) {
      var params = data.split("&");

      for (var i = 0; i < params.length; i++) {
        var param = params[i].split("=");

        if (param[0] === name) {
          return decodeURIComponent(param[1]);
        }
      }
    }

    return null;
  }
})(jQuery);
