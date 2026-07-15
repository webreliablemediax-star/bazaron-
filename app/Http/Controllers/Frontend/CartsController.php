<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\ProductVariation;
use Illuminate\Http\Request;
use Auth;

class CartsController extends Controller
{

    # all cart items
    public function index()
    {
        $carts = null;

        if (Auth::check()) {
            $carts = Cart::where('user_id', Auth::user()->id)
                ->where('location_id', session('stock_location_id'))
                ->get();
        } else {
            $carts = Cart::where('guest_user_id', (int) $_COOKIE['guest_user_id'])
                ->where('location_id', session('stock_location_id'))
                ->get();
        }

        return getView('pages.checkout.carts', ['carts' => $carts]);
    }


    # add to cart
    public function store(Request $request)
    {

        $productVariation = ProductVariation::where('id', $request->product_variation_id)->first();

        if (!$productVariation) {
            return response()->json([
                'success' => false,
                'alert' => 'warning',
                'message' => 'Invalid product selected.'
            ]);
        }

      $product = $productVariation->product;

// 🔴 SELLER SELF-PURCHASE BLOCK (FINAL FIX)
if (Auth::check() && $product) {

    $user = Auth::user();

    // check if seller
   if (Auth::check() && in_array(Auth::user()->user_type, ['vendor','admin'])) {

    return response()->json([
        'success' => false,
        'alert' => 'warning',
        'message' => 'Please login with a customer account to buy products'
    ]);
}
}

        $cart = null;
        $message = '';

        if (Auth::check()) {

            $cart = Cart::where('user_id', Auth::user()->id)
                ->where('location_id', session('stock_location_id'))
                ->where('product_variation_id', $productVariation->id)
                ->first();

        } else {

            $cart = Cart::where('guest_user_id', (int) $_COOKIE['guest_user_id'])
                ->where('location_id', session('stock_location_id'))
                ->where('product_variation_id', $productVariation->id)
                ->first();
        }


        if (is_null($cart)) {

            $cart = new Cart;
            $cart->product_variation_id = $productVariation->id;
            $cart->vendor_id = $product->vendor_id;

            if ($request->quantity > $product->max_purchase_qty) {
                $cart->qty = (int) $product->max_purchase_qty;
            } else {
                $cart->qty = (int) $request->quantity;
            }

            $cart->location_id = session('stock_location_id');

            if (Auth::check()) {
                $cart->user_id = Auth::user()->id;
            } else {
                $cart->guest_user_id = (int) $_COOKIE['guest_user_id'];
            }

            $message = localize('Product added to your cart');

        } else {

            if ($product->max_purchase_qty > $cart->qty) {

                $cart->qty += (int) $request->quantity;
                $message = localize('Quantity has been increased');

            } else {

                $message = localize('You have reached maximum order quantity at a time for this product');

                return $this->getCartsInfo($message, true, '', 'warning');
            }
        }

        $cart->save();

        return $this->getCartsInfo($message, getCoupon() != '', getCoupon());
    }



    # update cart
    public function update(Request $request)
    {

        try {

            $cart = Cart::where('id', $request->id)->first();

            if ($request->action == "increase") {

                $product = $cart->product_variation->product;

                if ($product->max_purchase_qty > $cart->qty) {

                    $productVariationStock = $cart->product_variation->product_variation_stock;

                    if ($productVariationStock->stock_qty > $cart->qty) {

                        $cart->qty += 1;
                        $cart->save();
                    }

                } else {

                    $message = localize('You have reached maximum order quantity at a time for this product');

                    return $this->getCartsInfo($message, true, '', 'warning');
                }

            } elseif ($request->action == "decrease") {

                if ($cart->qty > 1) {
                    $cart->qty -= 1;
                    $cart->save();
                }

            } else {

                $cart->delete();
            }

        } catch (\Throwable $th) {
        }

        return $this->getCartsInfo('', getCoupon() != '', getCoupon());
    }



    # apply coupon
    public function applyCoupon(Request $request)
    {

        $coupon = Coupon::where('code', $request->code)->first();

        if ($coupon) {

            // Coupons are configured as dates, so compare at the start of the
            // current date. This also keeps previously saved end dates (00:00)
            // valid through their displayed end date.
            $date = strtotime(date('Y-m-d'));

            if ($date < $coupon->start_date) {
                removeCoupon();

                return $this->couponApplyFailed(
                    localize('Coupon will be active from') . ' ' . date('d M Y', (int) $coupon->start_date)
                );
            }

            if ($date > $coupon->end_date) {
                removeCoupon();

                return $this->couponApplyFailed(
                    localize('Coupon expired on') . ' ' . date('d M Y', (int) $coupon->end_date)
                );
            }

            $carts = null;

                if (Auth::check()) {
                    $carts = Cart::where('user_id', Auth::user()->id)
                        ->where('location_id', session('stock_location_id'))
                        ->get();
                } else {
                    $carts = Cart::where('guest_user_id', (int) $_COOKIE['guest_user_id'])
                        ->where('location_id', session('stock_location_id'))
                        ->get();
                }

                $subTotal = (float) getSubTotal($carts, false);

                if ($subTotal >= (float) $coupon->min_spend) {
                    // A coupon restricted to products/categories must be checked before
                    // saving it in the customer's cookie, not only at checkout.
                    if (($coupon->product_ids || $coupon->category_ids)
                        && !validateCouponForProductsAndCategories($carts, $coupon)) {
                        removeCoupon();

                        return $this->couponApplyFailed(
                            localize('Coupon is only applicable for selected products or categories')
                        );
                    }

                    setCoupon($coupon);

                    return $this->getCartsInfo(localize('Coupon applied successfully'), true, $coupon->code);

                } else {

                    removeCoupon();

                    return $this->couponApplyFailed('Please shop for atleast ' . formatPrice($coupon->min_spend));
                }

        }

        removeCoupon();

        return $this->couponApplyFailed(localize('Coupon is not valid'));
    }



    # coupon apply failed
    private function couponApplyFailed($message = '', $success = false)
    {

        $response = $this->getCartsInfo($message, false);
        $response['success'] = $success;

        return $response;
    }



    # clear coupon
    public function clearCoupon()
    {

        removeCoupon();

        return $this->couponApplyFailed(localize('Coupon has been removed'), true);
    }



    # get cart information
    private function getCartsInfo($message = '', $couponDiscount = true, $couponCode = '', $alert = 'success')
    {

        $couponCode = $couponCode ?: getCoupon();

        $carts = null;

        if (Auth::check()) {
            $carts = Cart::where('user_id', Auth::user()->id)
                ->where('location_id', session('stock_location_id'))
                ->get();
        } else {
            $carts = Cart::where('guest_user_id', (int) $_COOKIE['guest_user_id'])
                ->where('location_id', session('stock_location_id'))
                ->get();
        }

        return [

            'success' => true,
            'message' => $message,
            'alert' => $alert,

            'carts' => getViewRender('pages.partials.carts.cart-listing', ['carts' => $carts]),

            'navCarts' => getViewRender('pages.partials.carts.cart-navbar', ['carts' => $carts]),

            'cartCount' => count($carts),

            // Subtotal is always the cart amount before discount. The coupon
            // discount is returned separately so the cart summary stays clear.
            'subTotal' => formatPrice(getSubTotal($carts, false)),

            'couponDiscount' => formatPrice(getCouponDiscount(getSubTotal($carts, false), $couponCode)),

            'couponCode' => $couponCode,
        ];
    }
}
