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
                const isDetail = button.classList.contains('wishlist-btn--detail');
                // Toggle visual state (match PHP + wishlist-buttons.css)
                if (isInWishlist) {
                    button.classList.remove('in-wishlist');
                    button.innerHTML = isDetail
                        ? '<i class="far fa-heart"></i>'
                        : '<i class="far fa-heart text-base" aria-hidden="true"></i>';
                    button.setAttribute('aria-pressed', 'false');
                    button.setAttribute('aria-label', 'Add to wishlist');
                    button.setAttribute('title', 'Add to wishlist');
                    this.showNotification('Removed from wishlist', 'info');
                } else {
                    button.classList.add('in-wishlist');
                    button.innerHTML = isDetail
                        ? '<i class="fas fa-heart"></i>'
                        : '<i class="fas fa-heart text-red-500" aria-hidden="true"></i>';
                    button.setAttribute('aria-pressed', 'true');
                    button.setAttribute('aria-label', 'Remove from wishlist');
                    button.setAttribute('title', 'Remove from wishlist');
                    this.showNotification('Added to wishlist!', 'success');
                }

                if (data.wishlist_count !== undefined && data.wishlist_count !== null) {
                    this.updateWishlistCount(data.wishlist_count);
                }
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
        const n = Number(count);
        if (Number.isNaN(n) || n < 0) {
            return;
        }
        const wishlistCounts = document.querySelectorAll('.wishlist-count');
        wishlistCounts.forEach(element => {
            element.textContent = String(n);
            if (n > 0) {
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

function initWishlistManager() {
    window.wishlistManager = new WishlistManager();
    /**
     * Legacy onclick="addToWishlist(id)" support — delegates to WishlistManager.
     * Prefer: <button type="button" class="wishlist-btn" data-product-id="...">
     */
    window.addToWishlist = async function (productId) {
        const mgr = window.wishlistManager;
        if (!mgr) {
            return;
        }
        const id = String(productId);
        const existing = document.querySelector('.wishlist-btn[data-product-id="' + id + '"]');
        if (existing) {
            await mgr.toggleWishlist(existing);
            return;
        }
        const ghost = document.createElement('button');
        ghost.type = 'button';
        ghost.className = 'wishlist-btn';
        ghost.dataset.productId = id;
        ghost.setAttribute('aria-hidden', 'true');
        ghost.style.cssText = 'position:fixed!important;left:-9999px!important;width:1px!important;height:1px!important;opacity:0!important;pointer-events:none!important;';
        document.body.appendChild(ghost);
        try {
            await mgr.toggleWishlist(ghost);
        } finally {
            ghost.remove();
        }
    };
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initWishlistManager);
} else {
    initWishlistManager();
}
