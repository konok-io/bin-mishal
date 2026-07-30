<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Scan - Document Scanner</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cropperjs@1.6.1/dist/cropper.min.css">
    <script src="https://cdn.jsdelivr.net/npm/cropperjs@1.6.1/dist/cropper.min.js"></script>
    <style>
        .corner-point {
            width: 20px;
            height: 20px;
            background: #3b82f6;
            border: 3px solid white;
            border-radius: 50%;
            cursor: move;
            position: absolute;
            transform: translate(-50%, -50%);
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
            z-index: 10;
            transition: transform 0.1s;
        }
        .corner-point:hover {
            transform: translate(-50%, -50%) scale(1.2);
        }
        .corner-point.dragging {
            transform: translate(-50%, -50%) scale(1.3);
            background: #2563eb;
        }
        #imageContainer {
            position: relative;
            display: inline-block;
        }
        #overlayCanvas {
            position: absolute;
            top: 0;
            left: 0;
            pointer-events: none;
        }
        .effect-thumb {
            transition: all 0.2s;
            cursor: pointer;
        }
        .effect-thumb:hover {
            transform: scale(1.05);
        }
        .effect-thumb.active {
            ring: 4px;
            ring-color: #3b82f6;
            border-color: #3b82f6;
        }
        .thumbnail-scroll {
            display: flex;
            gap: 8px;
            overflow-x: auto;
            padding: 8px 0;
            scroll-snap-type: x mandatory;
        }
        .thumbnail-scroll > div {
            scroll-snap-align: start;
            flex-shrink: 0;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen" x-data="scannerApp()">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-5xl mx-auto">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Edit Scan</h1>
                <div class="flex gap-3">
                    <button 
                        @click="addToMerge()"
                        class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors"
                        x-show="!isSaved"
                    >
                        Add to Merge
                    </button>
                    <button 
                        @click="goBack()"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors"
                    >
                        ← Back
                    </button>
                </div>
            </div>

            <!-- Main Editor Area -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <!-- Image Preview with Corner Points -->
                <div class="text-center mb-6">
                    <div id="imageContainer" class="inline-block relative">
                        <img 
                            id="mainImage" 
                            src="{{ Storage::url($scan->original_path) }}" 
                            class="max-w-full h-auto rounded-lg shadow-md"
                            crossorigin="anonymous"
                        >
                        <canvas id="overlayCanvas"></canvas>
                        
                        <!-- Corner Points (will be positioned by JS) -->
                        <template x-for="(corner, index) in corners" :key="index">
                            <div 
                                class="corner-point"
                                :class="{ 'dragging': draggingCorner === index }"
                                :style="'left: ' + corner.x + 'px; top: ' + corner.y + 'px;'"
                                @mousedown="startDrag(index, $event)"
                                @touchstart="startDrag(index, $event)"
                            ></div>
                        </template>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-center gap-4 mb-6">
                    <button 
                        @click="autoDetectCorners()"
                        class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors"
                        :disabled="isProcessing"
                    >
                        Auto Detect
                    </button>
                    <button 
                        @click="cropImage()"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50"
                        :disabled="isProcessing || !isCropped"
                    >
                        Crop
                    </button>
                    <button 
                        @click="resetCorners()"
                        class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors"
                    >
                        Reset
                    </button>
                </div>

                <!-- Loading Indicator -->
                <div x-show="isProcessing" class="text-center mb-4">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-blue-100 text-blue-700 rounded-lg">
                        <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-text="processingMessage">Processing...</span>
                    </div>
                </div>

                <!-- Effects Thumbnail Strip -->
                <div x-show="isCropped" class="mt-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-3">Effects</h3>
                    <div class="thumbnail-scroll">
                        <template x-for="effect in effects" :key="effect.id">
                            <div 
                                class="effect-thumb flex-shrink-0 w-24 p-2 border-2 border-gray-200 rounded-lg"
                                :class="{ 'border-blue-500 bg-blue-50': selectedEffect === effect.id }"
                                @click="selectEffect(effect.id)"
                            >
                                <img 
                                    :id="'thumb-' + effect.id"
                                    class="w-full h-24 object-cover rounded mb-1 bg-gray-100"
                                    :src="effect.thumbnail || '/placeholder.png'"
                                    @error="$el.src = 'data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><rect fill=%22%23f3f4f6%22 width=%22100%22 height=%22100%22/><text x=%2250%22 y=%2255%22 text-anchor=%22middle%22 fill=%22%23999%22 font-size=%2212%22>Loading...</text></svg>'"
                                >
                                <p class="text-xs text-center text-gray-600 truncate" x-text="effect.name"></p>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Processed Image Preview -->
                <div x-show="currentProcessedImage" class="mt-6 text-center">
                    <h3 class="text-lg font-semibold text-gray-700 mb-3">Preview</h3>
                    <img 
                        :src="currentProcessedImage"
                        class="max-w-full mx-auto h-auto rounded-lg shadow-md border border-gray-200"
                    >
                </div>

                <!-- Save and Download Actions -->
                <div x-show="isCropped" class="mt-6 flex flex-wrap justify-center gap-4">
                    <button 
                        @click="saveScan()"
                        class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold"
                        :disabled="isSaving"
                    >
                        <span x-show="!isSaving">Save</span>
                        <span x-show="isSaving">Saving...</span>
                    </button>
                    <button 
                        @click="downloadImage('png')"
                        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold"
                        :disabled="!isSaved"
                    >
                        Download PNG
                    </button>
                    <button 
                        @click="downloadImage('jpg')"
                        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold"
                        :disabled="!isSaved"
                    >
                        Download JPG
                    </button>
                    <button 
                        @click="downloadImage('pdf')"
                        class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-semibold"
                        :disabled="!isSaved"
                    >
                        Download PDF
                    </button>
                </div>

                <!-- Merge Section -->
                <div x-show="mergedScans.length > 0" class="mt-6 p-4 bg-purple-50 rounded-lg">
                    <h3 class="text-lg font-semibold text-purple-700 mb-3">Merge to PDF</h3>
                    <p class="text-sm text-purple-600 mb-2" x-text="mergedScans.length + ' scan(s) selected'"></p>
                    <div class="flex gap-2 flex-wrap">
                        <template x-for="scan in mergedScans" :key="scan">
                            <span class="px-2 py-1 bg-purple-200 text-purple-800 rounded text-sm" x-text="scan.substring(0, 8) + '...'"></span>
                        </template>
                    </div>
                    <button 
                        @click="mergeToPdf()"
                        class="mt-3 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors"
                        :disabled="isMerging"
                    >
                        <span x-show="!isMerging">Merge & Download PDF</span>
                        <span x-show="isMerging">Merging...</span>
                    </button>
                </div>

                <!-- Error Message -->
                <div x-show="errorMessage" class="mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg" x-text="errorMessage"></div>
            </div>
        </div>
    </div>

    <script>
        function scannerApp() {
            return {
                sessionId: '{{ $scan->session_id }}',
                originalImage: '{{ Storage::url($scan->original_path) }}',
                currentImage: '{{ Storage::url($scan->original_path) }}',
                currentProcessedImage: null,
                corners: [
                    { x: 0, y: 0 },   // top-left
                    { x: 0, y: 0 },   // top-right
                    { x: 0, y: 0 },   // bottom-right
                    { x: 0, y: 0 }    // bottom-left
                ],
                originalCorners: [
                    { x: 0, y: 0 },
                    { x: 0, y: 0 },
                    { x: 0, y: 0 },
                    { x: 0, y: 0 }
                ],
                draggingCorner: null,
                isCropped: {{ $scan->is_cropped ? 'true' : 'false' }},
                isProcessing: false,
                isSaving: false,
                isMerging: false,
                isSaved: false,
                processingMessage: 'Processing...',
                errorMessage: '',
                selectedEffect: 'original',
                currentEffect: 'original',
                mergedScans: [],
                effects: [
                    { id: 'original', name: 'Original', thumbnail: null },
                    { id: 'no-shadow', name: 'No Shadow', thumbnail: null },
                    { id: 'lighten', name: 'Lighten', thumbnail: null },
                    { id: 'magic-color', name: 'Magic Color', thumbnail: null },
                    { id: 'magic-pro', name: 'Magic Pro', thumbnail: null },
                    { id: 'bw', name: 'B&W', thumbnail: null },
                    { id: 'eco', name: 'Eco', thumbnail: null },
                    { id: 'grayscale', name: 'Grayscale', thumbnail: null }
                ],

                init() {
                    this.$nextTick(() => {
                        this.initCorners();
                        this.initDragEvents();
                        if (this.isCropped) {
                            this.currentImage = '{{ $scan->processed_path ? Storage::url($scan->processed_path) : Storage::url($scan->original_path) }}';
                            this.currentProcessedImage = this.currentImage;
                            this.loadEffectThumbnails();
                        }
                    });
                },

                initCorners() {
                    const img = document.getElementById('mainImage');
                    const container = document.getElementById('imageContainer');
                    
                    const setCorners = () => {
                        const rect = img.getBoundingClientRect();
                        const containerRect = container.getBoundingClientRect();
                        
                        // Default corners (10% margin from edges)
                        const margin = 0.1;
                        const w = rect.width;
                        const h = rect.height;
                        
                        this.corners[0] = { x: w * margin, y: h * margin }; // top-left
                        this.corners[1] = { x: w * (1 - margin), y: h * margin }; // top-right
                        this.corners[2] = { x: w * (1 - margin), y: h * (1 - margin) }; // bottom-right
                        this.corners[3] = { x: w * margin, y: h * (1 - margin) }; // bottom-left
                        
                        this.originalCorners = JSON.parse(JSON.stringify(this.corners));
                        this.drawPolygon();
                    };

                    if (img.complete) {
                        setCorners();
                    } else {
                        img.addEventListener('load', setCorners);
                    }
                },

                initDragEvents() {
                    const container = document.getElementById('imageContainer');
                    
                    const handleMove = (e) => {
                        if (this.draggingCorner === null) return;
                        
                        const rect = container.getBoundingClientRect();
                        const clientX = e.touches ? e.touches[0].clientX : e.clientX;
                        const clientY = e.touches ? e.touches[0].clientY : e.clientY;
                        
                        this.corners[this.draggingCorner].x = clientX - rect.left;
                        this.corners[this.draggingCorner].y = clientY - rect.top;
                        
                        this.drawPolygon();
                    };

                    const handleEnd = () => {
                        this.draggingCorner = null;
                        document.querySelectorAll('.corner-point').forEach(el => {
                            el.classList.remove('dragging');
                        });
                        document.removeEventListener('mousemove', handleMove);
                        document.removeEventListener('mouseup', handleEnd);
                        document.removeEventListener('touchmove', handleMove);
                        document.removeEventListener('touchend', handleEnd);
                    };

                    document.addEventListener('mousemove', handleMove);
                    document.addEventListener('mouseup', handleEnd);
                    document.addEventListener('touchmove', handleMove, { passive: false });
                    document.addEventListener('touchend', handleEnd);
                },

                startDrag(index, e) {
                    e.preventDefault();
                    this.draggingCorner = index;
                    document.querySelectorAll('.corner-point')[index].classList.add('dragging');
                },

                drawPolygon() {
                    const canvas = document.getElementById('overlayCanvas');
                    const img = document.getElementById('mainImage');
                    canvas.width = img.width;
                    canvas.height = img.height;
                    
                    const ctx = canvas.getContext('2d');
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    
                    if (this.corners[0].x === 0 && this.corners[0].y === 0) return;
                    
                    // Draw semi-transparent polygon
                    ctx.beginPath();
                    ctx.moveTo(this.corners[0].x, this.corners[0].y);
                    for (let i = 1; i < this.corners.length; i++) {
                        ctx.lineTo(this.corners[i].x, this.corners[i].y);
                    }
                    ctx.closePath();
                    
                    ctx.fillStyle = 'rgba(59, 130, 246, 0.2)';
                    ctx.fill();
                    
                    ctx.strokeStyle = '#3b82f6';
                    ctx.lineWidth = 2;
                    ctx.stroke();
                },

                async autoDetectCorners() {
                    this.isProcessing = true;
                    this.processingMessage = 'Detecting document edges...';
                    this.errorMessage = '';
                    
                    try {
                        const img = document.getElementById('mainImage');
                        
                        // Use Cropper.js to get the cropped region
                        const cropper = new Cropper(img, {
                            aspectRatio: NaN, // Free aspect ratio
                            viewMode: 1,
                            dragMode: 'crop',
                            guides: true,
                            center: true,
                            highlight: false,
                            cropBoxMovable: true,
                            cropBoxResizable: true,
                            toggleDragModeOnDblclick: false,
                        });
                        
                        // Get the crop box data after a short delay
                        setTimeout(() => {
                            const cropBoxData = cropper.getCropBoxData();
                            const imageData = cropper.getImageData();
                            
                            // Calculate corners from crop box
                            const scaleX = imageData.width / img.offsetWidth;
                            const scaleY = imageData.height / img.offsetHeight;
                            
                            this.corners[0] = { x: cropBoxData.left * scaleX, y: cropBoxData.top * scaleY }; // top-left
                            this.corners[1] = { x: (cropBoxData.left + cropBoxData.width) * scaleX, y: cropBoxData.top * scaleY }; // top-right
                            this.corners[2] = { x: (cropBoxData.left + cropBoxData.width) * scaleX, y: (cropBoxData.top + cropBoxData.height) * scaleY }; // bottom-right
                            this.corners[3] = { x: cropBoxData.left * scaleX, y: (cropBoxData.top + cropBoxData.height) * scaleY }; // bottom-left
                            
                            this.drawPolygon();
                            cropper.destroy();
                        }, 500);
                        
                    } catch (error) {
                        this.errorMessage = error.message;
                        this.isProcessing = false;
                    }
                },

                resetCorners() {
                    this.corners = JSON.parse(JSON.stringify(this.originalCorners));
                    this.drawPolygon();
                },

                async cropImage() {
                    this.isProcessing = true;
                    this.processingMessage = 'Cropping image...';
                    this.errorMessage = '';
                    
                    try {
                        // Convert corner coordinates to match original image size
                        const img = document.getElementById('mainImage');
                        const imgRect = img.getBoundingClientRect();
                        const scaleX = img.width / imgRect.width;
                        const scaleY = img.height / imgRect.height;
                        
                        const scaledCorners = this.corners.map(c => ({
                            x: Math.round(c.x * scaleX),
                            y: Math.round(c.y * scaleY)
                        }));
                        
                        const response = await fetch(`/scan/${this.sessionId}/crop`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ corners: scaledCorners })
                        });
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            this.currentImage = data.image_url;
                            this.currentProcessedImage = data.image_url;
                            this.isCropped = true;
                            
                            // Update main image
                            document.getElementById('mainImage').src = data.image_url;
                            
                            // Reinitialize corners for new image
                            this.$nextTick(() => {
                                this.initCorners();
                                this.loadEffectThumbnails();
                            });
                        } else {
                            throw new Error(data.error || 'Failed to crop image');
                        }
                    } catch (error) {
                        this.errorMessage = error.message;
                    } finally {
                        this.isProcessing = false;
                    }
                },

                async loadEffectThumbnails() {
                    for (const effect of this.effects) {
                        try {
                            const response = await fetch(`/scan/${this.sessionId}/thumbnail`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({ effect: effect.id })
                            });
                            
                            const data = await response.json();
                            
                            if (data.success) {
                                effect.thumbnail = data.thumbnail_url;
                                const thumbImg = document.getElementById(`thumb-${effect.id}`);
                                if (thumbImg) {
                                    thumbImg.src = data.thumbnail_url;
                                }
                            }
                        } catch (error) {
                            console.error(`Failed to load thumbnail for ${effect.id}:`, error);
                        }
                    }
                },

                async selectEffect(effectId) {
                    this.selectedEffect = effectId;
                    this.currentEffect = effectId;
                    this.isProcessing = true;
                    this.errorMessage = '';
                    
                    try {
                        const response = await fetch(`/scan/${this.sessionId}/effect`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ effect: effectId })
                        });
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            this.currentProcessedImage = data.image_url;
                            document.getElementById('mainImage').src = data.image_url;
                        } else {
                            throw new Error(data.error || 'Failed to apply effect');
                        }
                    } catch (error) {
                        this.errorMessage = error.message;
                    } finally {
                        this.isProcessing = false;
                    }
                },

                async saveScan() {
                    this.isSaving = true;
                    this.errorMessage = '';
                    
                    try {
                        const response = await fetch(`/scan/${this.sessionId}/save`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ effect: this.currentEffect })
                        });
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            this.isSaved = true;
                        } else {
                            throw new Error(data.error || 'Failed to save');
                        }
                    } catch (error) {
                        this.errorMessage = error.message;
                    } finally {
                        this.isSaving = false;
                    }
                },

                async downloadImage(format) {
                    window.location.href = `/scan/${this.sessionId}/download?format=${format}`;
                },

                addToMerge() {
                    if (!this.mergedScans.includes(this.sessionId)) {
                        this.mergedScans.push(this.sessionId);
                    }
                },

                async mergeToPdf() {
                    if (this.mergedScans.length < 2) {
                        alert('Please add at least 2 scans to merge');
                        return;
                    }
                    
                    this.isMerging = true;
                    
                    try {
                        const response = await fetch('/scan/merge-pdf', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ session_ids: this.mergedScans })
                        });
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            window.location.href = data.pdf_url;
                        } else {
                            throw new Error(data.error || 'Failed to merge PDF');
                        }
                    } catch (error) {
                        this.errorMessage = error.message;
                    } finally {
                        this.isMerging = false;
                    }
                },

                goBack() {
                    window.location.href = '/';
                }
            };
        }
    </script>
</body>
</html>
