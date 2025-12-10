"use strict";

/**
 * Drag and Drop Upload Component
 * 
 * Usage:
 * 1. Add class "upload-zone" to the container element
 * 2. Add data-preview attribute with the ID of the image preview element
 * 3. Add data-input attribute with the ID of the file input element
 * 4. Optionally add data-max-size attribute for max file size in MB (default: 2)
 * 
 * Example:
 * <div class="upload-zone" data-preview="viewer" data-input="customFileEg1" data-max-size="1">
 *     <img id="viewer" src="..." />
 *     <input type="file" id="customFileEg1" />
 *     <div class="drag-overlay">
 *         <i class="tio-file-add-outlined"></i>
 *         <p>Drop image here</p>
 *     </div>
 * </div>
 */

class DragDropUpload {
    constructor(element) {
        this.uploadZone = element;
        this.previewId = element.dataset.preview;
        this.inputId = element.dataset.input;
        this.maxSize = parseFloat(element.dataset.maxSize) || 2; // Default 2MB
        this.allowedTypes = (element.dataset.allowedTypes || 'image/jpeg,image/jpg,image/png,image/gif,image/webp,image/bmp,image/tiff').split(',');
        
        this.imagePreview = document.getElementById(this.previewId);
        this.fileInput = document.getElementById(this.inputId);
        
        if (this.uploadZone && this.fileInput) {
            this.init();
        }
    }

    init() {
        // Prevent default drag behaviors on the zone
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            this.uploadZone.addEventListener(eventName, this.preventDefaults.bind(this), false);
        });

        // Highlight drop zone when dragging over it
        ['dragenter', 'dragover'].forEach(eventName => {
            this.uploadZone.addEventListener(eventName, this.highlight.bind(this), false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            this.uploadZone.addEventListener(eventName, this.unhighlight.bind(this), false);
        });

        // Handle dropped files
        this.uploadZone.addEventListener('drop', this.handleDrop.bind(this), false);

        // Make the entire zone clickable to trigger file input
        this.uploadZone.addEventListener('click', (e) => {
            // Don't trigger if clicking on the file input itself or its label
            if (e.target !== this.fileInput && !e.target.closest('.icon-file')) {
                // Check if there's a label that should handle the click
                const label = this.uploadZone.querySelector('label[for="' + this.inputId + '"]');
                if (!label || !label.contains(e.target)) {
                    this.fileInput.click();
                }
            }
        });
    }

    preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    highlight() {
        this.uploadZone.classList.add('drag-over');
    }

    unhighlight() {
        this.uploadZone.classList.remove('drag-over');
    }

    handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;

        if (files.length > 0) {
            this.handleFiles(files);
        }
    }

    handleFiles(files) {
        const file = files[0];

        // Validate file type
        if (!this.allowedTypes.includes(file.type)) {
            if (typeof toastr !== 'undefined') {
                toastr.error('Please upload a valid image file (JPG, PNG, GIF, WebP)');
            } else {
                alert('Please upload a valid image file (JPG, PNG, GIF, WebP)');
            }
            return;
        }

        // Validate file size
        const fileSizeMB = file.size / (1024 * 1024);
        if (fileSizeMB > this.maxSize) {
            if (typeof toastr !== 'undefined') {
                toastr.error('File size must be less than ' + this.maxSize + 'MB');
            } else {
                alert('File size must be less than ' + this.maxSize + 'MB');
            }
            return;
        }

        // Assign file to input
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        this.fileInput.files = dataTransfer.files;

        // Trigger change event on file input
        const event = new Event('change', { bubbles: true });
        this.fileInput.dispatchEvent(event);

        // Preview image if preview element exists
        if (this.imagePreview) {
            this.previewFile(file);
        }
    }

    previewFile(file) {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onloadend = () => {
            this.imagePreview.src = reader.result;
        }
    }
}

// Auto-initialize all upload zones when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    DragDropUpload.initAll();
});

// Static method to initialize all upload zones
DragDropUpload.initAll = function() {
    document.querySelectorAll('.upload-zone').forEach(element => {
        // Skip if already initialized
        if (!element.dragDropInstance) {
            element.dragDropInstance = new DragDropUpload(element);
        }
    });
};

// Static method to initialize a specific element
DragDropUpload.init = function(element) {
    if (element && !element.dragDropInstance) {
        element.dragDropInstance = new DragDropUpload(element);
    }
    return element ? element.dragDropInstance : null;
};

// For dynamically added elements (like modals)
$(document).ready(function() {
    // Re-initialize when modals are shown
    $(document).on('shown.bs.modal', function() {
        DragDropUpload.initAll();
    });

    // Re-initialize when offcanvas/sidebars are shown
    $(document).on('click', '[class*="withdraw-info-show"]', function() {
        setTimeout(function() {
            DragDropUpload.initAll();
        }, 100);
    });
});
