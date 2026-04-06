// Laptop brands submenu toggle
document.addEventListener('DOMContentLoaded', function() {
    var showBrandsBtn = document.getElementById('show-brands-btn');
    var brandsMenu = document.getElementById('laptop-brands-menu');
    if (showBrandsBtn && brandsMenu) {
        showBrandsBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            brandsMenu.classList.toggle('hidden');
        });
        document.addEventListener('click', function(e) {
            if (!brandsMenu.contains(e.target) && !showBrandsBtn.contains(e.target)) {
                brandsMenu.classList.add('hidden');
            }
        });
    }
});
// Category dropdown toggle
// Removed duplicate All Categories dropdown toggle logic (handled in header.php)
// Search functionality
class SearchManager {
    constructor() {
        this.searchInput = document.getElementById('search-input');
        this.categorySelect = document.getElementById('category-select');
        this.searchResults = document.getElementById('search-results');
        this.searchDebounce = null;
        this.baseUrl = '/Geotrans/';
        this.init();
    }

    init() {
        if (!this.searchInput) return;

        this.searchInput.addEventListener('input', (e) => {
            clearTimeout(this.searchDebounce);
            const query = e.target.value.trim();

            if (query.length < 2) {
                this.hideResults();
                return;
            }

            this.searchDebounce = setTimeout(() => {
                this.search(query);
            }, 300);
        });

        // Update search when category changes
        if (this.categorySelect) {
            this.categorySelect.addEventListener('change', () => {
                const query = this.searchInput.value.trim();
                if (query.length >= 2) {
                    this.search(query);
                }
            });
        }

        // Close search results when clicking outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.search-container')) {
                this.hideResults();
            }
        });

        // Close on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.hideResults();
            }
        });
    }

    async search(query) {
        try {
            const category = this.categorySelect ? this.categorySelect.value : '';
            let url = `${this.baseUrl}api/search.php?q=${encodeURIComponent(query)}`;
            
            if (category) {
                url += `&category=${encodeURIComponent(category)}`;
            }
            
            const response = await fetch(url);
            const data = await response.json();

            if (data.success && data.results) {
                this.displayResults(data.results);
            } else {
                this.displayNoResults();
            }
        } catch (error) {
            console.error('Search error:', error);
            this.displayError();
        }
    }

    displayResults(results) {
        if (!this.searchResults) {
            this.createResultsContainer();
        }

        if (results.length === 0) {
            this.displayNoResults();
            return;
        }

        let html = '<div class="search-results-list">';
        
        results.forEach(product => {
            const price = this.formatPrice(product.price);
            html += `
                <a href="product_detail.php?slug=${product.slug}" class="search-result-item flex items-center p-3 hover:bg-gray-100 transition">
                    <img src="${product.image}" alt="${product.name}" class="w-12 h-12 object-cover rounded mr-3">
                    <div class="flex-1">
                        <div class="text-sm font-medium text-gray-900">${product.name}</div>
                        <div class="text-xs text-gray-500">${product.brand} • ${product.category}</div>
                    </div>
                    <div class="text-sm font-bold text-[#680e68]">${price}</div>
                </a>
            `;
        });

        html += '</div>';
        this.searchResults.innerHTML = html;
        this.showResults();
    }

    displayNoResults() {
        if (!this.searchResults) {
            this.createResultsContainer();
        }

        this.searchResults.innerHTML = `
            <div class="p-4 text-center text-gray-500">
                <i class="fas fa-search text-2xl mb-2"></i>
                <p>No products found</p>
            </div>
        `;
        this.showResults();
    }

    displayError() {
        if (!this.searchResults) {
            this.createResultsContainer();
        }

        this.searchResults.innerHTML = `
            <div class="p-4 text-center text-red-500">
                <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                <p>An error occurred while searching</p>
            </div>
        `;
        this.showResults();
    }

    createResultsContainer() {
        this.searchResults = document.createElement('div');
        this.searchResults.id = 'search-results';
        this.searchResults.className = 'absolute top-full left-0 right-0 bg-white mt-2 rounded-lg shadow-lg max-h-96 overflow-y-auto z-50 hidden';
        this.searchInput.parentElement.style.position = 'relative';
        this.searchInput.parentElement.appendChild(this.searchResults);
    }

    showResults() {
        if (this.searchResults) {
            this.searchResults.classList.remove('hidden');
        }
    }

    hideResults() {
        if (this.searchResults) {
            this.searchResults.classList.add('hidden');
        }
    }

    formatPrice(price) {
        return 'Rs' + parseFloat(price).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
}

// Initialize search manager when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.searchManager = new SearchManager();
});
