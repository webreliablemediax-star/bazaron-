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
                <th data-breakpoints="xs sm">
    <label class="control-label">
        {{ localize('HSN Code') }} <span class="text-danger">*</span>
    </label>
</th>
                <th data-breakpoints="xs sm">
    <label class="control-label">Image</label>
</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($variations as $key => $variation)
    @php
        $name = '';
        $code_array = array_filter(explode('/', $variation->variation_key));
        $lstKey = array_key_last($code_array);

        foreach ($code_array as $key2 => $comb) {
            $comb = explode(':', $comb);

            // 🔥 SAFE FIND (NO CRASH)
            $variationModel = \App\Models\Variation::find($comb[0]);
            $variationValueModel = \App\Models\VariationValue::find($comb[1]);

            $option_name = $variationModel 
                ? $variationModel->collectLocalization('name') 
                : '';

            $choice_name = $variationValueModel 
                ? $variationValueModel->collectLocalization('name') 
                : '';

            $name .= $choice_name;

            if ($lstKey != $key2) {
                $name .= '-';
            }
        }
    @endphp


                <tr class="variant">
                    <td>
                        <input type="text" value="{{ $name }}" class="form-control" disabled>
                        <input type="hidden" value="{{ $variation->variation_key }}"
                            name="variations[{{ $key }}][variation_key]">
                    </td>
                    <td>
                        <input type="number" step="0.01" name="variations[{{ $key }}][price]"
                            min="0" class="form-control" value="{{ $variation->price }}" required>
                    </td>
                    <td>
                        <input type="number" name="variations[{{ $key }}][stock]"
                            value="{{ $variation->product_variation_stock ? $variation->product_variation_stock->stock_qty : 0 }}"
                            min="0" class="form-control" required>
                    </td>
                    <td>
                        <input type="text" name="variations[{{ $key }}][sku]"
                            value="{{ $variation->sku }}" value="SKU" class="form-control">
                    </td>
                    <td>
                       <input type="text" 
    name="variations[{{ $key }}][code]"
    value="{{ $variation->code }}"
    class="form-control"
    maxlength="8"
    pattern="\d{1,8}"
    inputmode="numeric"
     required>
                    </td>
                    <td>
    <input type="file"
           name="variations[{{ $key }}][image]"
           class="form-control"
           accept="image/*">
</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll('input[name*="[code]"]').forEach(function(input){

        input.addEventListener("input", function(){

            // only numbers
            this.value = this.value.replace(/\D/g, '');

            // max 8 digit
            if(this.value.length > 8){
                this.value = this.value.slice(0,8);
            }

        });

    });

});
</script>
