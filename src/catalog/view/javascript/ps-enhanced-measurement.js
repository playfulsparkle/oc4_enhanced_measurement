function getParameterByName(name, data) {
  if (data) {
    var params = data.split("&");

    for (var i = 0; i < params.length; i++) {
      var param = params[i].split("=");

      if (param[0] === name) {
        return param[1] ? decodeURIComponent(param[1]) : "";
      }
    }
  }

  return null;
}

$(document).on("click", '[data-ps-track-event]', function (e) {
  if (typeof ps_enhanced_measurement !== "undefined" && typeof ps_enhanced_measurement.send === "function") {
    e.preventDefault();

    var self = $(this);
    var elementType = self.prop("nodeName");
    var productId = self.data("ps-track-id");
    var event = self.data("ps-track-event");

    self.removeAttr("data-ps-track-event");

    ps_enhanced_measurement.send(event, productId);

    setTimeout(function () {
      if (elementType === 'BUTTON') {
        self.trigger("click");
      } else if (elementType === 'A') {
        location = self.attr("href");
      }
    }, 200);
  } else {
    console.error('ps_enhanced_measurement is not defined');
  }
});

(function ($) {
  var originalAjax = $.ajax;

  $.ajax = function (options) {
    var requestUrl = options.url;
    var dataString = typeof options.data === "string" ? options.data : $.param(options.data);
    var productId = parseInt(getParameterByName("product_id", dataString)) || null;

    var jqXHR = originalAjax.call(this, options);

    jqXHR.done(function (json) {
      if (!(productId && typeof json === "object" && "success" in json)) {
        return;
      }

      if (requestUrl.indexOf("route=account/wishlist.add") !== -1) {
        if (typeof ps_enhanced_measurement !== "undefined" && typeof ps_enhanced_measurement.send === "function") {
          ps_enhanced_measurement.send('add_to_wishlist', productId);
        }
      }

      if (requestUrl.indexOf("route=checkout/cart.add") !== -1) {
        if (typeof ps_enhanced_measurement !== "undefined" && typeof ps_enhanced_measurement.send === "function") {
          ps_enhanced_measurement.send('add_to_cart', productId);
        }
      }
    });

    return jqXHR;
  };
})(jQuery);
