


@php
    // Gallery Images (safe explode)
    // 🔥 CLEAN GALLERY IMAGES (NO PLACEHOLDER, NO EMPTY, EXACT COUNT)
// 🔥 CLEAN + REMOVE DUPLICATE + REMOVE THUMBNAIL FROM GALLERY
$galleryImages = [];

if (!empty($product->gallery_images)) {
    $rawGallery = explode(',', $product->gallery_images);

    $galleryImages = array_values(array_filter($rawGallery, function ($img) use ($product) {
        return !empty($img)
            && $img !== 'null'
            && trim($img) !== ''
            && $img !== 'placeholder.png'
            && $img != $product->thumbnail_image; // ⭐ REMOVE DUPLICATE MAIN IMAGE
    }));

    // Extra safety: unique images only
    $galleryImages = array_unique($galleryImages);
}

    // ===== SMART ASSET HELPER (MEDIA ID + DIRECT UPLOAD BOTH SUPPORT) =====
    function productAsset($path) {
        if (empty($path)) {
            return asset('images/placeholder.png');
        }

        // If numeric → media manager ID
        if (is_numeric($path)) {
            return uploadedAsset($path);
        }

        // If already full URL
        if (str_starts_with($path, 'http')) {
            return $path;
        }

        // If direct uploads path
        if (str_starts_with($path, 'uploads')) {
            return asset($path);
        }

        // Fallback
        return asset($path);
    }

    // Video Data
    $videoUrl = trim($product->video_url ?? '');
    $videoType = strtolower($product->video_type ?? '');

    // Final video URL (FIX 404)
    $finalVideoUrl = !empty($videoUrl) && str_starts_with($videoUrl, 'uploads')
        ? asset($videoUrl)
        : $videoUrl;

    // Extract YouTube ID
    $videoId = null;
    if (!empty($videoUrl) && $videoType === 'youtube') {
        if (str_contains($videoUrl, 'watch?v=')) {
            $videoId = explode('watch?v=', $videoUrl)[1];
            $videoId = explode('&', $videoId)[0];
        } elseif (str_contains($videoUrl, 'youtu.be/')) {
            $videoId = explode('youtu.be/', $videoUrl)[1];
            $videoId = explode('?', $videoId)[0];
        }
    }
@endphp

<div class="row d-flex">

    {{-- ================= LEFT : THUMBNAILS ================= --}}
    <div class="col-md-3 product-thumbs pe-3">


    {{-- MAIN THUMB --}}
        @php
    $mainThumb = null;

    if (!empty($product->thumbnail_image) && $product->thumbnail_image !== 'null') {
        $mainThumb = $product->thumbnail_image;
    } elseif (!empty($galleryImages) && count($galleryImages) > 0) {
        $mainThumb = $galleryImages[0]; // fallback to first gallery image
    }
@endphp

@if($mainThumb)
    <img src="{{ productAsset($mainThumb) }}"
         class="thumb-img active"
         data-type="image"
         data-full="{{ productAsset($mainThumb) }}">
@endif

        {{-- GALLERY IMAGES --}}
        @foreach ($galleryImages as $img)
    <img src="{{ productAsset($img) }}"
         class="thumb-img"
         data-type="image"
         data-full="{{ productAsset($img) }}"
         alt="product-gallery">
@endforeach

      
        {{-- ⭐ VIDEO THUMB (UPLOAD + YOUTUBE SUPPORT) --}}
@if(!empty($videoUrl) && $videoType === 'youtube')

    @php
        $videoThumb = $videoId
            ? 'https://img.youtube.com/vi/'.$videoId.'/hqdefault.jpg'
            : asset('images/video-thumb.png'); // custom play icon image
    @endphp

    <img 
        src="{{ $videoThumb }}"
        class="thumb-img video-thumb"
        data-type="video"
        data-video="{{ $videoType === 'upload' ? $finalVideoUrl : $videoUrl }}"
        data-video-type="{{ $videoType }}">

@endif


<!-- {{-- ⭐ VIDEO MANAGER THUMB (TEMPORARY) --}}
@php
$adminVideo = DB::table('videos')->latest()->first();
@endphp

@if($adminVideo)

<img 
    src="{{ asset('images/video-thumb.png') }}"
    class="thumb-img video-thumb"
    data-type="video"
    data-video="{{ asset('uploads/videos/'.$adminVideo->video) }}"
    data-video-type="upload">

@endif -->

    </div>

    {{-- ================= CENTER : MAIN IMAGE ================= --}}
    <div class="col-md-4">
        <div class="image-box" id="imageBox">

            {{-- MAIN IMAGE --}}
            <img id="mainImage"
                 src="{{ productAsset($product->thumbnail_image) }}">

            {{-- PLAY ICON --}}
            <div id="videoPlayOverlay" class="video-play-overlay">
                ▶
            </div>

            {{-- HIDDEN VIDEO PLAYER --}}
            <video id="mainVideo"
                   controls
                   style="display:none;width:100%;height:100%;object-fit:contain;">
                <source src="">
            </video>

            <div class="lens" id="lens"></div>
        </div>
    </div>

    {{-- ================= RIGHT : ZOOM RESULT ================= --}}
    <div class="col-md-5">
        <div class="zoom-result" id="zoomResult"></div>
    </div>

</div>

{{-- ================= VIDEO MODAL ================= --}}
{{-- ================= bazaron STYLE VIDEO MODAL ================= --}}
<div id="bazaronVideoModal" class="bazaron-video-modal">
    <div class="bazaron-modal-content">

        {{-- CLOSE BUTTON --}}
        <span class="bazaron-close">&times;</span>

        <div class="bazaron-modal-body">

    <div class="bazaron-video-player">

        <iframe id="bazaronYoutubeFrame"
            src=""
            frameborder="0"
            allow="autoplay; encrypted-media"
            allowfullscreen>
        </iframe>

        <video id="bazaronUploadVideo" controls>
            <source src="">
        </video>

    </div>

</div>

<div class="bazaron-video-title">
    {{ $product->name }}
</div>
    </div>
</div>

<style>
.product-thumbs {
    display: flex;
    flex-direction: column;
    gap: 10px;
    max-height: 520px;
    overflow-y: auto;
}

.product-thumbs .thumb-img {
    width: 100%;
    height: 95px;
    object-fit: cover;
    border-radius: 6px;
    border: 2px solid transparent;
    cursor: pointer;
}

.product-thumbs .thumb-img.active {
    border-color: #2874f0;
}

.product-thumbs .video-thumb::after {
    content: "▶";
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 18px;
    color: #fff;
    background: rgba(0,0,0,0.7);
    border-radius: 50%;
    padding: 6px 10px;
}

.image-box {
    position: relative;
    /* width: 420px; */
    /* height: 420px; */
    border: 1px solid #ddd;
    background: #fff;
}

.image-box img,
.image-box video {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.video-play-overlay {
    display: none;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 70px;
    height: 70px;
    background: rgba(0,0,0,0.6);
    border-radius: 50%;
    color: white;
    font-size: 28px;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 5;
}

/* ===== bazaron VIDEO MODAL ===== */
.bazaron-video-modal {
    display: none;
    position: fixed;
    z-index: 99999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.85);
    align-items: center;
    justify-content: center;
}

.bazaron-modal-content {
    width: 85%;
    max-width: 1100px;
    height: 600px;
    background: #fff;
    border-radius: 8px;
    position: relative;
    padding: 20px;
}

.bazaron-close {
    position: absolute;
    right: 20px;
    top: 15px;
    font-size: 26px;
    cursor: pointer;
    font-weight: bold;
}

.bazaron-modal-body {
    display: flex;
    gap: 20px;
    height: 100%;
}

.bazaron-video-player {
    flex: 3;
    height: 100%;
}

.bazaron-video-player iframe,
.bazaron-video-player video {
    width: 100%;
    height: 100%;
    border-radius: 6px;
    display: none;
}

.bazaron-video-list {
    flex: 1;
    overflow-y: auto;
    border-left: 1px solid #eee;
    padding-left: 15px;
}

.bazaron-video-thumb {
    cursor: pointer;
    margin-bottom: 12px;
    border: 2px solid transparent;
    border-radius: 6px;
}

.bazaron-video-thumb.active {
    border-color: #ff6a00;
}

.bazaron-video-thumb img {
    width: 100%;
    border-radius: 4px;
}

.lens {
    position: absolute;
    width: 150px;
    height: 150px;
    border: 1px solid #000;
    background: rgba(255,255,255,0.3);
    display: none;
    pointer-events: none;
}

.zoom-result {
    width: 600px;
    height: 600px;
    border: 1px solid #ddd;
    background-repeat: no-repeat;
    background-size: 800px 800px;
    display: none;
     box-shadow: 0 2px 10px rgba(0,0,0,0.15);
}
.image-box {
    position: relative;
    overflow: hidden;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ===== MAIN IMAGE SWITCH (IMAGES + VIDEO THUMB CLICK) =====
    const mainImage = document.getElementById('mainImage');
    const playOverlay = document.getElementById('videoPlayOverlay');

    let currentVideoSrc = null;
    let currentVideoType = null;

    document.querySelectorAll('.thumb-img').forEach(thumb => {
        thumb.addEventListener('click', function () {

            // active border
            document.querySelectorAll('.thumb-img')
                .forEach(i => i.classList.remove('active'));
            this.classList.add('active');

            const type = this.dataset.type;

            if (type === 'video') {
                currentVideoSrc = this.dataset.video;
                currentVideoType = this.dataset.videoType;

                // show video preview thumbnail in main box
                mainImage.src = this.src;
                playOverlay.style.display = 'flex';
            } else {
                // normal image
                playOverlay.style.display = 'none';
                mainImage.src = this.dataset.full;
            }
        });
    });

    // ===== bazaron MODAL ELEMENTS =====
    const bazaronModal = document.getElementById('bazaronVideoModal');
    const youtubeFrame = document.getElementById('bazaronYoutubeFrame');
    const uploadVideo = document.getElementById('bazaronUploadVideo');
    const closeBtn = document.querySelector('.bazaron-close');

    // ===== PLAY BUTTON CLICK → OPEN bazaron MODAL =====
    playOverlay.addEventListener('click', function () {
        if (!currentVideoSrc) return;

        bazaronModal.style.display = 'flex';

        if (currentVideoType === 'youtube') {

            let videoId = '';

            if (currentVideoSrc.includes('watch?v=')) {
                videoId = currentVideoSrc.split('watch?v=')[1].split('&')[0];
            } else if (currentVideoSrc.includes('youtu.be/')) {
                videoId = currentVideoSrc.split('youtu.be/')[1];
            } else {
                videoId = currentVideoSrc;
            }

            youtubeFrame.src = "https://www.youtube.com/embed/" + videoId + "?autoplay=1";
            youtubeFrame.style.display = 'block';
            uploadVideo.style.display = 'none';

        } else {
            uploadVideo.querySelector('source').src = currentVideoSrc;
            uploadVideo.load();
            uploadVideo.style.display = 'block';
            youtubeFrame.style.display = 'none';
        }
    });

    // ===== CLOSE MODAL (X BUTTON) =====
    closeBtn.addEventListener('click', function () {
        bazaronModal.style.display = 'none';
        youtubeFrame.src = "";
        uploadVideo.pause();
    });

    // ===== CLICK OUTSIDE = CLOSE (bazaron STYLE) =====
    bazaronModal.addEventListener('click', function(e){
        if(e.target === bazaronModal){
            bazaronModal.style.display = 'none';
            youtubeFrame.src = "";
            uploadVideo.pause();
        }
    });

});

   
document.addEventListener("DOMContentLoaded", function () {

    const imageBox = document.getElementById("imageBox");
    const mainImage = document.getElementById("mainImage");
    const lens = document.getElementById("lens");
    const result = document.getElementById("zoomResult");

    if (!imageBox || !mainImage || !lens || !result) return;

    function initZoom(){

    const imgRect = mainImage.getBoundingClientRect();

    const cx = result.offsetWidth / lens.offsetWidth;
    const cy = result.offsetHeight / lens.offsetHeight;

    result.style.backgroundImage = "url('" + mainImage.src + "')";

    // 🔥 FIXED ORDER
    result.style.backgroundSize =
        (imgRect.width * cx) + "px " + (imgRect.height * cy) + "px";
}

function moveLens(e){

    const rect = imageBox.getBoundingClientRect();
    const imgRect = mainImage.getBoundingClientRect();

    const cx = result.offsetWidth / lens.offsetWidth;
    const cy = result.offsetHeight / lens.offsetHeight;

    // 🔥 IMPORTANT: background size yaha set karo
    result.style.backgroundSize =
        (imgRect.width * cx) + "px " + (imgRect.height * cy) + "px";

    let offsetX = imgRect.left - rect.left;
    let offsetY = imgRect.top - rect.top;

    let x = e.clientX - rect.left - offsetX;
    let y = e.clientY - rect.top - offsetY;

    x = x - lens.offsetWidth / 2;
    y = y - lens.offsetHeight / 2;

    const imgWidth = imgRect.width;
    const imgHeight = imgRect.height;

    if (x < 0) x = 0;
    if (y < 0) y = 0;

    if (x > imgWidth - lens.offsetWidth) {
        x = imgWidth - lens.offsetWidth;
    }

    if (y > imgHeight - lens.offsetHeight) {
        y = imgHeight - lens.offsetHeight;
    }

    lens.style.left = (x + offsetX) + "px";
    lens.style.top  = (y + offsetY) + "px";

    result.style.backgroundPosition =
        "-" + (x * cx) + "px -" + (y * cy) + "px";
}

    // 🔥 EVENTS ONLY ONCE (MAIN FIX)
    imageBox.onmouseenter = function(){
        lens.style.display = "block";
        result.style.display = "block";
    };

    imageBox.onmouseleave = function(){
        lens.style.display = "none";
        result.style.display = "none";
    };

    imageBox.onmousemove = moveLens;

    // 🔥 INITIAL LOAD
   mainImage.onload = initZoom;

// 🔥 fallback (agar already loaded hai)
if (mainImage.complete) {
    initZoom();
}

    // 🔥 THUMB CLICK FIX
    document.querySelectorAll(".thumb-img").forEach(function(thumb){
        thumb.addEventListener("click",function(){

            const newImg = this.dataset.full || this.src;
            mainImage.src = newImg;

            mainImage.onload = function(){
                initZoom(); // re-init zoom
            };
        });
    });

});

    



</script>
