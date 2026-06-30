<?php
namespace App\Http\Controllers\Backend;
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
// Seller page form open
public function index()
{
    $sellerPage = SellerPage::first();
    $features = SellerPageFeature::orderBy('display_order')->get();
    $steps = SellerPageStep::orderBy('step_number')->get();

    $whyChoose = SellerPageWhyChoose::first();
    $whyPoints = SellerPageWhyChoosePoint::orderBy('display_order')->get();

    $pricing = SellerPagePricing::orderBy('display_order')->get();

    // ADD THESE TWO
    $documentation = SellerPageDocumentation::first();

    $documentationPoints = SellerPageDocumentationPoint::orderBy('display_order')->get();

    return view('backend.pages.sellerpage.index', compact(
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
// Save Seller Page
public function store(Request $request)
{
// -------- SELLER PAGE --------
SellerPage::updateOrCreate(
['id' => 1],
[
'hero_title' => $request->hero_title,
'hero_subtitle' => $request->hero_subtitle,
'hero_button_text' => $request->hero_button_text,
'hero_button_link' => $request->hero_button_link,
'hero_image' => $request->hero_image,
'cta_title' => $request->cta_title,
'cta_description' => $request->cta_description,
'cta_button_text' => $request->cta_button_text,
'cta_button_link' => $request->cta_button_link,
'cta_background' => $request->cta_background,
'cta_image' => $request->cta_image,
'features_description' => $request->features_description,
'steps_description' => $request->steps_description,
'pricing_description' => $request->pricing_description,
]
);
// -------- FEATURES --------
if ($request->features) {
SellerPageFeature::truncate();
foreach ($request->features as $feature) {
SellerPageFeature::create([
'title' => $feature['title'] ?? null,
'description' => $feature['description'] ?? null,
'icon' => $feature['icon'] ?? null,
'display_order' => $feature['display_order'] ?? 0
]);
}
}
// -------- STEPS --------
if ($request->steps) {
SellerPageStep::truncate();
foreach ($request->steps as $step) {
SellerPageStep::create([
'step_number' => $step['step_number'] ?? 0,
'title' => $step['title'] ?? null,
'description' => $step['description'] ?? null,
'image' => $step['image'] ?? null
]);
}
}
// -------- WHY CHOOSE --------
SellerPageWhyChoose::updateOrCreate(
['id' => 1],
[
'section_title' => $request->why_choose_title,
'section_description' => $request->why_choose_description
]
);
if ($request->has('why_points')){
SellerPageWhyChoosePoint::truncate();
foreach ($request->why_points as $point) {
SellerPageWhyChoosePoint::create([
'title' => $point['title'] ?? null,
'display_order' => $point['display_order'] ?? 0
]);
}
}
// -------- PRICING --------
if ($request->pricing) {
SellerPagePricing::truncate();
$pricingTitle = $request->pricing_title;
foreach ($request->pricing as $row) {
SellerPagePricing::create([
'section_title' => $request->pricing_title,
'feature_name' => $row['feature_name'] ?? null,
'feature_value' => $row['feature_value'] ?? null,
'display_order' => $row['display_order'] ?? 0
]);
}
}
// -------- DOCUMENTATION --------
SellerPageDocumentation::updateOrCreate(
['id' => 1],
[
'section_title' => $request->documentation_title,
'section_description' => $request->documentation_description
]
);
if ($request->has('documentation_points')) {

    SellerPageDocumentationPoint::truncate();

    foreach ($request->documentation_points as $point) {

        SellerPageDocumentationPoint::create([
            'title'         => $point['title'] ?? null,
            'icon'          => $point['icon'] ?? null,
            'display_order' => $point['display_order'] ?? 0
        ]);

    }
}
return back()->with('success', 'Seller Page Updated Successfully');
}
}