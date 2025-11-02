# 🍽️ StudEats - Smart Meal Planning Platform

<p align="center">
  <img src="https://via.placeholder.com/400x150/4F46E5/FFFFFF?text=StudEats" alt="StudEats Logo" width="400">
</p>

<p align="center">
  <strong>A comprehensive meal planning application built with Laravel 12</strong>
</p>

<p align="center">
  <a href="https://studeats-12.onrender.com" target="_blank">🌐 Live Demo</a> • 
  <a href="#-features">Features</a> • 
  <a href="#-getting-started">Getting Started</a> • 
  <a href="#-documentation">Documentation</a>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/TailwindCSS-4.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/React-19.x-61DAFB?style=for-the-badge&logo=react&logoColor=black" alt="React">
  <img src="https://img.shields.io/badge/Deployed_on-Render-46E3B7?style=for-the-badge&logo=render&logoColor=white" alt="Render">
</p>

---

## 🚀 About StudEats

StudEats is a modern, intelligent meal planning platform designed to help users create personalized nutrition plans based on their health goals, dietary preferences, and lifestyle. Built with Laravel 12 and featuring a responsive React-powered frontend, StudEats combines powerful backend functionality with an intuitive user experience.

### 🎯 Why StudEats?

- **Personalized Nutrition**: BMI-based calorie recommendations and dietary preference matching
- **Smart Planning**: Advanced meal planning with automatic nutritional calculations
- **User-Friendly**: Clean, responsive interface built with Tailwind CSS v4
- **Secure & Reliable**: Email verification, role-based access, and comprehensive error handling
- **Admin Dashboard**: Complete administrative interface for content and user management

---

## ✨ Features

### 🔐 **Authentication & Security**
- **Email Verification**: Secure OTP-based email verification system
- **Role-Based Access**: Admin and Super Admin roles with activity logging
- **Session Management**: Secure session handling with customizable timeouts
- **Rate Limiting**: Protection against abuse with intelligent rate limiting

### 👤 **User Management**
- **Profile Management**: Comprehensive user profiles with health metrics
- **BMI Calculation**: Automatic BMI calculation with category recommendations
- **Timezone Support**: Multi-timezone support for global users
- **Account Controls**: Suspend/activate accounts with admin oversight

### 🍽️ **Meal Planning**
- **Smart Meal Plans**: Create meal plans by date and meal type (breakfast, lunch, dinner)  
- **Nutritional Tracking**: Automatic nutritional information calculation
- **Recipe Management**: Comprehensive recipe database with ingredients
- **Dietary Preferences**: Support for various dietary restrictions and preferences
- **Calorie Adjustment**: BMI-based automatic calorie recommendations

### 📊 **Analytics & Insights**
- **Nutrition Analytics**: Detailed nutritional breakdowns and trends
- **Market Integration**: Bantay Presyo API integration for ingredient pricing
- **Progress Tracking**: Monitor dietary goals and achievements
- **Reporting**: Generate comprehensive nutrition and meal planning reports

### 🛠️ **Admin Features**
- **User Management**: Complete user administration with role management
- **Content Management**: Recipe, ingredient, and meal plan administration
- **Analytics Dashboard**: System-wide analytics and user insights
- **Activity Logging**: Comprehensive audit trail for admin actions

---

## 🛠️ Technology Stack

### **Backend**
- **Framework**: Laravel 12.25.0
- **PHP Version**: 8.2+
- **Database**: MySQL/PostgreSQL support
- **Queue System**: Database-driven job processing
- **Email**: SMTP integration with queue support
- **API Integration**: USDA Nutrition API, Bantay Presyo API

### **Frontend**
- **CSS Framework**: Tailwind CSS 4.1.12
- **JavaScript**: React 19.1.1 components
- **Build Tool**: Vite 7.x for fast development and optimized builds
- **Icons**: Heroicons and Lucide React for consistent iconography

### **Development & Deployment**
- **Code Quality**: Laravel Pint for consistent formatting
- **Testing**: PHPUnit with feature and unit tests
- **Deployment**: Docker support with Render/Railway configurations
- **Documentation**: Comprehensive docs in `/docs` directory

---

## 🚀 Getting Started

### Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js 18+ and NPM
- MySQL or PostgreSQL database
- SMTP email service (Gmail recommended)

### 🔧 Local Development Setup

1. **Clone the repository**
   ```bash
   git clone https://github.com/AlbertAndal/StudEats-13.git
   cd StudEats
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure your `.env` file**
   ```env
   APP_NAME=StudEats
   APP_URL=http://localhost:8000
   
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=studeats
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.gmail.com
   MAIL_PORT=587
   MAIL_USERNAME=your_email@gmail.com
   MAIL_PASSWORD=your_app_password
   ```

6. **Database Setup**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

7. **Build Frontend Assets**
   ```bash
   npm run build
   ```

8. **Start Development Server**
   ```bash
   composer run dev
   ```

### 🐳 Docker Deployment

StudEats includes Docker configurations for easy deployment:

```bash
# Build and run with Docker
docker build -t studeats .
docker run -p 8000:8000 studeats
```

### ☁️ Cloud Deployment

Deploy to Render, Railway, or any cloud platform:

- **Render**: Use the included `render-build.sh` and `render-start.sh`
- **Railway**: Configured with `railway.json` and deployment scripts  
- **Docker**: Production-ready Dockerfile included
- **Troubleshooting**: See `RENDER-500-ERROR-SOLUTION.md` for immediate 500 error fix

---

## 📖 Documentation

Comprehensive documentation is available in the `/docs` directory:

- **[Setup Guide](docs/email-configuration-fix.md)** - Detailed setup instructions
- **[Admin Guide](docs/admin-dashboard-guide.md)** - Administrative features
- **[API Documentation](docs/analytics-implementation-summary.md)** - API integration guides
- **[Deployment Guide](docs/RENDER-ENV-FIX.md)** - Cloud deployment instructions
- **[500 Error Fix](RENDER-500-ERROR-SOLUTION.md)** - Immediate solution: APP_KEY issue
- **[Troubleshooting Guide](RENDER-DEPLOYMENT-TROUBLESHOOTING.md)** - Complete deployment guide

### Key Documentation Files:
- 📧 [Email System](docs/email-confirmation-system.md)
- 🔐 [Authentication](docs/magic-link-troubleshooting-guide.md)
- 📊 [Analytics](docs/analytics-feature-guide.md)
- 🥗 [Meal Planning](docs/filipino-meal-plan.md)
- 💰 [Pricing Integration](docs/bantay-presyo-FINAL-SUMMARY.md)

---

## 🧪 Testing

StudEats includes comprehensive testing:

```bash
# Run all tests
composer run test

# Run specific test suites
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit

# Code formatting
vendor/bin/pint --dirty
```

---

## 🚀 Development Commands

StudEats includes convenient development scripts:

```bash
# Start full development environment
composer run dev

# Run tests with proper setup
composer run test

# Format code
vendor/bin/pint --dirty

# Clear all caches
php artisan optimize:clear
```

---

## 🤝 Contributing

We welcome contributions to StudEats! Please follow these guidelines:

1. **Fork the repository**
2. **Create a feature branch**: `git checkout -b feature/amazing-feature`
3. **Make your changes** and ensure tests pass
4. **Run code formatting**: `vendor/bin/pint --dirty`
5. **Commit your changes**: `git commit -m 'Add amazing feature'`
6. **Push to branch**: `git push origin feature/amazing-feature`
7. **Open a Pull Request**

### Development Guidelines:
- Follow PSR-12 coding standards
- Write tests for new features
- Update documentation as needed
- Use meaningful commit messages

---

## 📊 Project Status

- ✅ **Core Features**: User management, meal planning, nutrition tracking
- ✅ **Authentication**: Email verification, role-based access
- ✅ **Admin Dashboard**: Complete administrative interface
- ✅ **API Integration**: USDA Nutrition API, Bantay Presyo pricing
- ✅ **Responsive Design**: Mobile-first, Tailwind CSS implementation
- ✅ **Cloud Deployment**: Production-ready on Render/Railway
- 🔄 **Ongoing**: Performance optimizations, additional features

---

## 🐛 Support & Issues

- **Bug Reports**: [GitHub Issues](https://github.com/AlbertAndal/StudEats-13/issues)
- **Feature Requests**: [GitHub Discussions](https://github.com/AlbertAndal/StudEats-13/discussions)
- **Documentation**: Check the `/docs` directory for detailed guides
- **Deployment Issues**: See [RENDER-500-ERROR-SOLUTION.md](RENDER-500-ERROR-SOLUTION.md) and [RENDER-DEPLOYMENT-TROUBLESHOOTING.md](RENDER-DEPLOYMENT-TROUBLESHOOTING.md)

---

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 🙏 Acknowledgments

- **Laravel Team** for the amazing framework
- **USDA** for the comprehensive nutrition API
- **Bantay Presyo** for market pricing data
- **Open Source Community** for the excellent packages and tools

---

<p align="center">
  <strong>Built with ❤️ by the StudEats Team</strong>
</p>

<p align="center">
  <a href="https://studeats-12.onrender.com">🌐 Visit StudEats</a> • 
  <a href="https://github.com/AlbertAndal/StudEats-13">⭐ Star on GitHub</a>
</p>
