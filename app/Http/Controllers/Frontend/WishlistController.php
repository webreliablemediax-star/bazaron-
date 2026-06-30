<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{

    # customer's wishlist
    public function index()
    {
        $wishlist = auth()->user()->wishlist;
        return getView('pages.users.wishlist', ['wishlist' => $wishlist]);
    }

    # add to wishlist
   public function store(Request $request)
{
    $userId = auth()->id();

    // product id safely get
    $productId = $request->input('product_id');

    $exists = Wishlist::where('user_id', $userId)
        ->where('product_id', $productId)
        ->exists();

    if (!$exists) {

        $wishlist = new Wishlist();
        $wishlist->user_id = $userId;
        $wishlist->product_id = $productId;
        $wishlist->save();

    }

    return response()->json([
        'success' => true,
        'message' => localize("Product added to your wishlist")
    ]);
}

    # delete wishlist
    public function delete($id)
    {
        try {
            auth()->user()->wishlist()->where('id', $id)->delete();
        } catch (\Throwable $th) {
            //throw $th;
        }

        flash(localize('Product has been removed from your wishlist'))->success();
        return back();
    }
}
