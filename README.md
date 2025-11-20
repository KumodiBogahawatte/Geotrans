# GeoTrans E-Commerce Platform

A full-featured e-commerce platform for office automation products built with PHP, MySQL, and modern web technologies.

## 🚀 Features

- **Product Management**: Browse products with categories, brands, and filters
- **Shopping Cart**: Add/remove items, update quantities
- **Wishlist**: Save favorite products
- **User Authentication**: Register, login, profile management
- **Order Management**: Place orders, track status, view history
- **Search**: Real-time product search with category filtering
- **Testimonials**: Customer feedback and reviews
- **Admin Panel**: Manage products, orders, users, and settings
- **Responsive Design**: Mobile-first design with Tailwind CSS
- **Animations**: Smooth Dribbble-inspired animations

## 📁 Project Structure

```
Geotrans/
├── index.php                 # Homepage
├── products.php              # Product listing
├── product_detail.php        # Single product view
├── cart.php                  # Shopping cart
├── checkout.php              # Checkout process
├── login.php / register.php  # Authentication
├── order-tracking.php        # Track orders
│
├── account/                  # User account pages
│   ├── profile.php           # User profile
│   ├── orders.php            # Order history
│   └── addresses.php         # Shipping addresses
│
├── admin/                    # Admin dashboard
│   ├── dashboard.php         # Admin home
│   ├── products.php          # Product management
│   ├── orders.php            # Order management
│   ├── users.php             # User management
│   └── testimonials.php      # Testimonial management
│
├── api/                      # Backend API endpoints
│   ├── cart.php              # Cart operations
│   ├── wishlist.php          # Wishlist operations
│   ├── search.php            # Product search
│   ├── newsletter.php        # Newsletter subscription
│   └── submit-review.php     # Product reviews
│
├── classes/                  # PHP Classes (Database Layer)
│   ├── Product.php           # Product operations
│   ├── Cart.php              # Cart logic
│   ├── Order.php             # Order management
│   ├── User.php              # User authentication
│   ├── Category.php          # Category management
│   ├── Brand.php             # Brand management
│   ├── Wishlist.php          # Wishlist operations
│   └── Testimonial.php       # Testimonial management
│
├── assets/
│   ├── css/                  # Stylesheets
│   ├── js/                   # JavaScript files
│   │   ├── cart.js           # Cart functionality
│   │   ├── search.js         # Search functionality
│   │   └── wishlist.js       # Wishlist functionality
│   └── images/               # Product images, logos, etc.
│
├── includes/                 # Shared components
│   ├── header.php            # Site header
│   ├── footer.php            # Site footer
│   ├── helpers.php           # Helper functions
│   └── currency.php          # Currency conversion
│
└── config/
    ├── database.php          # Database configuration
    └── email.php             # Email settings
```

## 🔧 Technology Stack

### Frontend
- **HTML5/CSS3**: Semantic markup and styling
- **Tailwind CSS**: Utility-first CSS framework
- **JavaScript (ES6+)**: Modern JavaScript features
- **Font Awesome**: Icon library
- **AJAX/Fetch API**: Asynchronous requests

### Backend
- **PHP 7.4+**: Server-side scripting
- **MySQL**: Database management
- **PDO**: Database abstraction layer
- **Session Management**: User state handling

### Architecture Pattern
- **MVC-inspired**: Separation of concerns
- **RESTful API**: JSON-based endpoints
- **OOP**: Object-oriented programming

## 🏗️ How It Works

### Data Flow Architecture

```
┌─────────────────────────────────────────────────┐
│              USER INTERFACE (Pages)              │
│  index.php, products.php, cart.php, etc.        │
└───────────────────┬─────────────────────────────┘
                    │
                    ↓
┌─────────────────────────────────────────────────┐
│         CLIENT-SIDE LOGIC (JavaScript)          │
│  cart.js, search.js, wishlist.js                │
│  - Event handling                                │
│  - AJAX requests                                 │
│  - DOM manipulation                              │
└───────────────────┬─────────────────────────────┘
                    │
                    ↓
┌─────────────────────────────────────────────────┐
│           API LAYER (api/*.php)                 │
│  - Receives HTTP requests                       │
│  - Validates input                               │
│  - Calls class methods                           │
│  - Returns JSON responses                        │
└───────────────────┬─────────────────────────────┘
                    │
                    ↓
┌─────────────────────────────────────────────────┐
│         BUSINESS LOGIC (classes/*.php)          │
│  - Database operations (CRUD)                   │
│  - Data validation                               │
│  - Business rules                                │
└───────────────────┬─────────────────────────────┘
                    │
                    ↓
┌─────────────────────────────────────────────────┐
│              DATABASE (MySQL)                    │
│  Products, Users, Orders, Cart, etc.            │
└─────────────────────────────────────────────────┘
```

### Example: Adding Product to Cart

1. **User Action**: Clicks "Add to Cart" button on `product_detail.php`
2. **JavaScript**: `cart.js` captures the click event
3. **API Request**: Sends POST request to `api/cart.php` with product ID
4. **API Processing**: 
   - Validates user session
   - Calls `Cart->addItem()` method
5. **Database**: Cart class executes SQL INSERT/UPDATE
6. **Response**: API returns JSON with success status
7. **UI Update**: JavaScript updates cart count badge and shows notification

### Session Management

- **Logged-in Users**: `user_id` stored in session
- **Guest Users**: `session_id` for cart persistence
- **Cart Merge**: Guest cart items merge with user cart on login

## 📦 Installation

1. **Clone Repository**
```bash
git clone https://github.com/KumodiBogahawatte/Geotrans-.git
cd Geotrans
```

2. **Setup Database**
```bash
# Import database schema
mysql -u root -p < config/geotrans_database.sql
```

3. **Configure Database**
```php
// config/database.php
define('DB_HOST', 'localhost');
define('DB_NAME', 'geotrans_db');
define('DB_USER', 'root');
define('DB_PASS', '');
```

4. **Start Server**
```bash
# Using XAMPP
# Place project in C:\xampp\htdocs\Geotrans
# Start Apache and MySQL
# Visit: http://localhost/Geotrans/
```

## 🔐 Security Features

- **SQL Injection Prevention**: PDO prepared statements
- **XSS Protection**: `htmlspecialchars()` for output
- **Session Security**: Session hijacking prevention
- **Password Hashing**: `password_hash()` for user passwords
- **CSRF Protection**: Token validation for forms
- **Input Validation**: Server-side validation for all inputs

## 🎨 Key Features Explained

### Real-time Search
- Live search as user types
- Category-based filtering
- Debounced API calls for performance

### Dynamic Cart
- No page reload required
- Instant count updates
- Session-based persistence

### Order Tracking
- URL parameter support (`?order=ORDER123`)
- Dynamic status timeline
- Real-time updates

### Responsive Design
- Mobile-first approach
- Breakpoints: sm (640px), md (768px), lg (1024px), xl (1280px)
- Touch-friendly UI elements

### Animations
- Scroll-triggered animations
- Hover effects on cards
- Smooth transitions
- Dribbble-inspired micro-interactions

## 🛠️ API Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| `api/cart.php?action=add` | POST | Add item to cart |
| `api/cart.php?action=remove` | POST | Remove item |
| `api/cart.php?action=update` | POST | Update quantity |
| `api/cart.php?action=count` | GET | Get cart count |
| `api/wishlist.php` | POST | Add/remove wishlist |
| `api/search.php?q=query` | GET | Search products |
| `api/newsletter.php` | POST | Subscribe to newsletter |

## 👥 User Roles

### Customer
- Browse and search products
- Add to cart and wishlist
- Place orders
- Track orders
- Submit reviews

### Admin
- Full CRUD for products, categories, brands
- Order management
- User management
- View analytics
- Manage testimonials

## 📱 Responsive Breakpoints

```css
sm:  640px  /* Small devices */
md:  768px  /* Medium devices */
lg:  1024px /* Large devices */
xl:  1280px /* Extra large devices */
```

## 🔄 State Management

- **PHP Sessions**: User authentication, cart data
- **LocalStorage**: Recently viewed products
- **Cookies**: Remember me functionality
- **URL Parameters**: Filters, pagination, search

## 🚦 Status Codes

### Order Status
- `Pending`: Order placed, awaiting processing
- `Processing`: Order being prepared
- `Shipped`: Order dispatched
- `Delivered`: Order completed
- `Cancelled`: Order cancelled

## 📊 Database Schema Highlights

- **users**: Customer accounts
- **products**: Product catalog
- **categories**: Product categories
- **brands**: Product brands
- **cart**: Shopping cart items
- **orders**: Order information
- **order_items**: Order line items
- **wishlist**: Customer wishlists
- **testimonials**: Customer reviews

## 🎯 Future Enhancements

- [ ] Payment gateway integration
- [ ] Email notifications
- [ ] Product reviews/ratings
- [ ] Coupon/discount system
- [ ] Multi-currency support
- [ ] Advanced analytics
- [ ] Inventory management
- [ ] Product recommendations

## 📝 License

This project is part of academic coursework.

## 👨‍💻 Developer

**Kumodi Bogahawatte**
- GitHub: [@KumodiBogahawatte](https://github.com/KumodiBogahawatte)

## 🤝 Contributing

This is an academic project. For suggestions or issues, please contact the developer.

---

**Built SLTDS**