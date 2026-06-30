<script src="{{ staticAsset('frontend/default/assets/js/vendors/jquery-3.6.4.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/elevatezoom/3.0.8/jquery.elevatezoom.min.js"></script>
<script src="{{ staticAsset('frontend/default/assets/js/vendors/jquery-ui.min.js') }}"></script>
<script src="{{ staticAsset('frontend/default/assets/js/vendors/bootstrap.bundle.min.js') }}"></script>
<script src="{{ staticAsset('frontend/default/assets/js/vendors/swiper-bundle.min.js') }}"></script>
<script src="{{ staticAsset('frontend/default/assets/js/vendors/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ staticAsset('frontend/default/assets/js/vendors/simplebar.min.js') }}"></script>
<script src="{{ staticAsset('frontend/default/assets/js/vendors/parallax-scroll.js') }}"></script>
<script src="{{ staticAsset('frontend/default/assets/js/vendors/isotop.pkgd.min.js') }}"></script>
<script src="{{ staticAsset('frontend/default/assets/js/vendors/countdown.min.js') }}"></script>
<script src="{{ staticAsset('frontend/default/assets/js/vendors/range-slider.js') }}"></script>
<script src="{{ staticAsset('frontend/default/assets/js/vendors/waypoints.js') }}"></script>
<script src="{{ staticAsset('frontend/default/assets/js/vendors/counterup.min.js') }}"></script>
<script src="{{ staticAsset('frontend/default/assets/js/vendors/clipboard.min.js') }}"></script>

<script src="{{ staticAsset('frontend/common/js/toastr.min.js') }}"></script>
<script src="{{ staticAsset('frontend/common/js/select2.js') }}"></script>
<script src="{{ staticAsset('frontend/default/assets/js/app.js') }}"></script>

<script>
"use strict";

// runs when the document is ready


// tooltip
$(function () {
    $('[data-bs-toggle="tooltip"]').tooltip();
});

// isotop filter grid 
function initIsotop() {
    var $filter_grid = $(".filter_group").isotope({});
    $(".filter-btns").on("click", "button", function () {
        var filterValue = $(this).attr("data-filter");
        $filter_grid.isotope({ filter: filterValue });
        $(this).parent().find("button.active").removeClass("active");
        $(this).addClass("active");
    });
}

// copy coupon code
$(function () {
    new ClipboardJS('.copy-text');
});
$(".copyBtn").each(function () {
    $(this).on("click", function () {
        $(this).html('{{ 'Copied' }}');
    });
});

// change language
function changeLocaleLanguage(e) {
    var locale = e.dataset.flag;
    $.post("{{ route('backend.changeLanguage') }}", {
        _token: '{{ csrf_token() }}',
        locale: locale
    }, function () {
        setTimeout(() => location.reload(), 300);
    });
}

// change currency
function changeLocaleCurrency(e) {
    var currency_code = e.dataset.currency;
    $.post("{{ route('backend.changeCurrency') }}", {
        _token: '{{ csrf_token() }}',
        currency_code: currency_code
    }, function () {
        setTimeout(() => location.reload(), 300);
    });
}

// change location
function changeLocation(e) {
    var location_id = e.dataset.location;
    $.post("{{ route('backend.changeLocation') }}", {
        _token: '{{ csrf_token() }}',
        location_id: location_id
    }, function () {
        setTimeout(() => location.reload(), 300);
    });
}

// show product details in modal
function showProductDetailsModal(productId) {
    $('#quickview_modal .product-info').html(null);
    $('.data-preloader-wrapper>div').addClass('spinner-border');
    $('.data-preloader-wrapper').addClass('min-h-400');
    $('#quickview_modal').modal('show');

    $.post('{{ route('products.showInfo') }}', {
        _token: '{{ csrf_token() }}',
        id: productId
    }, function (data) {
        setTimeout(() => {
            $('.data-preloader-wrapper>div').removeClass('spinner-border');
            $('.data-preloader-wrapper').removeClass('min-h-400');
            $('#quickview_modal .product-info').html(data);
            TT.ProductSliders();
            cartFunc();
            
        }, 200);
    });
}

$('#quickview_modal').on('hide.bs.modal', function () {
    $('#quickview_modal .product-info').html(null);
});




// cart func
function cartFunc() {
    $('.product-radio-btn input').on('change', function () {
        getVariationInfo();
    });
}
cartFunc();

// wishlist
function addToWishlist(productId) {
@if (auth()->check())
    $.post('{{ route('customers.wishlist.store') }}', {
        _token: '{{ csrf_token() }}',
        product_id: productId
    }, function (data) {
        notifyMe('success', data.message);
    });
@else
    notifyMe('warning', 'Please login first');
@endif
}
</script>
