// Main JavaScript File

document.addEventListener('DOMContentLoaded', function() {

    // ========================================
    // Back to Top Button
    // ========================================
    const backToTopBtn = document.getElementById('backToTop');

    if (backToTopBtn) {
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTopBtn.classList.add('show');
            } else {
                backToTopBtn.classList.remove('show');
            }
        });

        backToTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    // ========================================
    // Mobile Menu Toggle
    // ========================================
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const navbarNav = document.querySelector('.navbar-nav');

    if (mobileMenuToggle && navbarNav) {
        mobileMenuToggle.addEventListener('click', function() {
            navbarNav.classList.toggle('active');
            this.classList.toggle('active');
        });
    }

    // ========================================
    // Close Dropdown When Clicking Outside
    // ========================================
    document.addEventListener('click', function(event) {
        const dropdowns = document.querySelectorAll('.dropdown');

        dropdowns.forEach(dropdown => {
            if (!dropdown.contains(event.target)) {
                dropdown.classList.remove('active');
            }
        });
    });

    // ========================================
    // Form Validation Helper
    // ========================================
    const forms = document.querySelectorAll('form[data-validate]');

    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('error');
                } else {
                    field.classList.remove('error');
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields');
            }
        });
    });

    // ========================================
    // Confirm Delete Actions
    // ========================================
    const deleteButtons = document.querySelectorAll('[data-confirm]');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const message = this.getAttribute('data-confirm') || 'Are you sure you want to delete this item?';
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });

    // ========================================
    // Auto-update Cart Count (Optional)
    // ========================================
    function updateCartCount() {
        fetch('/api/cart-count.php')
            .then(response => response.json())
            .then(data => {
                const cartBadge = document.querySelector('.cart-badge');
                if (cartBadge && data.count !== undefined) {
                    cartBadge.textContent = data.count;
                }
            })
            .catch(error => console.error('Error updating cart count:', error));
    }

    // ========================================
    // Image Lazy Loading
    // ========================================
    const images = document.querySelectorAll('img[data-src]');

    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                observer.unobserve(img);
            }
        });
    });

    images.forEach(img => imageObserver.observe(img));

});