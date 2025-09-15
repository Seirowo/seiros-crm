const fileInput = document.getElementById('logo_original');
const fileNameDisplay = document.getElementById('file-name-display');
const logoCroppedInput = document.getElementById('logo_cropped');
const cropModal = document.getElementById('cropModal');
const imageBox = document.getElementById('image-box');
const cropBtn = document.getElementById('cropBtn');
const cropCancelBtn = document.getElementById('cropCancelBtn');
const logoPreview = document.getElementById('logo-preview');
const previewContainer = document.getElementById('preview-container');
const deleteLogoBtn = document.getElementById('delete-logo-btn');
const deleteLogoFlag = document.getElementById('delete_logo_flag');
let cropper;

// Function to reset logo preview to placeholder
function showPlaceholder() {
    if (previewContainer) {
        previewContainer.classList.add('hidden');
    }
    // Tambahan untuk menangani placeholder di halaman edit jika ada
    const placeholderIcon = document.getElementById('placeholder-icon');
    if (placeholderIcon) {
        logoPreview.classList.add('hidden');
        placeholderIcon.classList.remove('hidden');
    }
    if (deleteLogoBtn) {
        deleteLogoBtn.classList.add('hidden');
    }
}

// Function to show actual logo preview
function showLogo(src) {
    if (previewContainer) {
        previewContainer.classList.remove('hidden');
    }
    logoPreview.src = src;
    logoPreview.classList.remove('hidden');
    // Tambahan untuk menyembunyikan placeholder di halaman edit jika ada
    const placeholderIcon = document.getElementById('placeholder-icon');
    if (placeholderIcon) {
        placeholderIcon.classList.add('hidden');
    }
    if (deleteLogoBtn) {
        deleteLogoBtn.classList.remove('hidden');
    }
}

// Cek apakah data dari PHP ada (untuk halaman 'edit')
if (window.appData && window.appData.companyHasLogo) {
    showLogo(window.appData.companyLogoUrl);
} else {
    // Jalankan ini jika tidak ada data appData atau tidak ada logo (untuk halaman 'create' dan 'edit' tanpa logo)
    showPlaceholder();
}

fileInput.addEventListener('change', (event) => {
    const file = event.target.files[0];
    if (deleteLogoFlag) {
        deleteLogoFlag.value = '0'; // Reset delete flag when a new file is chosen
    }

    if (file) {
        fileNameDisplay.textContent = file.name;
        
        const reader = new FileReader();
        reader.onload = function(e) {
            showLogo(e.target.result);
            
            imageBox.src = e.target.result;
            cropModal.classList.remove('hidden');
            if (cropper) {
                cropper.destroy();
            }
            cropper = new Cropper(imageBox, {
                aspectRatio: 1,
                viewMode: 1,
            });
        };
        reader.readAsDataURL(file);
    } else {
        fileNameDisplay.textContent = 'No file chosen';
        if (window.appData && window.appData.companyHasLogo) {
            showLogo(window.appData.companyLogoUrl);
        } else {
            showPlaceholder();
        }
        logoCroppedInput.value = '';
    }
});

cropBtn.addEventListener('click', () => {
    const croppedImage = cropper.getCroppedCanvas({
        width: 200,
        height: 200,
    }).toDataURL('image/jpeg');

    logoCroppedInput.value = croppedImage;
    showLogo(croppedImage);
    
    cropModal.classList.add('hidden');
    if (cropper) {
        cropper.destroy();
    }
});

cropCancelBtn.addEventListener('click', () => {
    cropModal.classList.add('hidden');
    fileInput.value = '';
    fileNameDisplay.textContent = 'No file chosen';
    if (deleteLogoFlag) {
        deleteLogoFlag.value = '0';
    }

    if (window.appData && window.appData.companyHasLogo) {
        showLogo(window.appData.companyLogoUrl);
    } else {
        showPlaceholder();
    }
    
    if (cropper) {
        cropper.destroy();
    }
});

if (deleteLogoBtn) {
    deleteLogoBtn.addEventListener('click', () => {
        if (confirm('Are you sure you want to delete the company logo?')) {
            if (deleteLogoFlag) {
                deleteLogoFlag.value = '1';
            }
            logoCroppedInput.value = '';
            fileInput.value = '';
            fileNameDisplay.textContent = 'No file chosen';
            showPlaceholder();
        }
    });
}