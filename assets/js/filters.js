// Product filtering functionality
class ProductFilter {
    constructor() {
        this.productsContainer = document.getElementById('products-grid');
        this.filtersForm = document.getElementById('filters-form');
        this.sortSelect = document.getElementById('sort-select');
        this.currentPage = 1;
        this.filters = {};
        this.init();
    }

    init() {
        if (!this.productsContainer) return;

        // Listen to filter changes
        if (this.filtersForm) {
            this.filtersForm.addEventListener('change', () => {
                this.currentPage = 1;
                this.applyFilters();
            });
        }

        // Listen to sort changes
        if (this.sortSelect) {
            this.sortSelect.addEventListener('change', () => {
                this.applyFilters();
            });
        }

        // Price range sliders
        const minPriceInput = document.getElementById('min-price');
        const maxPriceInput = document.getElementById('max-price');
        
        if (minPriceInput && maxPriceInput) {
            let priceDebounce;
            [minPriceInput, maxPriceInput].forEach(input => {
                input.addEventListener('input', () => {
                    clearTimeout(priceDebounce);
                    priceDebounce = setTimeout(() => {
                        this.currentPage = 1;
                        this.applyFilters();
                    }, 500);
                });
            });
        }
    }

    getFilters() {
        const filters = {
            page: this.currentPage
        };

        // Category filter
        const categoryInputs = document.querySelectorAll('input[name="category"]:checked');
        if (categoryInputs.length > 0) {
            filters.category = Array.from(categoryInputs).map(input => input.value).join(',');
        }

        // Brand filter
        const brandInputs = document.querySelectorAll('input[name="brand"]:checked');
        if (brandInputs.length > 0) {
            filters.brand = Array.from(brandInputs).map(input => input.value).join(',');
        }

        // Price range
        const minPrice = document.getElementById('min-price');
        const maxPrice = document.getElementById('max-price');
        
        if (minPrice && minPrice.value) {
            filters.min_price = minPrice.value;
        }
        if (maxPrice && maxPrice.value) {
            filters.max_price = maxPrice.value;
        }

        // Sort
        if (this.sortSelect) {
            filters.sort = this.sortSelect.value;
        }

        // Search
        const searchParam = new URLSearchParams(window.location.search).get('search');
        if (searchParam) {
            filters.search = searchParam;
        }

        return filters;
    }

    async applyFilters() {
        this.showLoading();
        
        const filters = this.getFilters();
        const queryString = new URLSearchParams(filters).toString();

        try {
            const response = await fetch(`api/products.php?${queryString}`);
            const data = await response.json();

            if (data.success) {
                this.displayProducts(data.products);
                this.updatePagination(data.pagination);
            } else {
                this.showError('Failed to load products');
            }
        } catch (error) {
            console.error('Filter error:', error);
            this.showError('An error occurred');
        }
    }

    displayProducts(products) {
        if (products.length === 0) {
            this.productsContainer.innerHTML = `
                <div class="col-span-full text-center py-12">
                    <i class="fas fa-box-open text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">No products found</p>
                </div>
            `;
            return;
        }

        let html = '';
        products.forEach(product => {
            const priceDisplay = product.sale_price ? `
                <div class="flex items-center gap-2">
                    <span class="text-lg font-bold text-gray-900">${this.formatPrice(product.sale_price)}</span>
                    <span class="text-sm text-gray-400 line-through">${this.formatPrice(product.price)}</span>
                </div>
                ${product.discount > 0 ? `<span class="text-xs text-red-500 font-semibold">${product.discount}% OFF</span>` : ''}
            ` : `
                <span class="text-lg font-bold text-gray-900">${this.formatPrice(product.price)}</span>
            `;

            html += `
                <div class="bg-white rounded-lg shadow-sm hover:shadow-lg transition p-4">
                    <a href="product_detail.php?slug=${product.slug}">
                        <img src="${product.image}" alt="${product.name}" class="w-full h-48 object-cover rounded mb-3">
                    </a>
                    <div class="text-xs text-gray-500 mb-1">${product.brand}</div>
                    <a href="product_detail.php?slug=${product.slug}" class="text-sm font-medium text-gray-900 hover:text-[#680e68] line-clamp-2 mb-2">
                        ${product.name}
                    </a>
                    <div class="flex items-center mb-2">
                        <div class="flex items-center text-yellow-400">
                            ${this.renderStars(product.rating)}
                        </div>
                        <span class="text-xs text-gray-500 ml-2">(${product.rating})</span>
                    </div>
                    ${priceDisplay}
                    <button class="add-to-cart-btn w-full mt-3 bg-purple-custom hover:bg-[#4f0a4f] text-white py-2 px-4 rounded transition" data-product-id="${product.id}">
                        <i class="fas fa-shopping-cart mr-2"></i>Add to Cart
                    </button>
                </div>
            `;
        });

        this.productsContainer.innerHTML = html;
    }

    renderStars(rating) {
        const fullStars = Math.floor(rating);
        const hasHalfStar = rating % 1 >= 0.5;
        let stars = '';

        for (let i = 0; i < fullStars; i++) {
            stars += '<i class="fas fa-star"></i>';
        }
        if (hasHalfStar) {
            stars += '<i class="fas fa-star-half-alt"></i>';
        }
        for (let i = fullStars + (hasHalfStar ? 1 : 0); i < 5; i++) {
            stars += '<i class="far fa-star"></i>';
        }

        return stars;
    }

    updatePagination(pagination) {
        const paginationContainer = document.getElementById('pagination');
        if (!paginationContainer) return;

        let html = '';
        
        // Previous button
        html += `
            <button class="px-4 py-2 border rounded ${pagination.current_page === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-100'}" 
                    ${pagination.current_page === 1 ? 'disabled' : ''} 
                    onclick="productFilter.goToPage(${pagination.current_page - 1})">
                &lt;
            </button>
        `;

        // Page numbers
        for (let i = 1; i <= pagination.total_pages; i++) {
            if (i === 1 || i === pagination.total_pages || (i >= pagination.current_page - 1 && i <= pagination.current_page + 1)) {
                html += `
                    <button class="px-4 py-2 rounded ${i === pagination.current_page ? 'bg-purple-custom text-white' : 'border hover:bg-gray-100'}" 
                            onclick="productFilter.goToPage(${i})">
                        ${i}
                    </button>
                `;
            } else if (i === pagination.current_page - 2 || i === pagination.current_page + 2) {
                html += '<span class="px-2">...</span>';
            }
        }

        // Next button
        html += `
            <button class="px-4 py-2 border rounded ${pagination.current_page === pagination.total_pages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-100'}" 
                    ${pagination.current_page === pagination.total_pages ? 'disabled' : ''} 
                    onclick="productFilter.goToPage(${pagination.current_page + 1})">
                &gt;
            </button>
        `;

        paginationContainer.innerHTML = html;
    }

    goToPage(page) {
        this.currentPage = page;
        this.applyFilters();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    showLoading() {
        this.productsContainer.innerHTML = `
            <div class="col-span-full text-center py-12">
                <i class="fas fa-spinner fa-spin text-4xl text-[#680e68]"></i>
                <p class="text-gray-500 mt-4">Loading products...</p>
            </div>
        `;
    }

    showError(message) {
        this.productsContainer.innerHTML = `
            <div class="col-span-full text-center py-12">
                <i class="fas fa-exclamation-triangle text-4xl text-red-500 mb-4"></i>
                <p class="text-gray-500">${message}</p>
            </div>
        `;
    }

    formatPrice(price) {
        return 'Rs' + parseFloat(price).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
}

// Initialize product filter when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.productFilter = new ProductFilter();
});
