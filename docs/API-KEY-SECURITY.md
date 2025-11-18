# API Key Security Guide

## ✅ Current Security Implementation

### 1. Environment Variables
- API keys are stored in `.env` file (not tracked by git)
- `.env` is properly listed in `.gitignore`
- `.env.example` contains placeholder values for setup reference

### 2. Git Security
```bash
# ✅ These files are ignored by git:
.env
.env.backup
.env.production
```

### 3. Documentation Safety
All documentation files use placeholder values:
- `your_api_key_here` instead of real keys
- Clear instructions on where to get API keys

## 🔐 API Key Configuration

### Nutrition API Key
```env
# In your .env file:
NUTRITION_API_KEY=your_actual_api_key_here
```

**Get your key from**: https://fdc.nal.usda.gov/api-key-signup.html

### Production Deployment
For Laravel Cloud, Railway, or other platforms:

1. **Set environment variables in platform dashboard**
2. **Never commit real keys to repository**
3. **Use platform-specific secret management**

## ⚠️ Security Best Practices

### DO:
- ✅ Store keys in `.env` file
- ✅ Use environment variables in code: `env('NUTRITION_API_KEY')`
- ✅ Keep `.env` in `.gitignore`
- ✅ Use placeholder values in documentation
- ✅ Rotate keys regularly
- ✅ Use different keys for different environments

### DON'T:
- ❌ Commit `.env` file to git
- ❌ Hardcode keys in source code
- ❌ Share keys in documentation
- ❌ Use production keys in development
- ❌ Store keys in public repositories

## 🔄 Key Rotation

When rotating API keys:

1. Update `.env` file with new key
2. Update production environment variables
3. Test functionality
4. Revoke old key from provider

## 📝 Environment File Template

```env
# Copy from .env.example and fill in real values:
NUTRITION_API_KEY=your_api_key_here
```

---

**Last Updated**: November 18, 2025
**Status**: ✅ Secure - API keys properly protected