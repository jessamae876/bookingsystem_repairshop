//MOBILE MENU TOGGLE
function toggleMobileMenu() {
    const mobileNav = document.getElementById('mobileNav');
    const menuIcon = document.getElementById('menuIcon');
    const closeIcon = document.getElementById('closeIcon');

    if (!mobileNav) return;

    mobileNav.classList.toggle('hidden');

    if (menuIcon && closeIcon) {
        menuIcon.classList.toggle('hidden');
        closeIcon.classList.toggle('hidden');
    }
}

//CLOSE MOBILE MENU
function closeMobileMenu() {
    const mobileNav = document.getElementById('mobileNav');
    const menuIcon = document.getElementById('menuIcon');
    const closeIcon = document.getElementById('closeIcon');

    if (!mobileNav) return;

    mobileNav.classList.add('hidden');

    if (menuIcon && closeIcon) {
        menuIcon.classList.remove('hidden');
        closeIcon.classList.add('hidden');
    }
}

// TRACK REPAIR
function trackRepair() {
    const input = document.getElementById('ticketNumber');
    if (!input) return;

    const ticket = input.value.trim();
    if (ticket === '') {
        alert('Please enter a ticket number.');
        return;
    }

    window.location.href = `book-repair.html?ticket=${ticket}`;
}

// Inside trackRepair() function
const data = await response.json();
if (data.success) {
    resultDiv.innerHTML = `<strong>Status:</strong> ${data.data.status || 'Details loaded.'}`;  // Adjust 'status' to match your DB column
} else {
    resultDiv.innerHTML = data.message;
}
// trackRepair() function
const response = await fetch(`/website/track.php?ticketNumber=${encodeURIComponent(ticket)}`, {
    headers: {
        'X-Requested-With': 'XMLHttpRequest'  // This tells the server it's AJAX
    }
});
// images preview
function previewMultipleImages(event) {
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = ''; // Clear previous previews
    const files = event.target.files;

    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const reader = new FileReader();

        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.style.width = '80px';
            img.style.height = '80px';
            img.style.objectFit = 'cover';
            img.style.margin = '5px';
            img.style.borderRadius = '6px';
            preview.appendChild(img);
        };

        reader.readAsDataURL(file);
    }
}
