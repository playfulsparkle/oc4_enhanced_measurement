
$(document).on('click', '[data-ps-track-event]', function (e) {
  e.preventDefault();

  var self = $(this);
  var trackId = self.data("ps-track-id");
  var eventName = self.data("ps-track-event");

  if (typeof trackId === 'undefined') {
    console.error('No track ID found');
    return;
  } else if (typeof eventName === 'undefined') {
    console.error('No event name found');
    return;
  }

  self.removeAttr("data-ps-track-event").prop('disabled', true);

  if (eventName === 'update_cart') { // Handle shopping cart events
    var quantityObj = $('#product-quantity-' + trackId);
    var newQuantity = parseInt(quantityObj.val()) ?? 0;

    eventName = (newQuantity > 0) ? 'add_to_cart' : 'remove_from_cart';

    var trackData = ps_dataLayer.getData(eventName, trackId);

    if (eventName === 'add_to_cart') {
      var dataPrice = trackData.ecommerce.items[0].price;

      trackData.ecommerce.value = dataPrice * newQuantity;
      trackData.ecommerce.items[0].quantity = newQuantity;
    }

    ps_dataLayer.pushData(eventName, trackData);
  } else {
    ps_dataLayer.push(eventName, trackId);
  }

  var elementType = self.prop("nodeName");

  setTimeout(function () {
    if (elementType === 'BUTTON') {
      self.prop('disabled', false).trigger("click").attr("data-ps-track-event", eventName);
    } else if (elementType === 'A') {
      location = self.attr("href");
    }
  }, 800);
});
