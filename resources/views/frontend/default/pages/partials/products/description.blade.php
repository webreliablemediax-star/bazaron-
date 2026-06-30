<div class="product-info-tab bg-white rounded-2 overflow-hidden pt-6 mt-4">
   {{-- ================= DESCRIPTION FIRST ================= --}}
   <div class="px-2 py-2 border-bottom">
      <h4 class="fw-bold m-3 dsc">Description</h4>
      @if ($product->description)
      {!! $product->collectLocalization('description') !!}
      @else
      <div class="text-dark text-center border py-2">
         {{ localize('Not Available') }}
      </div>
      @endif
   </div>
   <div class="tab-content">
      <div class="tab-pane fade show active px-4 py-5" id="productinfo">
         <!-- FIXED PERFECT ALIGNMENT ROW -->
         <div class="row g-4 align-items-stretch product-info-flex" style="margin-top: -51px;">
            <!-- LEFT SIDE - PRODUCT INFO -->
            <div class="col-lg-6 d-flex flex-column">
               <!-- HEADING OUTSIDE -->
               <h3 class="info-title mb-4">Product Information</h3>
               <div class="info-box w-100 h-100" style="margin-top: -21px;">
                  <table class="w-100 product-info-table">
                     <!-- <tr>
                        <td class="fw-semibold">Brand</td>
                        <td>{{ $product->brand->name ?? 'N/A' }}</td>
                        </tr> -->
                     @php
                     $productInfos = is_array($product->product_info ?? null) 
                     ? $product->product_info 
                     : json_decode($product->product_info ?? '[]', true);
                     @endphp
                     @if(!empty($productInfos))
                     @foreach($productInfos as $info)
                     @if(!empty($info['title']) || !empty($info['value']))
                     <tr>
                        <td class="fw-semibold">
                           {{ $info['title'] ?? '-' }}
                        </td>
                        <td style="white-space: normal; word-break: break-word;">
                           {{ $info['value'] ?? '-' }}
                        </td>
                     </tr>
                     @endif
                     @endforeach
                     @endif
                  </table>
               </div>
            </div>
            <!-- RIGHT SIDE - ADDITIONAL INFO (bazaron STYLE) -->
            <div class="col-lg-6 d-flex flex-column">
               <!-- HEADING OUTSIDE (SAME AS PRODUCT INFO) -->
               <h3 class="info-title mb-4">
                  {{ localize('Additional Information') }}
               </h3>
               <!-- TABLE BOX -->
               <div class="info-box w-100 h-100" style="margin-top: -21px;">
                  @php
                  $additionalInfos = $product->additional_info ?? [];
                  // 🔥 Safety & Compliance check
                  $hasSafetyData = 
                  !empty($product->country_of_origin) ||
                  !empty($product->manufacturer) ||
                  !empty($product->importer_name) ||
                  !empty($product->packer_details) ||
                  !empty($product->compliance_certification) ||
                  !empty($product->safety_information);
                  @endphp
                  @if(
                  (!empty($additionalInfos) && count($additionalInfos) > 0) || 
                  $hasSafetyData
                  )
                  <table class="w-100 product-info-table">
                     {{-- 🔹 EXISTING ADDITIONAL INFO (UNCHANGED PATTERN) --}}
                     @foreach($additionalInfos as $info)
                     @if(!empty($info['title']) && !empty($info['value']))
                     <tr>
                        <td class="fw-semibold">
                           {{ $info['title'] }}
                        </td>
                        <td>
                           {{ $info['value'] }}
                        </td>
                     </tr>
                     @endif
                     @endforeach
                     {{-- 🔥 bazaron STYLE ITEM DETAILS (SAFETY & COMPLIANCE MERGED) --}}
                     @if($hasSafetyData)
                     <tr>
                        {{-- <td colspan="2" class="fw-bold pt-3">
                           Item Details
                        </td> --}}
                     </tr>
                     @if(!empty($product->manufacturer))
                     <tr>
                        <td class="fw-semibold">Manufacturer</td>
                        <td>{{ $product->manufacturer }}</td>
                     </tr>
                     @endif
                     @if(!empty($product->importer_name))
                     <tr>
                        <td class="fw-semibold">Importer</td>
                        <td>{{ $product->importer_name }}</td>
                     </tr>
                     @endif
                     @if(!empty($product->packer_details))
                     <tr>
                        <td class="fw-semibold">Packer Details</td>
                        <td>{{ $product->packer_details }}</td>
                     </tr>
                     @endif
                     @if(!empty($product->country_of_origin))
                     <tr>
                        <td class="fw-semibold">Country of Origin</td>
                        <td>{{ $product->country_of_origin }}</td>
                     </tr>
                     @endif
                     @if(!empty($product->safety_information))
                     <tr>
                        <td class="fw-semibold">Safety Information</td>
                        <td>{{ $product->safety_information }}</td>
                     </tr>
                     @endif
                     @if(!empty($product->compliance_certification))
                     <tr>
                        <td class="fw-semibold">Compliance</td>
                        <td>{{ $product->compliance_certification }}</td>
                     </tr>
                     @endif
                     @endif
                  </table>
                  @else
                  <p class="text-muted mb-0">
                     No additional information available.
                  </p>
                  @endif
               </div>
            </div>
         </div>
      </div>
   </div>
</div>