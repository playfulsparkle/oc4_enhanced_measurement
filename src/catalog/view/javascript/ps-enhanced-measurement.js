
$(document).on('click', '[data-ps-track-event]', function (e) {
  e.preventDefault();

  var self = $(this);
  var elementType = self.prop("nodeName");
  var trackId = self.data("ps-track-id");
  var eventName = self.data("ps-track-event");

  self.removeAttr("data-ps-track-event").prop('disabled', true);

  if (typeof trackId !== 'undefined') {
    if (eventName === 'update_cart') {
      var trackData = ps_dataLayer.getData(trackId);

      var quantityObj = $('#product-quantity-' + trackId);
      var newQuantity = parseInt(quantityObj.val());

      if (newQuantity > 0) {
        var dataQuantity = trackData.ecommerce.items[0].quantity;
        var dataPrice = trackData.ecommerce.items[0].price;

        dataPrice /= dataQuantity;

        trackData.ecommerce.value = dataPrice * newQuantity;
        trackData.ecommerce.items[0].price = dataPrice * newQuantity;
        trackData.ecommerce.items[0].quantity = newQuantity;

        eventName = 'add_to_cart';
      } else {
        eventName = 'remove_from_cart';
      }

      ps_dataLayer.push(eventName, trackData);
    } else {
      ps_dataLayer.push_manuall(eventName, trackId);
    }
  }

  setTimeout(function () {
    if (elementType === 'BUTTON') {
      self.prop('disabled', false).trigger("click");
    } else if (elementType === 'A') {
      location = self.attr("href");
    }
  }, 800);
});
