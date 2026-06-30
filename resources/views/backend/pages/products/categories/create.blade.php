@extends('backend.layouts.master')
@section('title')
{{ localize('Add New Category') }} {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
@endsection
@section('contents')
<section class="tt-section pt-4">
   <div class="container">
      <div class="row mb-3">
         <div class="col-12">
            <div class="card tt-page-header">
               <div class="card-body d-lg-flex align-items-center justify-content-lg-between">
                  <div class="tt-page-title">
                     <h2 class="h5 mb-lg-0">{{ localize('Add Category') }}</h2>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="row mb-4 g-4">
         <!--left sidebar-->
         <div class="col-xl-9 order-2 order-md-2 order-lg-2 order-xl-1">
            <form action="{{ route('admin.categories.store') }}" method="POST" class="pb-650">
               @csrf
               <!--basic information start-->
               <div class="card mb-4" id="section-1">
                  <div class="card-body">
                     <h5 class="mb-4">{{ localize('Basic Information') }}</h5>
                     <div class="mb-4">
                        <label for="name" class="form-label">{{ localize('Category Name') }}</label>
                        <input class="form-control" type="text" id="name"
                           placeholder="{{ localize('Type your category name') }}" name="name" required>
                     </div>
                    
                     <div class="mb-4">
                        <label for="parent_id" class="form-label">{{ localize('Base Category') }}</label>
                       @php
                                        function renderCategoryOptions($category, $prefix = '')
                                        {
                                            echo '<option value="' .
                                                $category->id .
                                                '">' .
                                                $prefix .
                                                $category->collectLocalization('name') .
                                                '</option>';

                                            foreach ($category->childrenCategories as $child) {
                                                renderCategoryOptions(
                                                    $child,
                                                    $prefix . $category->collectLocalization('name') . ' > ',
                                                );
                                            }
                                        }
                                    @endphp

                                    <select class="form-control select2" name="parent_id">

                                        <option value="0"> - </option>

                                        @foreach ($categories as $category)
                                            @php
                                                renderCategoryOptions($category);
                                            @endphp
                                        @endforeach

                                    </select>
                        <!-- ✅ YAHAN DALNA HAI -->
                        <div class="mt-2">
                           <small class="text-muted" id="categoryBreadcrumb">
                           No category selected
                           </small>
                        </div>
                     </div>
                     <div class="mb-4">
                        <label class="form-label">{{ localize('Brands') }}</label>
                        <select class="form-control select2" name="brand_ids[]" class="w-100"
                           data-toggle="select2" data-placeholder="{{ localize('Select brands') }}" multiple>
                           @foreach ($brands as $brand)
                           <option value="{{ $brand->id }}">
                              {{ $brand->collectLocalization('name') }}
                           </option>
                           @endforeach
                        </select>
                     </div>
                     <div class="mb-4">
                        <label for="sorting_order_level"
                           class="form-label">{{ localize('Sorting Priority Number') }}</label>
                        <input class="form-control" type="number" id="sorting_order_level"
                           placeholder="{{ localize('Type sorting priority number') }}"
                           name="sorting_order_level">
                     </div>
                  </div>
               </div>
               <!--basic information end-->
               <!-- Commission Percentage -->
               <div class="mb-4">
                  <label for="commission_percentage" class="form-label">{{ localize('Commission (%)') }}</label>
                  <input type="number" step="0.01" min="0" max="100" class="form-control" id="commission_percentage" name="commission_percentage" value="{{ old('commission_percentage', 0) }}">
                  <span class="fs-sm text-muted">{{ localize('Set the commission percentage for this category') }}</span>
               </div>
               <!--product image and gallery start-->
               <div class="card mb-4" id="section-2">
                  <div class="card-body">
                     <h5 class="mb-4">{{ localize('Images') }}</h5>
                     <div class="mb-4">
                        <label class="form-label">{{ localize('Thumbnail') }}</label>
                        <div class="tt-image-drop rounded">
                           <span class="fw-semibold">{{ localize('Choose Category Thumbnail') }}</span>
                           <!-- choose media -->
                           <div class="tt-product-thumb show-selected-files mt-3">
                              <div class="avatar avatar-xl cursor-pointer choose-media"
                                 data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom"
                                 onclick="showMediaManager(this)" data-selection="single">
                                 <input type="hidden" name="image">
                                 <div class="no-avatar rounded-circle">
                                    <span><i data-feather="plus"></i></span>
                                 </div>
                              </div>
                           </div>
                           <!-- choose media -->
                        </div>
                     </div>
                  </div>
               </div>
               <!--product image and gallery end-->
               <!-- Category Landing Banners -->
               <div class="card mb-4">
                  <div class="card-body">
                     <h5 class="mb-4">Category Landing Banners</h5>
                     {{-- Banner 1 --}}
                     <div class="mb-4">
                        <label class="form-label">Banner 1</label>
                        <div class="tt-image-drop rounded">
                           <div class="tt-product-thumb show-selected-files mt-3">
                              <div class="avatar avatar-xl cursor-pointer choose-media"
                                 data-bs-toggle="offcanvas"
                                 data-bs-target="#offcanvasBottom"
                                 onclick="showMediaManager(this)"
                                 data-selection="single">
                                 <input type="hidden" name="banner_image_1">
                                 <div class="no-avatar rounded-circle">
                                    <span><i data-feather="plus"></i></span>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!-- ✅ FIX -->
                        <input type="text" name="banner_link_1"
                           class="form-control mt-3"
                           placeholder="Banner 1 Link (optional)">
                     </div>
                     {{-- Banner 2 --}}
                     <div class="mb-4">
                        <label class="form-label">Banner 2</label>
                        <div class="tt-image-drop rounded">
                           <div class="tt-product-thumb show-selected-files mt-3">
                              <div class="avatar avatar-xl cursor-pointer choose-media"
                                 data-bs-toggle="offcanvas"
                                 data-bs-target="#offcanvasBottom"
                                 onclick="showMediaManager(this)"
                                 data-selection="single">
                                 <input type="hidden" name="banner_image_2">
                                 <div class="no-avatar rounded-circle">
                                    <span><i data-feather="plus"></i></span>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!-- ✅ FIX -->
                        <input type="text" name="banner_link_2"
                           class="form-control mt-3"
                           placeholder="Banner 2 Link (optional)">
                     </div>
                     {{-- Banner 3 --}}
                     <div class="mb-4">
                        <label class="form-label">Banner 3</label>
                        <div class="tt-image-drop rounded">
                           <div class="tt-product-thumb show-selected-files mt-3">
                              <div class="avatar avatar-xl cursor-pointer choose-media"
                                 data-bs-toggle="offcanvas"
                                 data-bs-target="#offcanvasBottom"
                                 onclick="showMediaManager(this)"
                                 data-selection="single">
                                 <input type="hidden" name="banner_image_3">
                                 <div class="no-avatar rounded-circle">
                                    <span><i data-feather="plus"></i></span>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!-- ✅ FIX -->
                        <input type="text" name="banner_link_3"
                           class="form-control mt-3"
                           placeholder="Banner 3 Link (optional)">
                     </div>
                     {{-- Banner 4 --}}
                     <div class="mb-4">
                        <label class="form-label">Banner 4</label>
                        <div class="tt-image-drop rounded">
                           <div class="tt-product-thumb show-selected-files mt-3">
                              <div class="avatar avatar-xl cursor-pointer choose-media"
                                 data-bs-toggle="offcanvas"
                                 data-bs-target="#offcanvasBottom"
                                 onclick="showMediaManager(this)"
                                 data-selection="single">
                                 <input type="hidden" name="banner_image_4">
                                 <div class="no-avatar rounded-circle">
                                    <span><i data-feather="plus"></i></span>
                                 </div>
                              </div>
                           </div>
                           <input type="text" name="banner_link_4"
                              class="form-control mt-3"
                              placeholder="Banner 4 Link (optional)">
                        </div>
                     </div>
                     {{-- Banner 5 --}}
                     <div class="mb-4">
                        <label class="form-label">Banner 5</label>
                        <div class="tt-image-drop rounded">
                           <div class="tt-product-thumb show-selected-files mt-3">
                              <div class="avatar avatar-xl cursor-pointer choose-media"
                                 data-bs-toggle="offcanvas"
                                 data-bs-target="#offcanvasBottom"
                                 onclick="showMediaManager(this)"
                                 data-selection="single">
                                 <input type="hidden" name="banner_image_5">
                                 <div class="no-avatar rounded-circle">
                                    <span><i data-feather="plus"></i></span>
                                 </div>
                              </div>
                           </div>
                           <input type="text" name="banner_link_5"
                              class="form-control mt-3"
                              placeholder="Banner 5 Link (optional)">
                        </div>
                     </div>
                     {{-- Banner 6 --}}
                     <div class="mb-4">
                        <label class="form-label">Banner 6</label>
                        <div class="tt-image-drop rounded">
                           <div class="tt-product-thumb show-selected-files mt-3">
                              <div class="avatar avatar-xl cursor-pointer choose-media"
                                 data-bs-toggle="offcanvas"
                                 data-bs-target="#offcanvasBottom"
                                 onclick="showMediaManager(this)"
                                 data-selection="single">
                                 <input type="hidden" name="banner_image_6">
                                 <div class="no-avatar rounded-circle">
                                    <span><i data-feather="plus"></i></span>
                                 </div>
                              </div>
                           </div>
                           <input type="text" name="banner_link_6"
                              class="form-control mt-3"
                              placeholder="Banner 6 Link (optional)">
                        </div>
                     </div>
                     {{-- Banner 7 --}}
                     <div class="mb-4">
                        <label class="form-label">Banner 7</label>
                        <div class="tt-image-drop rounded">
                           <div class="tt-product-thumb show-selected-files mt-3">
                              <div class="avatar avatar-xl cursor-pointer choose-media"
                                 data-bs-toggle="offcanvas"
                                 data-bs-target="#offcanvasBottom"
                                 onclick="showMediaManager(this)"
                                 data-selection="single">
                                 <input type="hidden" name="banner_image_7">
                                 <div class="no-avatar rounded-circle">
                                    <span><i data-feather="plus"></i></span>
                                 </div>
                              </div>
                           </div>
                           <input type="text" name="banner_link_7"
                              class="form-control mt-3"
                              placeholder="Banner 7 Link (optional)">
                        </div>
                     </div>
                     {{-- Banner 8 --}}
                     <div class="mb-4">
                        <label class="form-label">Banner 8</label>
                        <div class="tt-image-drop rounded">
                           <div class="tt-product-thumb show-selected-files mt-3">
                              <div class="avatar avatar-xl cursor-pointer choose-media"
                                 data-bs-toggle="offcanvas"
                                 data-bs-target="#offcanvasBottom"
                                 onclick="showMediaManager(this)"
                                 data-selection="single">
                                 <input type="hidden" name="banner_image_8">
                                 <div class="no-avatar rounded-circle">
                                    <span><i data-feather="plus"></i></span>
                                 </div>
                              </div>
                           </div>
                           <input type="text" name="banner_link_8"
                              class="form-control mt-3"
                              placeholder="Banner 8 Link (optional)">
                        </div>
                     </div>
                     {{-- Banner 9 --}}
                     <div class="mb-4">
                        <label class="form-label">Banner 9</label>
                        <div class="tt-image-drop rounded">
                           <div class="tt-product-thumb show-selected-files mt-3">
                              <div class="avatar avatar-xl cursor-pointer choose-media"
                                 data-bs-toggle="offcanvas"
                                 data-bs-target="#offcanvasBottom"
                                 onclick="showMediaManager(this)"
                                 data-selection="single">
                                 <input type="hidden" name="banner_image_9">
                                 <div class="no-avatar rounded-circle">
                                    <span><i data-feather="plus"></i></span>
                                 </div>
                              </div>
                           </div>
                           <input type="text" name="banner_link_9"
                              class="form-control mt-3"
                              placeholder="Banner 9 Link (optional)">
                        </div>
                     </div>
                     {{-- Banner 10 --}}
                     <div class="mb-4">
                        <label class="form-label">Banner 10</label>
                        <div class="tt-image-drop rounded">
                           <div class="tt-product-thumb show-selected-files mt-3">
                              <div class="avatar avatar-xl cursor-pointer choose-media"
                                 data-bs-toggle="offcanvas"
                                 data-bs-target="#offcanvasBottom"
                                 onclick="showMediaManager(this)"
                                 data-selection="single">
                                 <input type="hidden" name="banner_image_10">
                                 <div class="no-avatar rounded-circle">
                                    <span><i data-feather="plus"></i></span>
                                 </div>
                              </div>
                           </div>
                           <input type="text" name="banner_link_10"
                              class="form-control mt-3"
                              placeholder="Banner 10 Link (optional)">
                        </div>
                     </div>
                  </div>
               </div>
               <!--seo meta description start-->
               <div class="card mb-4" id="section-10">
                  <div class="card-body">
                     <h5 class="mb-4">{{ localize('SEO Meta Configuration') }}</h5>
                     <div class="mb-4">
                        <label for="meta_title" class="form-label">{{ localize('Meta Title') }}</label>
                        <input type="text" name="meta_title" id="meta_title"
                           placeholder="{{ localize('Type meta title') }}" class="form-control">
                        <span class="fs-sm text-muted">
                        {{ localize('Set a meta tag title. Recommended to be simple and unique.') }}
                        </span>
                     </div>
                     <div class="mb-4">
                        <label for="meta_description"
                           class="form-label">{{ localize('Meta Description') }}</label>
                        <textarea class="form-control" name="meta_description" id="meta_description" rows="4"
                           placeholder="{{ localize('Type your meta description') }}"></textarea>
                     </div>
                     <div class="mb-4">
                        <label class="form-label">{{ localize('Meta Image') }}</label>
                        <div class="tt-image-drop rounded">
                           <span class="fw-semibold">{{ localize('Choose Meta Image') }}</span>
                           <!-- choose media -->
                           <div class="tt-product-thumb show-selected-files mt-3">
                              <div class="avatar avatar-xl cursor-pointer choose-media"
                                 data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom"
                                 onclick="showMediaManager(this)" data-selection="single">
                                 <input type="hidden" name="meta_image">
                                 <div class="no-avatar rounded-circle">
                                    <span><i data-feather="plus"></i></span>
                                 </div>
                              </div>
                           </div>
                           <!-- choose media -->
                        </div>
                     </div>
                  </div>
               </div>
               <!--seo meta description end-->
               <!-- Category SEO Description -->
               <div class="card mb-4">
                  <div class="card-body">
                     <h5 class="mb-4">Category Description </h5>
                     <textarea name="description"
                        class="form-control summernote"
                        rows="8"
                        placeholder="Write detailed category description for SEO...">
            {{ old('description') }}
        </textarea>
                     <span class="fs-sm text-muted">
                     This content will appear at the bottom of category page (above footer).
                     </span>
                  </div>
               </div>
               <!-- submit button -->
               <div class="row">
                  <div class="col-12">
                     <div class="mb-4">
                        <button class="btn btn-primary" type="submit">
                        <i data-feather="save" class="me-1"></i> {{ localize('Save Category') }}
                        </button>
                     </div>
                  </div>
               </div>
               <!-- submit button end -->
            </form>
         </div>
         <!--right sidebar-->
         <div class="col-xl-3 order-1 order-md-1 order-lg-1 order-xl-2">
            <div class="card tt-sticky-sidebar d-none d-xl-block">
               <div class="card-body">
                  <h5 class="mb-4">{{ localize('Category Information') }}</h5>
                  <div class="tt-vertical-step">
                     <ul class="list-unstyled">
                        <li>
                           <a href="#section-1" class="active">{{ localize('Basic Information') }}</a>
                        </li>
                        <li>
                           <a href="#section-2">{{ localize('Category Image') }}</a>
                        </li>
                        <li>
                           <a href="#section-10">{{ localize('SEO Meta Options') }}</a>
                        </li>
                     </ul>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
@section('scripts')
<script>
   "use strict";
   
   $(document).ready(function () {
       if ($('.summernote').length > 0) {
           $('.summernote').summernote({
               height: 250,
               toolbar: [
                   ['style', ['style']],
                   ['font', ['bold', 'italic', 'underline', 'clear']],
                   ['fontname', ['fontname']],
                   ['para', ['ul', 'ol', 'paragraph']],
                   ['insert', ['link']],
                   ['view', ['codeview']]
               ]
           });
       }
   });
   
   
   
   const categories = @json($categories);
   
   function findCategoryPath(id, list, path = []) {
       for (let cat of list) {
   
           // match id
           if (cat.id == id) {
               return [...path, cat.name];
           }
   
           // 🔥 handle both cases
           let children = cat.childrenCategories || cat.children_categories;
   
           if (children && children.length > 0) {
               let result = findCategoryPath(id, children, [...path, cat.name]);
               if (result) return result;
           }
       }
       return null;
   }
   
   $(document).ready(function () {
   
       function updateBreadcrumb() {
           let selectedId = $('[name="parent_id"]').val();
   
           if (!selectedId || selectedId == 0) {
               $('#categoryBreadcrumb').text('No category selected');
               return;
           }
   
           let path = findCategoryPath(selectedId, categories);
   
           if (path) {
               $('#categoryBreadcrumb').text(path.join(' > '));
           } else {
               $('#categoryBreadcrumb').text('No category selected');
           }
       }
   
       // 🔥 IMPORTANT (select2 fix)
       $(document).on('change', '[name="parent_id"]', function () {
           updateBreadcrumb();
       });
        updateBreadcrumb(); 
   
   });
</script>
@endsection
@endsection