# Bulk Ingredient Upload Guide

The bulk ingredient upload feature allows you to quickly add multiple ingredients to a recipe without entering them one by one.

## How to Use

1. Click the **"Bulk Upload"** button (purple button) next to "Add Ingredient"
2. Select a file (.csv or .txt) containing your ingredients
3. The ingredients will be automatically added to your recipe

## Supported Formats

### 1. CSV Format (Most Complete)
Perfect for when you have all ingredient details:
```
ingredient name,quantity,unit,price
Chicken breast,500,g,8.50
Rice,2,cup,3.00
Garlic,4,cloves,0.25
Onion,1,medium,0.75
```

**Supported Units:** g, kg, lb, oz, cup, tbsp, tsp, ml, l, pcs, piece, pack, clove, cloves, slice, medium, large, small, head, bunch

### 2. Simple Format
Great for recipes with quantities and units:
```
ingredient name - quantity unit
Chicken breast - 500 g
Rice - 2 cup
Garlic - 4 cloves
```

### 3. Raw Ingredient List
Just ingredient names (one per line):
```
Chicken breast
Rice
Garlic
Onion
Olive oil
```

## Tips

- Make sure each ingredient is on a separate line
- For CSV format, don't use quotes unless necessary
- Units will default to "g" (grams) if not specified
- You can mix formats in the same file
- Empty lines are ignored

## Sample Files

Check these example files in your StudEats folder:
- `sample-bulk-ingredients.csv` - Complete CSV format
- `sample-simple-format.txt` - Simple format
- `sample-raw-list.txt` - Raw ingredient list

## Troubleshooting

- **File not uploading**: Make sure it's a .csv or .txt file
- **Ingredients not appearing**: Check that each ingredient is on its own line
- **Missing data**: You can always edit ingredients manually after upload

The bulk upload feature works on both the **Create Recipe** and **Edit Recipe** pages.