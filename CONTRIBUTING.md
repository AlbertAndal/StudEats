# Contributing to StudEats

Thank you for your interest in contributing to StudEats! This document provides guidelines and information for contributors.

## 🚀 Getting Started

### Prerequisites
- PHP 8.2+
- Composer
- Node.js 18+
- MySQL or PostgreSQL
- Git

### Setup Development Environment

1. **Fork and clone the repository**
   ```bash
   git clone https://github.com/your-username/StudEats.git
   cd StudEats
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Set up environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure database and run migrations**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

5. **Start development server**
   ```bash
   composer run dev
   ```

## 📝 Development Guidelines

### Code Style
- Follow **PSR-12** coding standards
- Use **Laravel Pint** for code formatting: `vendor/bin/pint --dirty`
- Write meaningful variable names and comments
- Keep functions small and focused

### Laravel Conventions
- Use **Eloquent relationships** properly
- Follow **Repository pattern** where applicable
- Use **Form Requests** for validation
- Implement **Resource classes** for API responses
- Use **Laravel's built-in features** (notifications, queues, etc.)

### Frontend Guidelines
- Use **Tailwind CSS** classes consistently
- Follow **React best practices** for components
- Ensure **responsive design** (mobile-first)
- Use **semantic HTML** elements
- Optimize **accessibility** (ARIA labels, etc.)

## 🧪 Testing

### Running Tests
```bash
# Run all tests
composer run test

# Run specific test suites
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit

# Run tests with coverage
php artisan test --coverage
```

### Writing Tests
- Write **Feature tests** for user-facing functionality
- Write **Unit tests** for individual classes/methods
- Use **Laravel factories** for test data
- Mock external services (APIs, email, etc.)
- Test both success and failure scenarios

## 🔄 Workflow

### Branch Strategy
- `main` - Production-ready code
- `develop` - Development branch
- `feature/feature-name` - New features
- `bugfix/bug-description` - Bug fixes
- `hotfix/critical-fix` - Critical production fixes

### Pull Request Process

1. **Create a feature branch**
   ```bash
   git checkout -b feature/your-feature-name
   ```

2. **Make your changes**
   - Write code following our guidelines
   - Add/update tests as needed
   - Update documentation if necessary

3. **Test your changes**
   ```bash
   composer run test
   vendor/bin/pint --dirty
   ```

4. **Commit your changes**
   ```bash
   git add .
   git commit -m "Add: Brief description of your changes"
   ```

5. **Push and create PR**
   ```bash
   git push origin feature/your-feature-name
   ```

### Commit Message Format
Use conventional commit format:
- `feat: add new meal planning feature`
- `fix: resolve email verification bug`
- `docs: update README with new instructions`
- `test: add unit tests for User model`
- `refactor: improve database query performance`

## 🎯 Contribution Areas

### High Priority
- **Performance optimizations**
- **Mobile responsiveness improvements**
- **Additional dietary preferences**
- **Enhanced analytics features**
- **API rate limiting improvements**

### Documentation
- **Code documentation** (PHPDoc comments)
- **User guides** in `/docs` directory
- **Video tutorials** or screenshots
- **API documentation** updates

### Testing
- **Increase test coverage**
- **Add integration tests**
- **Performance testing**
- **Browser testing** (different devices)

## 🐛 Bug Reports

When reporting bugs, please include:

1. **Laravel version** and **PHP version**
2. **Steps to reproduce** the issue
3. **Expected behavior** vs **actual behavior**
4. **Error messages** or **screenshots**
5. **Environment details** (local, staging, production)

Use our bug report template:

```markdown
**Bug Description**
A clear description of the bug.

**Steps to Reproduce**
1. Go to '...'
2. Click on '...'
3. See error

**Expected Behavior**
What should happen.

**Actual Behavior**
What actually happens.

**Environment**
- Laravel version:
- PHP version:
- Database:
- Browser:
```

## 💡 Feature Requests

For new features, please:

1. **Check existing issues** to avoid duplicates
2. **Describe the problem** the feature would solve
3. **Explain the proposed solution**
4. **Consider implementation complexity**
5. **Think about potential impacts**

## 📚 Resources

### Laravel Resources
- [Laravel Documentation](https://laravel.com/docs)
- [Laravel Best Practices](https://github.com/alexeymezenin/laravel-best-practices)
- [Laravel Testing](https://laravel.com/docs/testing)

### Project-Specific
- [StudEats Documentation](docs/)
- [API Integration Guides](docs/analytics-implementation-summary.md)
- [Deployment Instructions](docs/RENDER-ENV-FIX.md)

## 🏆 Recognition

Contributors will be recognized in:
- **GitHub contributors page**
- **README acknowledgments**
- **Release notes** for significant contributions

## 📞 Getting Help

If you need help:
- **Check the documentation** in `/docs`
- **Search existing issues** on GitHub
- **Ask questions** in GitHub Discussions
- **Review the codebase** for similar implementations

## 🎉 Thank You!

Every contribution makes StudEats better. Whether it's:
- 🐛 **Bug fixes**
- ✨ **New features**
- 📝 **Documentation**
- 🧪 **Tests**
- 💡 **Ideas and feedback**

Your help is appreciated! 🙏

---

**Happy coding!** 🚀