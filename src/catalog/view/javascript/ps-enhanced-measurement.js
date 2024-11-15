
$(document).on('click', '[data-ps-track-event]', function (e) {
    e.preventDefault();

    var self = $(this);
    var elementType = self.prop("nodeName");
    var productId = self.data("ps-track-id");
    var eventName = self.data("ps-track-event");

    self.removeAttr("data-ps-track-event");

    if (typeof productId !== 'undefined') {
      ps_dataLayer.push_manuall(eventName, productId);
    }

    setTimeout(function () {
      if (elementType === 'BUTTON') {
        self.trigger("click");
      } else if (elementType === 'A') {
        location = self.attr("href");
      }
    }, 500);
});
