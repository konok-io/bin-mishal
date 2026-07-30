<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Scanner</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h1 class="text-3xl font-bold text-center text-gray-800 mb-2">Document Scanner</h1>
                <p class="text-gray-600 text-center mb-8">Upload an image to scan and enhance your documents</p>

                <!-- Upload Form -->
                <form id="uploadForm" action="{{ route('scanner.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <!-- Drop Zone -->
                    <div 
                        id="dropZone"
                        class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center cursor-pointer transition-all duration-200 hover:border-blue-500 hover:bg-blue-50"
                        x-data="{
                            isDragging: false,
                            fileName: '',
                            preview: null,
                            
                            init() {
                                this.$watch('preview', (value) => {
                                    if (value) {
                                        this.$nextTick(() => {
                                            document.getElementById('previewImage').src = value;
                                            document.getElementById('previewContainer').classList.remove('hidden');
                                        });
                                    }
                                });
                            },
                            
                            handleDragOver(e) {
                                e.preventDefault();
                                this.isDragging = true;
                            },
                            
                            handleDragLeave(e) {
                                e.preventDefault();
                                this.isDragging = false;
                            },
                            
                            handleDrop(e) {
                                e.preventDefault();
                                this.isDragging = false;
                                const files = e.dataTransfer.files;
                                if (files.length > 0) {
                                    this.handleFile(files[0]);
                                }
                            },
                            
                            handleFileSelect(e) {
                                const files = e.target.files;
                                if (files.length > 0) {
                                    this.handleFile(files[0]);
                                }
                            },
                            
                            handleFile(file) {
                                // Validate file
                                const validTypes = ['image/jpeg', 'image/png', 'image/heic', 'image/heif'];
                                if (!validTypes.includes(file.type)) {
                                    alert('Please upload a valid image file (JPG, PNG, or HEIC)');
                                    return;
                                }
                                if (file.size > 10 * 1024 * 1024) {
                                    alert('File size must be less than 10MB');
                                    return;
                                }
                                
                                this.fileName = file.name;
                                const reader = new FileReader();
                                reader.onload = (e) => {
                                    this.preview = e.target.result;
                                };
                                reader.readAsDataURL(file);
                                
                                // Set file to input
                                const input = document.getElementById('imageInput');
                                const dt = new DataTransfer();
                                dt.items.add(file);
                                input.files = dt.files;
                            }
                        }"
                        @dragover.prevent="handleDragOver"
                        @dragleave.prevent="handleDragLeave"
                        @drop.prevent="handleDrop"
                        @click="$refs.fileInput.click()"
                        :class="{ 'border-blue-500 bg-blue-50': isDragging }"
                    >
                        <input 
                            type="file" 
                            id="imageInput" 
                            name="image" 
                            accept="image/jpeg,image/png,image/heic,image/heif"
                            class="hidden"
                            @change="handleFileSelect"
                            x-ref="fileInput"
                        >
                        
                        <div id="previewContainer" class="hidden mb-4">
                            <img id="previewImage" class="max-h-64 mx-auto rounded-lg shadow-md" alt="Preview">
                        </div>
                        
                        <div id="uploadPrompt" class="space-y-4">
                            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <div class="text-gray-600">
                                <p class="font-semibold">Drag and drop your image here</p>
                                <p class="text-sm">or click to browse</p>
                            </div>
                            <p class="text-xs text-gray-500">Supports JPG, PNG, HEIC (max 10MB)</p>
                        </div>
                    </div>
                    
                    <!-- Upload Status -->
                    <div id="uploadStatus" class="hidden">
                        <div class="flex items-center justify-center space-x-2">
                            <svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="text-gray-600">Uploading...</span>
                        </div>
                    </div>
                    
                    <!-- Upload Button -->
                    <button 
                        type="submit" 
                        id="submitBtn"
                        class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        Upload & Scan
                    </button>
                </form>

                <!-- Error Message -->
                <div id="errorMessage" class="hidden mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg"></div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('uploadForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = document.getElementById('submitBtn');
            const uploadStatus = document.getElementById('uploadStatus');
            const errorMessage = document.getElementById('errorMessage');
            
            // Hide previous errors
            errorMessage.classList.add('hidden');
            
            // Show loading state
            submitBtn.disabled = true;
            uploadStatus.classList.remove('hidden');
            
            try {
                const response = await fetch('{{ route('scanner.upload') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Redirect to edit page
                    window.location.href = '/scan/' + data.session_id;
                } else {
                    throw new Error(data.error || 'Upload failed');
                }
            } catch (error) {
                errorMessage.textContent = error.message;
                errorMessage.classList.remove('hidden');
                submitBtn.disabled = false;
            } finally {
                uploadStatus.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
