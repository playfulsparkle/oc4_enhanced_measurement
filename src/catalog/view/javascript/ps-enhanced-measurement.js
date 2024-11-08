function sendGAData(event, productId) {
  console.log('Event: ' + event)
  if (typeof window['ps_datalayer_' + productId] !== 'undefined') {
    console.log('Data: ');
    console.log(window['ps_datalayer_' + productId]);

    if (ps_config.measurement_implementation === 'gtag') {
      gtag('event', event, window['ps_datalayer_' + productId]);
    } else if (ps_config.measurement_implementation === 'gtm') {
      dataLayer.push({ ecommerce: null });
      dataLayer.push({ event: event, ecommerce: window['ps_datalayer_' + productId] });
    }
  }
}

$(document).ready(function () {
  var selectItems = $('[data-ps-track-event="select_item"]');
  var selectPromotions = $('[data-ps-track-event="select_promotion"]');

  function linkClick(e) {
    e.preventDefault();

    var self = $(this);
    var productId = self.data("ps-track-id");

    sendGAData('select_item', productId);

    setTimeout(function () {
      location = location = self.attr("href");
    }, 200);
  }

  if (selectItems.length > 0) {
    selectItems.on("click", linkClick);
  }

  if (selectPromotions.length > 0) {
    selectPromotions.on("click", linkClick);
  }

  $(document).on("click", '[data-ps-track-event="remove_from_cart"]', function (e) {
    e.preventDefault();

    var self = $(this);
    var productId = self.data("ps-track-id");

    self.removeAttr("data-ps-track-event"); // prevent invoking this function again

    sendGAData('select_item', productId);

    setTimeout(function () {
      self.trigger("click");
    }, 200);
  });
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
        requestUrl.indexOf("route=account/wishlist.add") > 0 &&
        productId &&
        typeof data === "object" &&
        "success" in data
      ) {
        sendGAData('add_to_wishlist', productId);
      }

      if (
        requestUrl.indexOf("route=checkout/cart.add") > 0 &&
        productId &&
        typeof data === "object" &&
        "success" in data
      ) {
        sendGAData('add_to_cart', productId);
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
