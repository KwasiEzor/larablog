# Laravel Blog Application

A modern, feature-rich blog application built with Laravel 12, featuring a powerful admin dashboard powered by Filament, comprehensive role-based access control, and a beautiful user interface.

## 🚀 Features

### Core Blog Features
- **Post Management**: Create, edit, and publish blog posts with rich text editing
- **Category System**: Hierarchical categories with parent-child relationships
- **Tag System**: Flexible tagging system for post organization
- **Comment System**: User comments with moderation capabilities
- **User Management**: Complete user registration and profile management
- **Search & Filtering**: Advanced search and filtering capabilities

### Admin Dashboard (Filament)
- **Modern Admin Interface**: Beautiful, responsive admin panel built with Filament
- **Complete CRUD Operations**: Full management for all content types
- **Dashboard Widgets**: Real-time statistics and overview widgets
- **Rich Text Editor**: Full-featured content editor for posts
- **Image Management**: File upload with image editing capabilities
- **Role-based Access**: Secure access control for admin functions

### Role-Based Access Control
- **Three-tier Role System**: Administrator, Author, and User roles
- **Granular Permissions**: Fine-grained permission control for all actions
- **System Protection**: Built-in safeguards for system roles and permissions
- **Admin Interface**: Complete role and permission management through Filament

### Authentication & Security
- **Laravel Jetstream**: Modern authentication scaffolding
- **Two-Factor Authentication**: Enhanced security with 2FA support
- **Email Verification**: Secure email verification system
- **API Token Management**: Secure API access with token management
- **Session Management**: Advanced session and browser management

## 🛠️ Technology Stack

### Backend
- **Laravel 12**: Latest Laravel framework
- **PHP 8.2+**: Modern PHP with latest features
- **MySQL/PostgreSQL**: Database support
- **Spatie Laravel Permission**: Role and permission management
- **Filament 3**: Admin panel framework
- **Laravel Jetstream**: Authentication scaffolding
- **Laravel Sanctum**: API authentication

### Frontend
- **Tailwind CSS**: Utility-first CSS framework
- **Alpine.js**: Lightweight JavaScript framework
- **Vite**: Modern build tool
- **Blade Templates**: Laravel's templating engine
- **Livewire**: Dynamic interfaces without leaving PHP

### Development Tools
- **Pest**: Modern PHP testing framework
- **Laravel Pint**: PHP code style fixer
- **Laravel Sail**: Docker development environment
- **Laravel Pail**: Real-time log viewer

## 📋 Requirements

- PHP 8.2 or higher
- Composer
- Node.js & NPM
- MySQL 8.0+ or PostgreSQL 13+
- Redis (optional, for caching)

## 🚀 Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd larablog
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure database**
   Edit `.env` file with your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=larablog
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

6. **Run migrations and seeders**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

7. **Create admin user**
   ```bash
   php artisan roles:manage assign-role --user=1 --role=admin
   ```

8. **Build assets**
   ```bash
   npm run build
   ```

9. **Start the development server**
   ```bash
   php artisan serve
   ```

## 👥 User Roles & Permissions

### Administrator
- Full access to all features
- User and role management
- System settings access
- Complete content management

### Author
- Create and edit posts
- Manage own comments
- Access admin panel
- Category and tag viewing

### User
- View posts and categories
- Create comments
- Basic profile management

## 🎨 Admin Dashboard

Access the admin dashboard at `/admin` (requires admin or author role).

### Key Features:
- **Dashboard Overview**: Real-time statistics and recent activity
- **Post Management**: Full CRUD with rich text editor
- **User Management**: Complete user administration
- **Role & Permission Management**: Granular access control
- **Comment Moderation**: Approve/reject user comments
- **Category & Tag Management**: Content organization tools

## 🗄️ Database Structure

### Core Models
- **User**: Authentication and profile management
- **Post**: Blog posts with rich content
- **Category**: Hierarchical content organization
- **Tag**: Flexible content tagging
- **Comment**: User-generated comments
- **Role**: User role definitions
- **Permission**: Granular permission system

### Key Relationships
- Posts belong to categories and have many tags
- Users can have multiple roles
- Comments belong to posts and users
- Categories can have parent-child relationships

## 🧪 Testing

Run the test suite:
```bash
php artisan test
```

The application includes comprehensive tests for:
- Authentication flows
- User management
- Post operations
- Role and permission system
- API endpoints

## 📚 Documentation

- [Filament Admin Documentation](FILAMENT_ADMIN.md)
- [Role & Permission System](ROLE_PERMISSION_SYSTEM.md)

## 🔧 Development

### Available Commands
```bash
# Development server with all services
composer run dev

# Run tests
composer run test

# Code style fixing
./vendor/bin/pint

# Real-time logs
php artisan pail
```

### Key Directories
- `app/Filament/` - Admin panel resources and widgets
- `app/Models/` - Eloquent models
- `app/Enums/` - Role and permission enums
- `database/seeders/` - Database seeders
- `resources/views/` - Blade templates

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new functionality
5. Ensure all tests pass
6. Submit a pull request

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🆘 Support

For support and questions:
- Check the documentation files in the project
- Review Laravel and Filament documentation
- Open an issue on the repository

---

Built with ❤️ using Laravel, Filament, and modern web technologies.
