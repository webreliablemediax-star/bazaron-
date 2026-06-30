function initFlipkartZoom() {

  $('.zoomContainer').remove();
  $('#mainProductImage').removeData('elevateZoom');

  $('#mainProductImage').elevateZoom({
    zoomType: "window",
    zoomWindowPosition: 1,
    zoomWindowWidth: 450,
    zoomWindowHeight: 450,
    zoomWindowOffsetX: 20,
    borderSize: 1,
    cursor: "crosshair"
  });
}

$(document).ready(function () {

  if (window.innerWidth > 991) {
    initFlipkartZoom();
  }

  $('.thumb-img').first().addClass('active');
});

$(document).on('mouseenter', '.thumb-img', function () {

  if (window.innerWidth < 992) return;

  let img = $(this).data('full');
  let mainImg = $('#mainProductImage');
  let ez = mainImg.data('elevateZoom');

  $('.thumb-img').removeClass('active');
  $(this).addClass('active');

  if (ez) {
    ez.swaptheimage(img, img);
  } else {
    mainImg
      .attr('src', img)
      .attr('data-zoom-image', img);

    initFlipkartZoom();
  }
});
