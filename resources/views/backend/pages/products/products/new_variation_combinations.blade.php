@if (count($combinations[0]) > 0)
    <div class="border bg-light-subtle rounded p-2">
        <table class="table tt-footable tt-footable-border-0">
            <thead>
                <tr>
                    <th>
                        <label for="" class="control-label">{{ localize('Variation') }}</label>
                    </th>
                    <th data-breakpoints="xs sm">
                        <label for="" class="control-label">{{ localize('Price') }}</label>
                    </th>
                    <th data-breakpoints="xs sm">
                        <label for="" class="control-label">{{ localize('Stock') }} <small
                                class="text-warning">({{ localize('Default Location') }})</small></label>
                    </th>
                    <th data-breakpoints="xs sm">
                        <label for="" class="control-label">{{ localize('SKU') }}</label>
                    </th>
                    <!-- <th data-breakpoints="xs sm">
               <label class="control-label">
                   {{ localize('HSN Code') }} <span class="text-danger">*</span>
               </label>
               </th> -->
                    <th data-breakpoints="xs sm">
                        <label class="control-label">Image</label>
                    </th>
                </tr>
            </thead>
            <tbody>

                @foreach ($combinations as $key => $combination)
                    @php
                        $name = '';
                        $variation_key = '';
                        $lstKey = array_key_last($combination);
                        foreach ($combination as $option_id => $choice_id) {
                            $option_name = \App\Models\Variation::find($option_id)->collectLocalization('name');
                            $choice_name = \App\Models\VariationValue::find($choice_id)->collectLocalization('name');
                            $name .= $choice_name;
                            $variation_key .= $option_id . ':' . $choice_id . '/';
                            if ($lstKey != $option_id) {
                                $name .= '-';
                            }
                        }
                    @endphp
                    <tr class="variant">
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <!-- 🔥 hidden (for unchecked case) -->
                                <!-- <input type="hidden" name="variations[{{ $key }}][is_active]" value="0"> -->
                                <!-- ✅ checkbox -->
                                <!-- <input type="checkbox"
                     name="variations[{{ $key }}][is_active]"
                     value="1"
                     checked
                     class="form-check-input variation-checkbox"> -->
                                <!-- name -->
                                <input type="text" value="{{ $name }}" class="form-control" disabled>
                            </div>
                            <!-- Hidden key (same as before) -->
                            <input type="hidden" value="{{ $variation_key }}"
                                name="variations[{{ $key }}][variation_key]">
                        </td>
                        <td>
                            <input type="number" step="0.01" name="variations[{{ $key }}][price]"
                                value="{{ old('variations.' . $key . '.price', optional($existingVariations[$variation_key] ?? null)->price) }}"
                                min="0" class="form-control" required>
                        </td>
                        <td>
                            <input type="number" name="variations[{{ $key }}][stock]"
                                value="{{ old(
                                    'variations.' . $key . '.stock',
                                    optional(optional($existingVariations[$variation_key] ?? null)->product_variation_stock)->stock_qty,
                                ) }}"
                                min="0" class="form-control" required>
                        </td>
                        <td>
                            <input type="text" name="variations[{{ $key }}][sku]"
                                value="{{ $name }}" class="form-control">
                        </td>
                        <!-- <td>
               <input type="text"
               name="variations[{{ $key }}][code]"
               value=""
               class="form-control"
               maxlength="8"
               pattern="\d{1,8}"
               inputmode="numeric"
               required>
               </td> -->
                        <td>

                            @php
                                $image = optional($existingVariations[$variation_key] ?? null)->image;
                            @endphp

                            <div class="avatar avatar-xl cursor-pointer choose-media" data-bs-toggle="offcanvas"
                                data-bs-target="#offcanvasBottom" onclick="showMediaManager(this)"
                                data-selection="single">

                                <input type="hidden" name="variation_gallery[{{ $variation_key }}]"
                                    value="{{ $image }}">

                                @if ($image)
                                    <img src="{{ uploadedAsset($image) }}" class="rounded"
                                        style="
width:80px;
height:80px;
object-fit:cover;
">
                                @else
                                    <div class="no-avatar rounded-circle">
                                        <span>
                                            <i data-feather="plus"></i>
                                        </span>
                                    </div>
                                @endif

                            </div>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
