# 🍽️ Quinoa Restaurant Management System

[![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3+-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com/)
[![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)](https://developer.mozilla.org/en-US/docs/Web/HTML)
[![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)](https://developer.mozilla.org/en-US/docs/Web/CSS)
[![License](https://img.shields.io/badge/License-Proprietary-red.svg)](LICENSE)

> A comprehensive restaurant management system for "Restaurante Quinoa" - a vegetarian and vegan restaurant. Features table reservations, menu management, user administration, and employee portals. **This project is part of my professional portfolio to demonstrate my development skills and practices.**

## 📋 Table of Contents

- [✨ Features](#-features)
- [🛠️ Technologies](#️-technologies)
- [📦 Installation](#-installation)
- [🎮 Usage](#-usage)
- [🏗️ Project Structure](#️-project-structure)
- [🔧 API Endpoints](#-api-endpoints)
- [🧪 Testing](#-testing)
- [📄 License](#-license)

## ✨ Features

### 🎯 Core Functionality
- **🍽️ Menu Management** - Complete CRUD operations for menu items with categories and pricing
- **📅 Reservation System** - Smart table allocation and reservation management
- **👥 User Management** - Multi-role system (Admin, Employee, Customer)
- **🏪 Table Management** - Dynamic table allocation based on capacity and availability
- **📊 Admin Dashboard** - Comprehensive management interface for restaurant operations

### 🎨 User Experience
- **📱 Responsive Design** - Mobile-first approach with Bootstrap 5
- **🎨 Modern UI** - Clean, professional interface with smooth animations
- **🔐 Secure Authentication** - Session-based user authentication and authorization
- **⚡ Real-time Updates** - Dynamic content loading and form validation
- **🌱 Vegetarian/Vegan Focus** - Specialized menu categories and dietary information

## 🛠️ Technologies

### Backend
| Technology | Version | Purpose |
|------------|---------|---------|
| [PHP](https://php.net/) | 7.2+ | Server-side scripting and business logic |
| [MySQL](https://www.mysql.com/) | 8.0+ | Relational database management |
| [MariaDB](https://mariadb.org/) | 10.11+ | Database server (production) |

### Frontend
| Technology | Version | Purpose |
|------------|---------|---------|
| [HTML5](https://developer.mozilla.org/en-US/docs/Web/HTML) | 5.0 | Markup structure |
| [CSS3](https://developer.mozilla.org/en-US/docs/Web/CSS) | 3.0 | Styling and layout |
| [Bootstrap](https://getbootstrap.com/) | 5.3+ | CSS framework and components |
| [JavaScript](https://developer.mozilla.org/en-US/docs/Web/JavaScript) | ES6+ | Client-side interactivity |

### Development Tools
- **AOS (Animate On Scroll)** - Smooth scroll animations
- **GLightbox** - Image gallery and lightbox functionality
- **Swiper** - Touch slider and carousel
- **Bootstrap Icons** - Icon library
- **PureCounter** - Animated counters

## 📦 Installation

### Prerequisites
- PHP 7.2 or higher
- MySQL 8.0 or MariaDB 10.11+
- Web server (Apache/Nginx)
- Composer (optional, for dependency management)

### Quick Start

1. **Clone the repository**
   ```bash
   git clone [url-del-repositorio]
   cd quinoa
   ```

2. **Set up the database**
   ```bash
   # Import the database schema
   mysql -u your_username -p your_database < quinoa.sql
   ```

3. **Configure database connection**
   ```bash
   # Edit config.php with your database credentials
   define('DB_SERVER', 'localhost');
   define('DB_USERNAME', 'your_username');
   define('DB_PASSWORD', 'your_password');
   define('DB_NAME', 'your_database');
   ```

4. **Set up web server**
   ```bash
   # For Apache, ensure mod_rewrite is enabled
   # For Nginx, configure proper routing
   ```

5. **Access the application**
   - Main Site: `http://localhost/quinoa/`
   - Admin Panel: `http://localhost/quinoa/indexAdmin.php`
   - Employee Panel: `http://localhost/quinoa/indexEmpleado.php`

## 🎮 Usage

### Getting Started
1. **Customer Registration/Login** - Create an account or log in to make reservations
2. **Browse Menu** - View vegetarian and vegan options with detailed descriptions
3. **Make Reservations** - Select date, time, and party size for table booking
4. **Admin Management** - Access admin panel for comprehensive restaurant management

### Key Features Usage

#### Making a Reservation
```php
// Example reservation process
$name = "John Doe";
$mail = "john@example.com";
$phone = "+1234567890";
$date = "2024-06-15";
$time = "19:00 - 20:00";
$people = 4;
$msg = "Window seat preferred";

if (reservar($name, $mail, $phone, $date, $time, $people, $msg)) {
    echo "Reservation confirmed!";
}
```

#### Menu Management
```php
// Add new menu item
$menuItem = [
    'name' => 'Quinoa Bowl',
    'descrip' => 'Fresh quinoa with seasonal vegetables',
    'category' => 'Principal',
    'price' => 12.99,
    'img' => 'img/food/quinoa_bowl.jpg',
    'state' => 1
];

agregarMenu($menuItem);
```

## 🏗️ Project Structure

```
quinoa/
├── 📁 assets/                    # Static assets
│   ├── 📁 css/                  # Stylesheets
│   ├── 📁 js/                   # JavaScript files
│   ├── 📁 img/                  # Images and icons
│   └── 📁 vendor/               # Third-party libraries
├── 📁 forms/                    # Form processing scripts
├── 📁 img/                      # Food and user images
│   └── 📁 food/                 # Menu item images
├── 🔧 config.php               # Database configuration
├── 🔧 consultas.php            # Database queries and functions
├── 🏠 index.php                # Main restaurant website
├── 👨‍💼 indexAdmin.php         # Admin dashboard
├── 👨‍💼 indexEmpleado.php      # Employee portal
├── 👤 indexCliente.php         # Customer portal
├── 🔐 iniciosesion.php         # Login system
├── 📝 registrocliente.php      # Customer registration
├── 🍽️ modificarMenu.php        # Menu management
├── 📅 modificarReserva.php     # Reservation management
└── 🗄️ quinoa.sql              # Database schema
```

## 🔧 API Endpoints

### User Management
- `POST /iniciosesion.php` - User authentication
- `POST /registrocliente.php` - Customer registration
- `GET /indexCliente.php` - Customer dashboard

### Reservation System
- `POST /index.php` - Create new reservation
- `GET /modificarReserva.php` - View/edit reservations
- `POST /modificarReserva.php` - Update reservation details

### Menu Management
- `GET /modificarMenu.php` - View menu items
- `POST /modificarMenu.php` - Add/edit menu items
- `DELETE /modificarMenu.php` - Remove menu items

### Admin Operations
- `GET /indexAdmin.php` - Admin dashboard
- `POST /indexAdmin.php` - User/table/menu management
- `GET /indexEmpleado.php` - Employee portal

## 🧪 Testing

### Running Tests
```bash
# Manual testing recommended for this PHP application
# Test user flows:
# 1. Customer registration and login
# 2. Menu browsing and reservation creation
# 3. Admin panel functionality
# 4. Employee portal operations
```

### Test Coverage
- ✅ User authentication and authorization
- ✅ Reservation system functionality
- ✅ Menu management operations
- ✅ Admin dashboard features
- ✅ Responsive design across devices
- ✅ Database operations and data integrity

## 📄 License

This project is proprietary software. All rights reserved. This code is made publicly available solely for portfolio demonstration purposes. See the [LICENSE](LICENSE) file for full terms and restrictions.

---

<div align="center">
  <p>
    <a href="#-quinoa-restaurant-management-system">Back to top</a>
  </p>
</div>
