<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Nutrition API Test - StudEats</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Nutrition API Testing Interface</h1>
            <p class="text-gray-600 mb-8">Test the USDA FoodData Central API integration for automatic nutrition calculation</p>
            
            <!-- API Status -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold text-blue-900">API Status</h3>
                        <p class="text-sm text-blue-700 mt-1" id="api-status">Testing connection...</p>
                    </div>
                    <div id="status-indicator" class="w-3 h-3 rounded-full bg-gray-400 animate-pulse"></div>
                </div>
            </div>
            
            <!-- Test 1: Search Food -->
            <div class="mb-8 border border-gray-200 rounded-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Test 1: Search Food Database</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Food Name</label>
                        <input type="text" id="search-query" value="chicken breast" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>
                    <button onclick="testSearchFood()" 
                            class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium">
                        Search Food
                    </button>
                    <div id="search-result" class="mt-4 hidden">
                        <h3 class="font-semibold text-gray-900 mb-2">Result:</h3>
                        <pre class="bg-gray-100 p-4 rounded-lg overflow-auto text-xs"></pre>
                    </div>
                </div>
            </div>
            
            <!-- Test 2: Calculate Single Ingredient -->
            <div class="mb-8 border border-gray-200 rounded-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Test 2: Calculate Single Ingredient</h2>
                <div class="grid grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ingredient</label>
                        <input type="text" id="ingredient-name" value="Chicken breast" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                        <input type="number" id="ingredient-quantity" value="500" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Unit</label>
                        <select id="ingredient-unit" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="g" selected>g (grams)</option>
                            <option value="kg">kg (kilograms)</option>
                            <option value="lb">lb (pounds)</option>
                            <option value="oz">oz (ounces)</option>
                            <option value="cup">cup</option>
                            <option value="tbsp">tbsp</option>
                            <option value="tsp">tsp</option>
                        </select>
                    </div>
                </div>
                <button onclick="testCalculateIngredient()" 
                        class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium">
                    Calculate Nutrition
                </button>
                <div id="ingredient-result" class="mt-4 hidden">
                    <h3 class="font-semibold text-gray-900 mb-2">Nutritional Values:</h3>
                    <div class="grid grid-cols-4 gap-4 mb-4">
                        <div class="bg-blue-50 p-4 rounded-lg text-center">
                            <div class="text-2xl font-bold text-blue-600" id="ing-calories">0</div>
                            <div class="text-xs text-gray-600">Calories</div>
                        </div>
                        <div class="bg-orange-50 p-4 rounded-lg text-center">
                            <div class="text-2xl font-bold text-orange-600" id="ing-protein">0g</div>
                            <div class="text-xs text-gray-600">Protein</div>
                        </div>
                        <div class="bg-yellow-50 p-4 rounded-lg text-center">
                            <div class="text-2xl font-bold text-yellow-600" id="ing-carbs">0g</div>
                            <div class="text-xs text-gray-600">Carbs</div>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg text-center">
                            <div class="text-2xl font-bold text-purple-600" id="ing-fats">0g</div>
                            <div class="text-xs text-gray-600">Fats</div>
                        </div>
                    </div>
                    <pre class="bg-gray-100 p-4 rounded-lg overflow-auto text-xs"></pre>
                </div>
            </div>
            
            <!-- Test 3: Calculate Recipe -->
            <div class="mb-8 border border-gray-200 rounded-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Test 3: Calculate Complete Recipe</h2>
                <div class="space-y-4 mb-4">
                    <div class="grid grid-cols-12 gap-2 text-sm font-medium text-gray-700">
                        <div class="col-span-5">Ingredient</div>
                        <div class="col-span-3">Quantity</div>
                        <div class="col-span-3">Unit</div>
                        <div class="col-span-1"></div>
                    </div>
                    <div id="recipe-ingredients">
                        <div class="grid grid-cols-12 gap-2 mb-2">
                            <input type="text" value="Chicken breast" class="col-span-5 px-3 py-2 border rounded-lg recipe-ing-name">
                            <input type="number" value="500" class="col-span-3 px-3 py-2 border rounded-lg recipe-ing-qty">
                            <select class="col-span-3 px-3 py-2 border rounded-lg recipe-ing-unit">
                                <option value="g" selected>g</option>
                                <option value="kg">kg</option>
                                <option value="cup">cup</option>
                            </select>
                            <button onclick="this.parentElement.remove()" class="col-span-1 text-red-600 hover:text-red-800">×</button>
                        </div>
                        <div class="grid grid-cols-12 gap-2 mb-2">
                            <input type="text" value="Rice" class="col-span-5 px-3 py-2 border rounded-lg recipe-ing-name">
                            <input type="number" value="2" class="col-span-3 px-3 py-2 border rounded-lg recipe-ing-qty">
                            <select class="col-span-3 px-3 py-2 border rounded-lg recipe-ing-unit">
                                <option value="cup" selected>cup</option>
                                <option value="g">g</option>
                            </select>
                            <button onclick="this.parentElement.remove()" class="col-span-1 text-red-600 hover:text-red-800">×</button>
                        </div>
                    </div>
                    <button onclick="addRecipeIngredient()" class="text-sm text-green-600 hover:text-green-800">+ Add Ingredient</button>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Servings</label>
                    <input type="number" id="recipe-servings" value="4" 
                           class="w-32 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <button onclick="testCalculateRecipe()" 
                        class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium">
                    Calculate Recipe Nutrition
                </button>
                <div id="recipe-result" class="mt-4 hidden">
                    <h3 class="font-semibold text-gray-900 mb-2">Total Recipe Nutrition:</h3>
                    <div class="grid grid-cols-4 gap-4 mb-4">
                        <div class="bg-blue-50 p-4 rounded-lg text-center">
                            <div class="text-2xl font-bold text-blue-600" id="recipe-calories">0</div>
                            <div class="text-xs text-gray-600">Calories (per serving)</div>
                        </div>
                        <div class="bg-orange-50 p-4 rounded-lg text-center">
                            <div class="text-2xl font-bold text-orange-600" id="recipe-protein">0g</div>
                            <div class="text-xs text-gray-600">Protein</div>
                        </div>
                        <div class="bg-yellow-50 p-4 rounded-lg text-center">
                            <div class="text-2xl font-bold text-yellow-600" id="recipe-carbs">0g</div>
                            <div class="text-xs text-gray-600">Carbs</div>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg text-center">
                            <div class="text-2xl font-bold text-purple-600" id="recipe-fats">0g</div>
                            <div class="text-xs text-gray-600">Fats</div>
                        </div>
                    </div>
                    <pre class="bg-gray-100 p-4 rounded-lg overflow-auto text-xs"></pre>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        
        // Test API connection on load
        window.addEventListener('load', async () => {
            try {
                const response = await fetch('/api/search-food?query=apple', {
                    headers: { 'Accept': 'application/json' }
                });
                
                if (response.ok) {
                    document.getElementById('api-status').textContent = '✅ API Connected Successfully';
                    document.getElementById('status-indicator').className = 'w-3 h-3 rounded-full bg-green-500';
                } else {
                    throw new Error('API not responding');
                }
            } catch (error) {
                document.getElementById('api-status').textContent = '❌ API Connection Failed';
                document.getElementById('status-indicator').className = 'w-3 h-3 rounded-full bg-red-500';
            }
        });
        
        async function testSearchFood() {
            const query = document.getElementById('search-query').value;
            const resultDiv = document.getElementById('search-result');
            const resultPre = resultDiv.querySelector('pre');
            
            try {
                const response = await fetch(`/api/search-food?query=${encodeURIComponent(query)}`, {
                    headers: { 'Accept': 'application/json' }
                });
                
                const data = await response.json();
                resultPre.textContent = JSON.stringify(data, null, 2);
                resultDiv.classList.remove('hidden');
            } catch (error) {
                resultPre.textContent = 'Error: ' + error.message;
                resultDiv.classList.remove('hidden');
            }
        }
        
        async function testCalculateIngredient() {
            const name = document.getElementById('ingredient-name').value;
            const quantity = parseFloat(document.getElementById('ingredient-quantity').value);
            const unit = document.getElementById('ingredient-unit').value;
            const resultDiv = document.getElementById('ingredient-result');
            const resultPre = resultDiv.querySelector('pre');
            
            try {
                const response = await fetch('/api/calculate-ingredient-nutrition', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ name, quantity, unit })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    document.getElementById('ing-calories').textContent = Math.round(data.calories);
                    document.getElementById('ing-protein').textContent = data.protein.toFixed(1) + 'g';
                    document.getElementById('ing-carbs').textContent = data.carbs.toFixed(1) + 'g';
                    document.getElementById('ing-fats').textContent = data.fats.toFixed(1) + 'g';
                }
                
                resultPre.textContent = JSON.stringify(data, null, 2);
                resultDiv.classList.remove('hidden');
            } catch (error) {
                resultPre.textContent = 'Error: ' + error.message;
                resultDiv.classList.remove('hidden');
            }
        }
        
        async function testCalculateRecipe() {
            const ingredients = [];
            document.querySelectorAll('#recipe-ingredients > div').forEach(row => {
                const name = row.querySelector('.recipe-ing-name').value;
                const quantity = parseFloat(row.querySelector('.recipe-ing-qty').value);
                const unit = row.querySelector('.recipe-ing-unit').value;
                
                if (name && quantity && unit) {
                    ingredients.push({ name, quantity, unit });
                }
            });
            
            const servings = parseInt(document.getElementById('recipe-servings').value);
            const resultDiv = document.getElementById('recipe-result');
            const resultPre = resultDiv.querySelector('pre');
            
            try {
                const response = await fetch('/api/calculate-recipe-nutrition', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ ingredients, servings })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    document.getElementById('recipe-calories').textContent = Math.round(data.per_serving.calories);
                    document.getElementById('recipe-protein').textContent = data.per_serving.protein.toFixed(1) + 'g';
                    document.getElementById('recipe-carbs').textContent = data.per_serving.carbs.toFixed(1) + 'g';
                    document.getElementById('recipe-fats').textContent = data.per_serving.fats.toFixed(1) + 'g';
                }
                
                resultPre.textContent = JSON.stringify(data, null, 2);
                resultDiv.classList.remove('hidden');
            } catch (error) {
                resultPre.textContent = 'Error: ' + error.message;
                resultDiv.classList.remove('hidden');
            }
        }
        
        function addRecipeIngredient() {
            const html = `
                <div class="grid grid-cols-12 gap-2 mb-2">
                    <input type="text" placeholder="Ingredient name" class="col-span-5 px-3 py-2 border rounded-lg recipe-ing-name">
                    <input type="number" placeholder="Amount" class="col-span-3 px-3 py-2 border rounded-lg recipe-ing-qty">
                    <select class="col-span-3 px-3 py-2 border rounded-lg recipe-ing-unit">
                        <option value="g">g</option>
                        <option value="kg">kg</option>
                        <option value="cup">cup</option>
                        <option value="tbsp">tbsp</option>
                    </select>
                    <button onclick="this.parentElement.remove()" class="col-span-1 text-red-600 hover:text-red-800">×</button>
                </div>
            `;
            document.getElementById('recipe-ingredients').insertAdjacentHTML('beforeend', html);
        }
    </script>
</body>
</html>
