# StudEats Image Guidelines

## Featured Meal Image Standards

To ensure consistency and visual appeal across the StudEats platform, all featured meal images must adhere to the following guidelines:

### Image Specifications

#### **File Format**
- **Preferred**: JPEG (.jpg)
- **Alternative**: PNG (.png) for images with transparency
- **Not recommended**: GIF, BMP, or other formats

#### **Dimensions & Aspect Ratio**
- **Recommended Size**: 800x600 pixels
- **Aspect Ratio**: 4:3 (width:height)
- **Minimum Size**: 640x480 pixels
- **Maximum Size**: 1200x900 pixels

#### **File Size**
- **Target**: 200-500 KB
- **Maximum**: 1 MB
- **Compression**: Use 85-95% quality for JPEG

### Visual Quality Standards

#### **Photography Requirements**
1. **High Resolution**: Sharp, clear images without blur
2. **Well-Lit**: Natural or professional lighting preferred
3. **Color Accuracy**: True-to-life colors representing the actual dish
4. **Composition**: Dish should occupy 60-80% of the frame
5. **Background**: Clean, neutral backgrounds (white, light gray, or wooden surfaces)

#### **Food Presentation**
1. **Fresh Appearance**: Food should look fresh and appetizing
2. **Proper Plating**: Well-presented on appropriate dishware
3. **Garnish**: Minimal, tasteful garnishes that complement the dish
4. **Steam/Heat Indicators**: Visible for hot dishes (steam, melted cheese, etc.)

### Technical Implementation

#### **File Naming Convention**
```
placeholder-[dish-name-lowercase-with-hyphens].jpg

Examples:
- placeholder-chicken-adobo.jpg
- placeholder-sinigang-na-baboy.jpg
- placeholder-kare-kare.jpg
```

#### **Storage Location**
```
storage/app/public/meals/
```

#### **Database Storage**
- Store relative path in `image_path` column
- Format: `meals/filename.jpg`
- Use Laravel's Storage facade for URL generation

### Accessibility Requirements

1. **Alt Text**: Always provide descriptive alt text
2. **Color Contrast**: Ensure text overlays have sufficient contrast
3. **Loading Performance**: Optimize images for web delivery

### Quality Checklist

Before adding any featured meal image, verify:

- [ ] Image meets dimension requirements (4:3 aspect ratio)
- [ ] File size is under 500KB
- [ ] Image is sharp and well-lit
- [ ] Food looks fresh and appetizing
- [ ] Background is clean and uncluttered
- [ ] File is properly named and stored
- [ ] Alt text is descriptive and meaningful

### Current Featured Meals

| Meal Name | Image Status | Image Path |
|-----------|--------------|------------|
| Tapsilog | ✅ Available | `meals/uEgYrZPJAqBMWi4bYbl2CSBtXKE24xQFyWJruQve.jpg` |
| Longsilog | ✅ Available | `meals/IKVoa42V5gSLCjfY1NR6zo1HIJzQU61xj9wIuURa.jpg` |
| Champorado | ✅ Available | `meals/jtXuARCmRuYM5OH1UunH3fJl3l1Mt8RDpxfIFHfl.jpg` |
| Chicken Adobo | 🟡 Placeholder | `meals/placeholder-chicken-adobo.jpg` |
| Sinigang na Baboy | 🟡 Placeholder | `meals/placeholder-sinigang-na-baboy.jpg` |
| Kare-Kare | 🟡 Placeholder | `meals/placeholder-kare-kare.jpg` |
| Crispy Pata | 🟡 Placeholder | `meals/placeholder-crispy-pata.jpg` |
| Lechon Kawali | 🟡 Placeholder | `meals/placeholder-lechon-kawali.jpg` |
| Turon | 🟡 Placeholder | `meals/placeholder-turon.jpg` |

### Implementation Notes

1. **Priority**: The dashboard controller now prioritizes featured meals with images
2. **Fallback**: Enhanced fallback display for missing images
3. **URL Generation**: Uses Laravel's `getImageUrlAttribute()` accessor
4. **Performance**: Images are served through Laravel's storage symlink

### Next Steps

1. Replace placeholder images with high-quality photographs
2. Implement image upload functionality in admin panel
3. Add image compression pipeline for automatic optimization
4. Consider using a CDN for image delivery in production

---

*Last updated: September 3, 2025*
*Version: 1.0*