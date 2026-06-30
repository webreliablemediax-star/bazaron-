<script>
   "use strict";
   
   // runs when the document is ready --> for media files
   $(document).ready(function() {
       getChosenFilesCount();
       showSelectedFilePreviewOnLoad();
   });
   
   // swith markup based on selection
   function isVariantProduct(el) {
       $(".hasVariation").hide();
       $(".noVariation").hide();
   
       if ($(el).is(':checked')) {
           $(".hasVariation").show();
   
           // remove required field for non variations
           $("#price").removeAttr('required', true);
           $("#stock").removeAttr('required', true);
           $("#sku").removeAttr('required', true);
           $("#code").removeAttr('required', true);
   
       } else {
           $(".noVariation").show();
   
           // add required field for non variations 
           $("#price").attr('required', true);
           $("#stock").attr('required', true);
           $("#sku").attr('required', true);
           $("#code").attr('required', true);
       }
   }
   
   // add another variation
   function addAnotherVariation() {
       $.ajax({
           type: "POST",
           headers: {
               'X-CSRF-TOKEN': '{{ csrf_token() }}'
           },
           data: $('#product-form').serialize(),
           url: '{{ route('product.newVariation') }}',
           success: function(data) {
               if (data.count > 0) {
                   $('.chosen_variation_options').find('.variation-names').find('.select2').siblings(
                       '.dropdown-toggle').addClass("disabled");
                   $('.chosen_variation_options').append(data.view);
                   $('.select2').select2();
                   initFeather();
               }
           }
       });
   }
   
   // get values for selected variations
   function getVariationValues(e) {
       $.ajax({
           headers: {
               'X-CSRF-TOKEN': '{{ csrf_token() }}'
           },
           type: "POST",
           data: {
               variation_id: $(e).val()
           },
           url: '{{ route('product.getVariationValues') }}',
           success: function(data) {
               $(e).closest('.row').find('.variationvalues').html(data);
               $('.select2').select2();
               initFeather();
           }
       });
   }
   
   function loadExistingVariations() {
   
   if (!preSelectedVariations || Object.keys(preSelectedVariations).length === 0) return;
   
   let keys = Object.keys(preSelectedVariations);
   
   function process(index) {
   
       if (index >= keys.length) return;
   
       let variationId = keys[index];
   
       addAnotherVariation();
   
       setTimeout(() => {
   
           let rows = document.querySelectorAll('.chosen_variation_options .row');
           let currentRow = rows[rows.length - 1];
   
           let select = currentRow.querySelector('select');
   
           if (select) {
               select.value = variationId;
               $(select).trigger('change');
           }
   
           setTimeout(() => {
   
               let values = preSelectedVariations[variationId];
   
               let multiSelect = currentRow.querySelector('select[name^="option_"]');
   
               if (multiSelect) {
                   $(multiSelect).val(values).trigger('change');
               }
   
               generateVariationCombinations();
   
               // 🔥 NEXT STEP (IMPORTANT)
               process(index + 1);
   
           }, 800);
   
       }, 800);
   }
   
   process(0);
   }
   
   
   
   // variation combinations
 function generateVariationCombinations() {

    $('#variation_combination').html('<div class="text-center p-3">Loading...</div>');

    let form = document.getElementById('product-form');
let formData = new FormData(form);


    console.log("FINAL SENDING 👉", formData);

    $.ajax({
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        url: '{{ route('product.generateVariationCombinations') }}',
        data: formData,
        processData: false,   // 🔥 MUST
        contentType: false,   // 🔥 MUST

        success: function(data) {

            console.log("DATA 👉", data);

            $('#variation_combination').html(data); // 🔥 IMPORTANT FIX

            $('.table').footable();
            initFeather();

            setTimeout(() => {
                $('.select2').select2();
            }, 200);
        }
    });
}
   
   document.addEventListener("DOMContentLoaded", function () {
   setTimeout(() => {
       loadExistingVariations();
   }, 800);
   });
   $(document).on('change', 'select[name="chosen_variations[]"]', function () {
    console.log("🔥 variation changed");
    generateVariationCombinations();
});

$(document).on('change', '.variationvalues select', function () {
    console.log("🔥 values changed");
    generateVariationCombinations();
});
</script>