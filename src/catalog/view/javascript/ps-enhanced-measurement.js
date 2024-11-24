function handleUpdateCartEvent(trackId) {
  var quantityObj = $('#product-quantity-' + trackId);

  if (quantityObj.length === 0) {
    return console.error('Cannot find product quantity input field for track ID:', trackId);
  }

  var eventName;

  var quantity = parseInt(quantityObj.val());

  if (isNaN(quantity)) {
    eventName = 'remove_from_cart';
  } else {
    eventName = (quantity > 0) ? 'add_to_cart' : 'remove_from_cart';
  }

  var trackData = ps_dataLayer.getData(eventName, trackId);

  if (!trackData) {
    return console.error('getData returned null for event:', eventName, 'and track ID:', trackId);
  }

  if (eventName === 'add_to_cart') {
    var dataPrice = trackData.ecommerce.items[0].price;

    trackData.ecommerce.value = dataPrice * quantity;
    trackData.ecommerce.items[0].quantity = quantity;
  }

  ps_dataLayer.pushEventData(eventName, trackData);
}

$(document).on('click', '[data-ps-track-event]', function (e) {
  e.preventDefault();

  var self = $(this);
  var trackId = self.data("ps-track-id");
  var eventName = self.data("ps-track-event");

  if (!trackId) {
    return console.error('No track ID found for the clicked element:', self);
  }
  if (!eventName) {
    return console.error('No event name found for the clicked element:', self);
  }

  self
    .removeAttr("data-ps-track-event")
    .prop('disabled', true);

  if (eventName === 'update_cart') {
    handleUpdateCartEvent(trackId);
  } else {
    ps_dataLayer.onClick(eventName, trackId);
  }

  var elementType = self.prop("nodeName");

  setTimeout(function () {
    if (elementType === 'BUTTON') {
      self
        .prop('disabled', false)
        .trigger("click")
        .attr("data-ps-track-event", eventName);
    } else if (elementType === 'A' && self.attr("href")) {
      location = self.attr("href");
    }
  }, ps_dataLayer.tracking_delay);
});
