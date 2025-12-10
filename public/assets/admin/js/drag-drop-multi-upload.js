"use strict";

/**
 * Multi-Image Drag & Drop Upload Component
 * Supports multiple images with drag and drop functionality
 * 
 * Usage: Add class "multi-upload-zone" to container and set data attributes:
 * - data-max-count: Maximum number of images (default: 5)
 * - data-max-size: Maximum file size in MB (default: 2)
 * - data-field-name: Input field name (default: "item_images[]")
 */

document.addEventListener('DOMContentLoaded', function() {
    initMultiImageUpload();
});

function initMultiImageUpload() {
    const multiUploadZones = document.querySelectorAll('.multi-upload-zone');
    
    multiUploadZones.forEach(zone => {
        setupMultiUploadZone(zone);
    });
}

function setupMultiUploadZone(zone) {
    const maxCount = parseInt(zone.dataset.maxCount) || 5;
    const maxSize = parseFloat(zone.dataset.maxSize) || 2;
    const fieldName = zone.dataset.fieldName || 'item_images[]';
    const placeholderImg = zone.dataset.placeholder || '/public/assets/admin/img/upload-img.png';
    
    // Store uploaded files
    zone.uploadedFiles = [];
    
    // Setup existing images (from edit page)
    setupExistingImages(zone);
    
    // Count existing images
    const existingCount = zone.querySelectorAll('.multi-upload-item.existing-image').length;
    
    // Create initial upload slot if under max count
    if (existingCount < maxCount) {
        createUploadSlot(zone, fieldName, placeholderImg, maxSize);
    }
    
    // Setup drag and drop on the zone
    zone.addEventListener('dragover', function(e) {
        e.preventDefault();
        e.stopPropagation();
        zone.classList.add('drag-over');
    });
    
    zone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        e.stopPropagation();
        if (!zone.contains(e.relatedTarget)) {
            zone.classList.remove('drag-over');
        }
    });
    
    zone.addEventListener('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        zone.classList.remove('drag-over');
        
        const files = Array.from(e.dataTransfer.files).filter(file => file.type.startsWith('image/'));
        
        if (files.length > 0) {
            handleMultipleFiles(zone, files, fieldName, placeholderImg, maxSize, maxCount);
        }
    });
}

function createUploadSlot(zone, fieldName, placeholderImg, maxSize) {
    const maxCount = parseInt(zone.dataset.maxCount) || 5;
    const currentCount = zone.querySelectorAll('.multi-upload-item').length;
    
    if (currentCount >= maxCount) {
        return null;
    }
    
    const itemWrapper = document.createElement('div');
    itemWrapper.className = 'multi-upload-item spartan_item_wrapper min-w-176px max-w-176px';
    
    const uniqueId = 'multi_upload_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
    
    itemWrapper.innerHTML = `
        <label class="multi-upload-label" for="${uniqueId}">
            <img class="img--square" src="${placeholderImg}" alt="Upload image">
            <input type="file" name="${fieldName}" id="${uniqueId}" 
                   class="multi-upload-input d-none" 
                   accept=".webp,.jpg,.png,.jpeg,.gif,.bmp,.tif,.tiff,image/*">
            <div class="multi-upload-overlay">
                <i class="tio-file-add-outlined"></i>
                <span>Drop or Click</span>
            </div>
        </label>
    `;
    
    zone.appendChild(itemWrapper);
    
    // Setup file input change handler
    const fileInput = itemWrapper.querySelector('.multi-upload-input');
    const imgPreview = itemWrapper.querySelector('img');
    
    fileInput.addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            
            if (!isValidImageFile(file)) {
                showToast('error', 'Please only input png or jpg type file');
                this.value = '';
                return;
            }
            
            if (file.size > maxSize * 1024 * 1024) {
                showToast('error', 'File size too big. Maximum ' + maxSize + 'MB allowed');
                this.value = '';
                return;
            }
            
            // Show preview
            const reader = new FileReader();
            reader.onload = function(event) {
                imgPreview.src = event.target.result;
                itemWrapper.classList.add('has-image');
                
                // Add remove button if not exists
                if (!itemWrapper.querySelector('.multi-upload-remove')) {
                    addRemoveButton(itemWrapper, zone, fieldName, placeholderImg, maxSize);
                }
                
                // Create new upload slot if under max count
                const existingSlots = zone.querySelectorAll('.multi-upload-item');
                const emptySlots = zone.querySelectorAll('.multi-upload-item:not(.has-image)');
                
                if (existingSlots.length < parseInt(zone.dataset.maxCount || 5) && emptySlots.length === 0) {
                    createUploadSlot(zone, fieldName, placeholderImg, maxSize);
                    
                    // Scroll to new slot
                    setTimeout(function() {
                        const lastItem = zone.querySelector('.multi-upload-item:last-child');
                        if (lastItem) {
                            lastItem.scrollIntoView({ behavior: 'smooth', inline: 'end', block: 'nearest' });
                        }
                    }, 50);
                }
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Setup individual item drag and drop
    setupItemDragDrop(itemWrapper, fileInput, imgPreview, zone, fieldName, placeholderImg, maxSize);
    
    return itemWrapper;
}

function setupItemDragDrop(itemWrapper, fileInput, imgPreview, zone, fieldName, placeholderImg, maxSize) {
    const label = itemWrapper.querySelector('.multi-upload-label');
    
    label.addEventListener('dragover', function(e) {
        e.preventDefault();
        e.stopPropagation();
        itemWrapper.classList.add('drag-over');
    });
    
    label.addEventListener('dragleave', function(e) {
        e.preventDefault();
        e.stopPropagation();
        itemWrapper.classList.remove('drag-over');
    });
    
    label.addEventListener('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        itemWrapper.classList.remove('drag-over');
        zone.classList.remove('drag-over');
        
        const files = e.dataTransfer.files;
        if (files && files[0]) {
            const file = files[0];
            
            if (!isValidImageFile(file)) {
                showToast('error', 'Please only input png or jpg type file');
                return;
            }
            
            if (file.size > maxSize * 1024 * 1024) {
                showToast('error', 'File size too big. Maximum ' + maxSize + 'MB allowed');
                return;
            }
            
            // Create a DataTransfer to set the file
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            fileInput.files = dataTransfer.files;
            
            // Trigger change event
            fileInput.dispatchEvent(new Event('change', { bubbles: true }));
        }
    });
}

function handleMultipleFiles(zone, files, fieldName, placeholderImg, maxSize, maxCount) {
    const currentCount = zone.querySelectorAll('.multi-upload-item.has-image').length;
    const availableSlots = maxCount - currentCount;
    
    if (availableSlots <= 0) {
        showToast('error', 'Maximum ' + maxCount + ' images allowed');
        return;
    }
    
    const filesToProcess = files.slice(0, availableSlots);
    
    filesToProcess.forEach((file, index) => {
        if (!isValidImageFile(file)) {
            showToast('error', 'Please only input png or jpg type file');
            return;
        }
        
        if (file.size > maxSize * 1024 * 1024) {
            showToast('error', 'File size too big. Maximum ' + maxSize + 'MB allowed');
            return;
        }
        
        // Find empty slot or create new one
        let emptySlot = zone.querySelector('.multi-upload-item:not(.has-image)');
        
        if (!emptySlot) {
            emptySlot = createUploadSlot(zone, fieldName, placeholderImg, maxSize);
        }
        
        if (emptySlot) {
            const fileInput = emptySlot.querySelector('.multi-upload-input');
            const imgPreview = emptySlot.querySelector('img');
            
            // Create DataTransfer to set file
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            fileInput.files = dataTransfer.files;
            
            // Show preview
            const reader = new FileReader();
            reader.onload = function(event) {
                imgPreview.src = event.target.result;
                emptySlot.classList.add('has-image');
                
                if (!emptySlot.querySelector('.multi-upload-remove')) {
                    addRemoveButton(emptySlot, zone, fieldName, placeholderImg, maxSize);
                }
                
                // Create new slot after last file
                if (index === filesToProcess.length - 1) {
                    const existingSlots = zone.querySelectorAll('.multi-upload-item');
                    if (existingSlots.length < maxCount) {
                        createUploadSlot(zone, fieldName, placeholderImg, maxSize);
                    }
                }
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Scroll to last item
    setTimeout(function() {
        const lastItem = zone.querySelector('.multi-upload-item:last-child');
        if (lastItem) {
            lastItem.scrollIntoView({ behavior: 'smooth', inline: 'end', block: 'nearest' });
        }
    }, 100);
}

function addRemoveButton(itemWrapper, zone, fieldName, placeholderImg, maxSize) {
    const removeBtn = document.createElement('a');
    removeBtn.href = '#';
    removeBtn.className = 'multi-upload-remove';
    removeBtn.innerHTML = '<i class="tio-add-to-trash"></i>';
    
    removeBtn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        // Check if this is an existing image (has data-key attribute)
        const dataKey = itemWrapper.dataset.key;
        if (dataKey !== undefined) {
            // Handle existing image removal
            const removedInput = document.getElementById('removedImageKeysInput');
            if (removedInput) {
                let removedKeys = removedInput.value ? removedInput.value.split(',') : [];
                removedKeys.push(dataKey);
                removedInput.value = removedKeys.join(',');
            }
        }
        
        // Remove the item
        itemWrapper.remove();
        
        // Ensure at least one upload slot exists
        const existingSlots = zone.querySelectorAll('.multi-upload-item');
        const emptySlots = zone.querySelectorAll('.multi-upload-item:not(.has-image)');
        
        if (emptySlots.length === 0 && existingSlots.length < parseInt(zone.dataset.maxCount || 5)) {
            createUploadSlot(zone, fieldName, placeholderImg, maxSize);
        }
    });
    
    itemWrapper.appendChild(removeBtn);
}

function isValidImageFile(file) {
    // Check by MIME type
    const validMimeTypes = [
        'image/jpeg',
        'image/jpg',
        'image/png',
        'image/gif',
        'image/bmp',
        'image/webp',
        'image/tiff'
    ];
    
    if (validMimeTypes.includes(file.type.toLowerCase())) {
        return true;
    }
    
    // Fallback: check by file extension
    const validExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'tif', 'tiff'];
    const fileName = file.name.toLowerCase();
    const extension = fileName.split('.').pop();
    
    return validExtensions.includes(extension);
}

function showToast(type, message) {
    if (typeof toastr !== 'undefined') {
        toastr[type](message, {
            CloseButton: true,
            ProgressBar: true
        });
    } else {
        alert(message);
    }
}

// Setup existing images from edit page
function setupExistingImages(zone) {
    const existingItems = zone.querySelectorAll('.multi-upload-item.existing-image');
    const fieldName = zone.dataset.fieldName || 'item_images[]';
    const placeholderImg = zone.dataset.placeholder || '/public/assets/admin/img/upload-img.png';
    const maxSize = parseFloat(zone.dataset.maxSize) || 2;
    
    existingItems.forEach(item => {
        const removeBtn = item.querySelector('.spartan_remove_row, .function_remove_img');
        
        if (removeBtn) {
            removeBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Get the key for tracking removed images
                const dataKey = this.dataset.key || item.dataset.key;
                
                if (dataKey !== undefined) {
                    // Handle existing image removal - add key to hidden input
                    const removedInput = document.getElementById('removedImageKeysInput');
                    if (removedInput) {
                        let removedKeys = removedInput.value ? removedInput.value.split(',') : [];
                        if (!removedKeys.includes(dataKey)) {
                            removedKeys.push(dataKey);
                            removedInput.value = removedKeys.join(',');
                        }
                    }
                }
                
                // Remove the item
                item.remove();
                
                // Ensure at least one upload slot exists
                const existingSlots = zone.querySelectorAll('.multi-upload-item');
                const emptySlots = zone.querySelectorAll('.multi-upload-item:not(.has-image)');
                const maxCount = parseInt(zone.dataset.maxCount) || 5;
                
                if (emptySlots.length === 0 && existingSlots.length < maxCount) {
                    createUploadSlot(zone, fieldName, placeholderImg, maxSize);
                }
            });
        }
    });
}

// Re-initialize on dynamic content (for reset functionality)
window.reinitMultiImageUpload = function(selector) {
    const zone = document.querySelector(selector);
    if (zone) {
        // Clear all items except hidden inputs
        const hiddenInputs = zone.querySelectorAll('input[type="hidden"]');
        zone.innerHTML = '';
        
        // Re-add hidden inputs
        hiddenInputs.forEach(input => {
            input.value = ''; // Clear removed keys
            zone.appendChild(input);
        });
        
        zone.uploadedFiles = [];
        
        // Create fresh upload slot
        const fieldName = zone.dataset.fieldName || 'item_images[]';
        const placeholderImg = zone.dataset.placeholder || '/public/assets/admin/img/upload-img.png';
        const maxSize = parseFloat(zone.dataset.maxSize) || 2;
        
        createUploadSlot(zone, fieldName, placeholderImg, maxSize);
    }
};
