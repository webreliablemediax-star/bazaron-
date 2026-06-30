<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\SellerPage;
use App\Models\SellerPageFeature;
use App\Models\SellerPageStep;
use App\Models\SellerPageWhyChoose;
use App\Models\SellerPageWhyChoosePoint;
use App\Models\SellerPagePricing;
use App\Models\SellerPageDocumentation;
use App\Models\SellerPageDocumentationPoint;

class SellerPageController extends Controller
{

    public function sellerPage()
    {
        $sellerPage = SellerPage::first();
        $features = SellerPageFeature::orderBy('display_order')->get();
        $steps = SellerPageStep::orderBy('step_number')->get();
        $whyChoose = SellerPageWhyChoose::first();
        $whyPoints = SellerPageWhyChoosePoint::orderBy('display_order')->get();
        $pricing = SellerPagePricing::orderBy('display_order')->get();
        $documentation = SellerPageDocumentation::first();
$documentationPoints = SellerPageDocumentationPoint::orderBy('display_order')->get();

        return view('frontend.default.pages.sellerPage', compact(
            'sellerPage',
            'features',
            'steps',
            'whyChoose',
            'whyPoints',
            'pricing',
            'documentation',
            'documentationPoints'
        ));
    }

}