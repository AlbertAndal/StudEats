// Profile Photo Management Component
class ProfilePhotoManager {
    constructor() {
        this.init();
        this.cropperInstance = null;
        this.tempImagePath = null;
    }

    init() {
        this.bindEvents();
        this.createModal();
    }

    bindEvents() {
        // Photo upload trigger
        document.addEventListener('click', (e) => {
            if (e.target.closest('[data-photo-upload]')) {
                e.preventDefault();
                this.showUploadModal();
            }
        });

        // Delete photo
        document.addEventListener('click', (e) => {
            if (e.target.closest('[data-photo-delete]')) {
                e.preventDefault();
                this.deletePhoto();
            }
        });
    }

    createModal() {
        const modalHTML = `
            <div id="photoUploadModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
                <div class="flex items-center justify-center min-h-screen p-4">
                    <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                        <!-- Modal Header -->
                        <div class="px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900">Update Profile Photo</h3>
                                <button type="button" data-modal-close class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Modal Body -->
                        <div class="px-6 py-4">
                            <!-- Upload Section -->
                            <div id="uploadSection">
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-green-400 transition-colors">
                                    <input type="file" id="photoInput" accept="image/*" class="hidden">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    <div class="space-y-2">
                                        <p class="text-lg font-medium text-gray-700">Upload your photo</p>
                                        <p class="text-sm text-gray-500">Drag and drop or click to browse</p>
                                        <p class="text-xs text-gray-400">Supports: JPEG, PNG, GIF (max 5MB)</p>
                                    </div>
                                    <button type="button" id="browseButton" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                                        Choose File
                                    </button>
                                </div>
                            </div>

                            <!-- Crop Section -->
                            <div id="cropSection" class="hidden">
                                <div class="space-y-4">
                                    <div class="text-center">
                                        <h4 class="text-md font-medium text-gray-900">Adjust your photo</h4>
                                        <p class="text-sm text-gray-500">Drag to reposition, scroll to zoom</p>
                                    </div>
                                    <div class="relative">
                                        <img id="cropImage" class="max-w-full h-auto" style="max-height: 400px;">
                                    </div>
                                    <div class="flex space-x-3">
                                        <button type="button" id="cropSaveButton" class="flex-1 bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 disabled:opacity-50">
                                            <span class="flex items-center justify-center">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Save Photo
                                            </span>
                                        </button>
                                        <button type="button" id="cropCancelButton" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                                            Cancel
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Loading Section -->
                            <div id="loadingSection" class="hidden text-center py-8">
                                <div class="inline-flex items-center">
                                    <svg class="animate-spin -ml-1 mr-3 h-8 w-8 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span class="text-lg text-gray-700" id="loadingText">Processing...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', modalHTML);
        this.bindModalEvents();
    }

    bindModalEvents() {
        const modal = document.getElementById('photoUploadModal');
        const photoInput = document.getElementById('photoInput');
        const browseButton = document.getElementById('browseButton');
        const cropSaveButton = document.getElementById('cropSaveButton');
        const cropCancelButton = document.getElementById('cropCancelButton');

        // Close modal
        modal.addEventListener('click', (e) => {
            if (e.target === modal || e.target.hasAttribute('data-modal-close')) {
                this.hideModal();
            }
        });

        // Browse button
        browseButton.addEventListener('click', () => {
            photoInput.click();
        });

        // File input change
        photoInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                this.handleFileUpload(e.target.files[0]);
            }
        });

        // Drag and drop
        const uploadSection = document.getElementById('uploadSection');
        uploadSection.addEventListener('dragover', (e) => {
            e.preventDefault();
            e.stopPropagation();
            uploadSection.classList.add('border-green-400', 'bg-green-50');
        });

        uploadSection.addEventListener('dragleave', (e) => {
            e.preventDefault();
            e.stopPropagation();
            uploadSection.classList.remove('border-green-400', 'bg-green-50');
        });

        uploadSection.addEventListener('drop', (e) => {
            e.preventDefault();
            e.stopPropagation();
            uploadSection.classList.remove('border-green-400', 'bg-green-50');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                this.handleFileUpload(files[0]);
            }
        });

        // Crop buttons
        cropSaveButton.addEventListener('click', () => {
            this.saveCroppedPhoto();
        });

        cropCancelButton.addEventListener('click', () => {
            this.showUploadSection();
        });
    }

    showUploadModal() {
        document.getElementById('photoUploadModal').classList.remove('hidden');
        this.showUploadSection();
    }

    hideModal() {
        document.getElementById('photoUploadModal').classList.add('hidden');
        this.resetModal();
    }

    showUploadSection() {
        document.getElementById('uploadSection').classList.remove('hidden');
        document.getElementById('cropSection').classList.add('hidden');
        document.getElementById('loadingSection').classList.add('hidden');
        if (this.cropperInstance) {
            this.cropperInstance.destroy();
            this.cropperInstance = null;
        }
    }

    showCropSection() {
        document.getElementById('uploadSection').classList.add('hidden');
        document.getElementById('cropSection').classList.remove('hidden');
        document.getElementById('loadingSection').classList.add('hidden');
    }

    showLoadingSection(text = 'Processing...') {
        document.getElementById('uploadSection').classList.add('hidden');
        document.getElementById('cropSection').classList.add('hidden');
        document.getElementById('loadingSection').classList.remove('hidden');
        document.getElementById('loadingText').textContent = text;
    }

    resetModal() {
        if (this.cropperInstance) {
            this.cropperInstance.destroy();
            this.cropperInstance = null;
        }
        this.tempImagePath = null;
        document.getElementById('photoInput').value = '';
        this.showUploadSection();
    }

    async handleFileUpload(file) {
        // Validate file
        if (!file.type.match(/^image\/(jpeg|jpg|png|gif)$/)) {
            this.showNotification('Please select a valid image file (JPEG, PNG, GIF)', 'error');
            return;
        }

        if (file.size > 5 * 1024 * 1024) { // 5MB
            this.showNotification('File size must be less than 5MB', 'error');
            return;
        }

        this.showLoadingSection('Uploading...');

        try {
            const formData = new FormData();
            formData.append('photo', file);

            const response = await fetch('/profile/photo/upload', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const result = await response.json();

            if (result.success) {
                this.tempImagePath = result.data.temp_path;
                this.setupCropper(result.data.preview_url);
            } else {
                this.showNotification(result.message || 'Upload failed', 'error');
                this.showUploadSection();
            }
        } catch (error) {
            console.error('Upload error:', error);
            this.showNotification('Upload failed. Please try again.', 'error');
            this.showUploadSection();
        }
    }

    setupCropper(imageUrl) {
        this.showCropSection();
        
        const cropImage = document.getElementById('cropImage');
        cropImage.src = imageUrl;

        // Wait for image to load
        cropImage.onload = () => {
            // Import Cropper.js if not already loaded
            if (typeof Cropper === 'undefined') {
                this.loadCropperJS().then(() => {
                    this.initializeCropper(cropImage);
                });
            } else {
                this.initializeCropper(cropImage);
            }
        };
    }

    async loadCropperJS() {
        return new Promise((resolve) => {
            if (document.querySelector('link[href*="cropper"]')) {
                resolve();
                return;
            }

            // Load CSS
            const cssLink = document.createElement('link');
            cssLink.rel = 'stylesheet';
            cssLink.href = 'https://cdn.jsdelivr.net/npm/cropperjs@1.6.1/dist/cropper.min.css';
            document.head.appendChild(cssLink);

            // Load JS
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/cropperjs@1.6.1/dist/cropper.min.js';
            script.onload = resolve;
            document.head.appendChild(script);
        });
    }

    initializeCropper(imageElement) {
        this.cropperInstance = new Cropper(imageElement, {
            aspectRatio: 1,
            viewMode: 2,
            dragMode: 'move',
            autoCropArea: 0.8,
            restore: false,
            guides: false,
            center: false,
            highlight: false,
            cropBoxMovable: false,
            cropBoxResizable: false,
            toggleDragModeOnDblclick: false,
            ready: () => {
                // Cropper is ready
            }
        });
    }

    async saveCroppedPhoto() {
        if (!this.cropperInstance || !this.tempImagePath) {
            this.showNotification('No image to save', 'error');
            return;
        }

        this.showLoadingSection('Saving...');

        try {
            const cropData = this.cropperInstance.getData();
            
            const response = await fetch('/profile/photo/crop', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    temp_path: this.tempImagePath,
                    x: Math.round(cropData.x),
                    y: Math.round(cropData.y),
                    width: Math.round(cropData.width),
                    height: Math.round(cropData.height)
                })
            });

            const result = await response.json();

            if (result.success) {
                this.showNotification('Profile photo updated successfully!', 'success');
                this.updateProfileImages(result.data.avatar_url);
                this.hideModal();
            } else {
                this.showNotification(result.message || 'Failed to save photo', 'error');
                this.showCropSection();
            }
        } catch (error) {
            console.error('Save error:', error);
            this.showNotification('Failed to save photo. Please try again.', 'error');
            this.showCropSection();
        }
    }

    async deletePhoto() {
        if (!confirm('Are you sure you want to delete your profile photo?')) {
            return;
        }

        try {
            const response = await fetch('/profile/photo', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const result = await response.json();

            if (result.success) {
                this.showNotification('Profile photo deleted successfully', 'success');
                this.updateProfileImages(result.data.avatar_url);
            } else {
                this.showNotification(result.message || 'Failed to delete photo', 'error');
            }
        } catch (error) {
            console.error('Delete error:', error);
            this.showNotification('Failed to delete photo. Please try again.', 'error');
        }
    }

    updateProfileImages(newUrl) {
        // Update all profile images on the page
        document.querySelectorAll('[data-profile-image]').forEach(img => {
            img.src = newUrl;
        });

        // Update any profile photo displays
        document.querySelectorAll('[data-avatar-image]').forEach(img => {
            img.src = newUrl;
        });
    }

    showNotification(message, type = 'info') {
        // Simple notification system
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-md shadow-lg ${
            type === 'success' ? 'bg-green-500 text-white' :
            type === 'error' ? 'bg-red-500 text-white' :
            'bg-blue-500 text-white'
        }`;
        notification.textContent = message;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 4000);
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    new ProfilePhotoManager();
});