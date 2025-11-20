// Wishlist functionality
class WishlistManager {
    constructor() {
        this.baseUrl = '/Geotrans/';
        this.init();
    }

    init() {
        // Add click listeners for wishlist buttons
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('wishlist-btn') || e.target.closest('.wishlist-btn')) {
                e.preventDefault();
                e.stopPropagation();
                const btn = e.target.classList.contains('wishlist-btn') ? e.target : e.target.closest('.wishlist-btn');
                this.toggleWishlist(btn);
            }
        });
    }

    async toggleWishlist(button) {
        const productId = button.dataset.productId;
        const isInWishlist = button.classList.contains('in-wishlist');

        if (!productId) {
            console.error('Product ID not found');
            return;
        }

        // Disable button during request
        button.disabled = true;

        try {
            const formData = new FormData();
            formData.append('action', isInWishlist ? 'remove' : 'add');
            formData.append('product_id', productId);

            const response = await fetch(this.baseUrl + 'api/wishlist.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: isInWishlist ? 'remove' : 'add',
                    product_id: productId
                })
            });

            const data = await response.json();

            if (data.success) {
                // Toggle visual state
                if (isInWishlist) {
                    button.classList.remove('in-wishlist');
                    button.innerHTML = '<i class="far fa-heart"></i>'; // Empty heart
                    this.showNotification('Removed from wishlist', 'info');
                } else {
                    button.classList.add('in-wishlist');
                    button.innerHTML = '<i class="fas fa-heart text-red-500"></i>'; // Filled heart
                    this.showNotification('Added to wishlist!', 'success');
                }

                // Update wishlist count in header
                this.updateWishlistCount(data.wishlist_count);
            } else {
                if (data.message === 'Please login first') {
                    this.showNotification('Please login to add items to wishlist', 'warning');
                    setTimeout(() => {
                        window.location.href = 'login.php';
                    }, 1500);
                } else {
                    this.showNotification(data.message, 'error');
                }
            }
        } catch (error) {
            console.error('Error:', error);
            this.showNotification('An error occurred', 'error');
        } finally {
            button.disabled = false;
        }
    }

    updateWishlistCount(count) {
        const wishlistCounts = document.querySelectorAll('.wishlist-count');
        wishlistCounts.forEach(element => {
            element.textContent = count;
            if (count > 0) {
                element.classList.remove('hidden');
            } else {
                element.classList.add('hidden');
            }
        });
    }

    showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-20 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white transform transition-all duration-300 translate-x-0`;
        
        const colors = {
            success: 'bg-green-500',
            error: 'bg-red-500',
            warning: 'bg-yellow-500',
            info: 'bg-blue-500'
        };
        
        notification.classList.add(colors[type] || colors.info);
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 10);
        
        // Remove after 3 seconds
        setTimeout(() => {
            notification.style.transform = 'translateX(400px)';
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }
}

// Initialize wishlist manager when DOM is loaded
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        window.wishlistManager = new WishlistManager();
    });
} else {
    window.wishlistManager = new WishlistManager();
}
