// Cart functionality
class CartManager {
    constructor() {
        this.cartCountElement = document.getElementById('cart-count');
        this.baseUrl = '/SLTDS/Geotrans/';
        this.init();
    }

    init() {
        // Add to cart buttons
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('add-to-cart-btn') || e.target.closest('.add-to-cart-btn')) {
                const btn = e.target.classList.contains('add-to-cart-btn') ? e.target : e.target.closest('.add-to-cart-btn');
                this.addToCart(btn);
            }
        });

        // Update cart count on page load
        this.updateCartCount();
    }

    async addToCart(buttonOrProductId, quantityParam = null) {
        let productId, quantity, button;
        
        // Check if first parameter is a button element or product ID
        if (typeof buttonOrProductId === 'object' && buttonOrProductId.dataset) {
            // It's a button element
            button = buttonOrProductId;
            productId = button.dataset.productId;
            quantity = button.dataset.quantity || 1;
        } else {
            // It's a product ID
            productId = buttonOrProductId;
            quantity = quantityParam || 1;
            button = null;
        }

        if (!productId) {
            console.error('Product ID not found');
            return;
        }

        // Disable button during request if button exists
        if (button) {
            button.disabled = true;
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
        }

        try {
            const formData = new FormData();
            formData.append('action', 'add');
            formData.append('product_id', productId);
            formData.append('quantity', quantity);

            const response = await fetch(this.baseUrl + 'api/cart.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                // Show success message
                this.showNotification('Product added to cart!', 'success');
                
                // Update cart count
                if (this.cartCountElement && data.cart_count) {
                    this.cartCountElement.textContent = data.cart_count;
                    this.cartCountElement.style.display = data.cart_count > 0 ? 'inline-block' : 'none';
                }

                // Animate button if it exists
                if (button) {
                    button.innerHTML = '<i class="fas fa-check"></i> Added!';
                    setTimeout(() => {
                        button.innerHTML = originalText;
                        button.disabled = false;
                    }, 2000);
                }
            } else {
                this.showNotification(data.message || 'Failed to add product', 'error');
                if (button) {
                    button.innerHTML = originalText;
                    button.disabled = false;
                }
            }
        } catch (error) {
            console.error('Error adding to cart:', error);
            this.showNotification('An error occurred', 'error');
            if (button) {
                button.innerHTML = originalText;
                button.disabled = false;
            }
        }
    }

    async updateCartCount() {
        try {
            const response = await fetch(this.baseUrl + 'api/cart.php?action=get');
            const data = await response.json();

            if (data.success && this.cartCountElement) {
                this.cartCountElement.textContent = data.cart_count;
                this.cartCountElement.style.display = data.cart_count > 0 ? 'inline-block' : 'none';
            }
        } catch (error) {
            console.error('Error updating cart count:', error);
        }
    }

    async updateQuantity(cartId, quantity) {
        try {
            const formData = new FormData();
            formData.append('action', 'update');
            formData.append('cart_id', cartId);
            formData.append('quantity', quantity);

            const response = await fetch(this.baseUrl + 'api/cart.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                this.showNotification('Cart updated', 'success');
                return data;
            } else {
                this.showNotification(data.message || 'Failed to update cart', 'error');
                return null;
            }
        } catch (error) {
            console.error('Error updating cart:', error);
            this.showNotification('An error occurred', 'error');
            return null;
        }
    }

    async removeItem(cartId) {
        if (!confirm('Remove this item from cart?')) {
            return;
        }

        try {
            const formData = new FormData();
            formData.append('action', 'remove');
            formData.append('cart_id', cartId);

            const response = await fetch(this.baseUrl + 'api/cart.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                this.showNotification('Item removed', 'success');
                
                // Update cart count
                if (this.cartCountElement && data.cart_count !== undefined) {
                    this.cartCountElement.textContent = data.cart_count;
                    this.cartCountElement.style.display = data.cart_count > 0 ? 'inline-block' : 'none';
                }

                return data;
            } else {
                this.showNotification(data.message || 'Failed to remove item', 'error');
                return null;
            }
        } catch (error) {
            console.error('Error removing item:', error);
            this.showNotification('An error occurred', 'error');
            return null;
        }
    }

    showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white transform transition-all duration-300 ${
            type === 'success' ? 'bg-green-500' : 
            type === 'error' ? 'bg-red-500' : 
            'bg-blue-500'
        }`;
        notification.textContent = message;
        notification.style.opacity = '0';
        notification.style.transform = 'translateY(-20px)';

        document.body.appendChild(notification);

        // Animate in
        setTimeout(() => {
            notification.style.opacity = '1';
            notification.style.transform = 'translateY(0)';
        }, 10);

        // Remove after 3 seconds
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transform = 'translateY(-20px)';
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 3000);
    }
}

// Initialize cart manager when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.cartManager = new CartManager();
});
