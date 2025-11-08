<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Geist Font Implementation Test - StudEats</title>
    
    <!-- Geist Font - Comprehensive weights with npm fallback -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css'])
    
    <style>
        .font-test {
            font-family: 'Geist', 'Geist Fallback', ui-sans-serif, system-ui, sans-serif;
        }
        .weight-100 { font-weight: 100; }
        .weight-200 { font-weight: 200; }
        .weight-300 { font-weight: 300; }
        .weight-400 { font-weight: 400; }
        .weight-500 { font-weight: 500; }
        .weight-600 { font-weight: 600; }
        .weight-700 { font-weight: 700; }
        .weight-800 { font-weight: 800; }
        .weight-900 { font-weight: 900; }
    </style>
</head>
<body class="font-sans bg-background text-foreground p-8">
    <div class="max-w-4xl mx-auto space-y-8">
        <header class="text-center mb-12">
            <h1 class="text-4xl font-bold text-primary mb-4">Geist Font Implementation Test</h1>
            <p class="text-lg text-muted-foreground">Verifying consistent font application across StudEats</p>
        </header>

        <!-- Font Weight Tests -->
        <section class="space-y-4">
            <h2 class="text-2xl font-semibold mb-6">Font Weight Variations</h2>
            <div class="grid gap-4">
                <div class="font-test weight-100 text-lg">Font Weight 100 - Thin: The quick brown fox jumps over the lazy dog</div>
                <div class="font-test weight-200 text-lg">Font Weight 200 - Extra Light: The quick brown fox jumps over the lazy dog</div>
                <div class="font-test weight-300 text-lg">Font Weight 300 - Light: The quick brown fox jumps over the lazy dog</div>
                <div class="font-test weight-400 text-lg">Font Weight 400 - Regular: The quick brown fox jumps over the lazy dog</div>
                <div class="font-test weight-500 text-lg">Font Weight 500 - Medium: The quick brown fox jumps over the lazy dog</div>
                <div class="font-test weight-600 text-lg">Font Weight 600 - Semi Bold: The quick brown fox jumps over the lazy dog</div>
                <div class="font-test weight-700 text-lg">Font Weight 700 - Bold: The quick brown fox jumps over the lazy dog</div>
                <div class="font-test weight-800 text-lg">Font Weight 800 - Extra Bold: The quick brown fox jumps over the lazy dog</div>
                <div class="font-test weight-900 text-lg">Font Weight 900 - Black: The quick brown fox jumps over the lazy dog</div>
            </div>
        </section>

        <!-- Typography Hierarchy -->
        <section class="space-y-4">
            <h2 class="text-2xl font-semibold mb-6">Typography Hierarchy</h2>
            <h1 class="text-5xl font-bold">Heading 1 - StudEats Dashboard</h1>
            <h2 class="text-4xl font-semibold">Heading 2 - Section Title</h2>
            <h3 class="text-3xl font-medium">Heading 3 - Subsection</h3>
            <h4 class="text-2xl font-medium">Heading 4 - Component Title</h4>
            <h5 class="text-xl font-medium">Heading 5 - Card Header</h5>
            <h6 class="text-lg font-medium">Heading 6 - Small Header</h6>
            <p class="text-base">Regular paragraph text with normal weight and spacing for body content.</p>
            <p class="text-sm text-muted-foreground">Small text used for descriptions and secondary information.</p>
        </section>

        <!-- UI Components -->
        <section class="space-y-6">
            <h2 class="text-2xl font-semibold mb-6">UI Components with Geist</h2>
            
            <!-- Buttons -->
            <div class="space-y-4">
                <h3 class="text-lg font-medium">Buttons</h3>
                <div class="flex gap-4 flex-wrap">
                    <button class="px-4 py-2 bg-primary text-primary-foreground rounded-md font-medium">Primary Button</button>
                    <button class="px-4 py-2 border border-input bg-background rounded-md font-medium">Secondary Button</button>
                    <button class="px-4 py-2 text-primary underline font-medium">Link Button</button>
                </div>
            </div>

            <!-- Cards -->
            <div class="space-y-4">
                <h3 class="text-lg font-medium">Cards</h3>
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="p-6 border rounded-lg bg-card">
                        <h4 class="font-semibold mb-2">Card Title</h4>
                        <p class="text-muted-foreground mb-4">Card description text that explains the content or purpose of this card component.</p>
                        <button class="text-sm bg-primary text-primary-foreground px-3 py-1 rounded">Action</button>
                    </div>
                    <div class="p-6 border rounded-lg bg-card">
                        <h4 class="font-semibold mb-2">Another Card</h4>
                        <p class="text-muted-foreground mb-4">More card content with consistent typography using the Geist font family.</p>
                        <button class="text-sm bg-primary text-primary-foreground px-3 py-1 rounded">Action</button>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="space-y-4">
                <h3 class="text-lg font-medium">Table</h3>
                <div class="overflow-hidden border rounded-lg">
                    <table class="w-full">
                        <thead class="bg-muted">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold">Name</th>
                                <th class="px-4 py-3 text-left font-semibold">Email</th>
                                <th class="px-4 py-3 text-left font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-t">
                                <td class="px-4 py-3 font-medium">John Doe</td>
                                <td class="px-4 py-3 text-muted-foreground">john@example.com</td>
                                <td class="px-4 py-3"><span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">Active</span></td>
                            </tr>
                            <tr class="border-t">
                                <td class="px-4 py-3 font-medium">Jane Smith</td>
                                <td class="px-4 py-3 text-muted-foreground">jane@example.com</td>
                                <td class="px-4 py-3"><span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">Pending</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- Links -->
        <section>
            <h2 class="text-2xl font-semibold mb-6">Navigation & Links</h2>
            <div class="space-y-2">
                <p><a href="/dashboard" class="text-primary hover:underline font-medium">Dashboard</a></p>
                <p><a href="/meal-plans" class="text-primary hover:underline font-medium">Meal Plans</a></p>
                <p><a href="/recipes" class="text-primary hover:underline font-medium">Recipes</a></p>
                <p><a href="/" class="text-primary hover:underline font-medium">Back to Home</a></p>
            </div>
        </section>

        <footer class="text-center pt-8 border-t">
            <p class="text-muted-foreground">Font loading verification complete. All text should render with Geist font family.</p>
        </footer>
    </div>

    <script>
        // Enhanced font loading detection
        document.fonts.ready.then(() => {
            const testElement = document.createElement('span');
            testElement.style.fontFamily = 'Geist, sans-serif';
            testElement.textContent = 'Test';
            document.body.appendChild(testElement);
            
            const computedFont = window.getComputedStyle(testElement).fontFamily;
            console.log('Computed font family:', computedFont);
            
            // Test font loading from different sources
            const googleFontsLoaded = computedFont.includes('Geist');
            const fallbackActive = computedFont.includes('system-ui') || computedFont.includes('sans-serif');
            
            // Create status indicator
            const statusDiv = document.createElement('div');
            statusDiv.className = 'fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50';
            
            if (googleFontsLoaded) {
                statusDiv.className += ' bg-green-100 border border-green-300 text-green-800';
                statusDiv.innerHTML = '✅ Google Fonts Geist loaded successfully';
                console.log('✅ Geist font successfully loaded from Google Fonts');
            } else if (fallbackActive) {
                statusDiv.className += ' bg-yellow-100 border border-yellow-300 text-yellow-800';
                statusDiv.innerHTML = '⚠️ Using fallback fonts (Google Fonts failed)';
                console.log('⚠️ Using fallback fonts - Google Fonts may be blocked');
            } else {
                statusDiv.className += ' bg-red-100 border border-red-300 text-red-800';
                statusDiv.innerHTML = '❌ Font loading failed';
                console.log('❌ Geist font not loaded, using default system fonts');
            }
            
            document.body.appendChild(statusDiv);
            
            // Test npm package availability
            fetch('/build/assets/geist.css').then(response => {
                if (response.ok) {
                    console.log('✅ Local Geist npm package available as fallback');
                } else {
                    console.log('⚠️ Local Geist npm package not found in build');
                }
            }).catch(() => {
                console.log('ℹ️ Local Geist check skipped (expected for Google Fonts primary)');
            });
            
            document.body.removeChild(testElement);
            
            // Remove status after 5 seconds
            setTimeout(() => {
                if (statusDiv.parentNode) {
                    statusDiv.parentNode.removeChild(statusDiv);
                }
            }, 5000);
        });
        
        // Test network connectivity to Google Fonts
        fetch('https://fonts.googleapis.com/css2?family=Geist:wght@400&display=swap', { mode: 'no-cors' })
            .then(() => console.log('✅ Google Fonts API accessible'))
            .catch(() => console.log('❌ Google Fonts API not accessible'));
    </script>
</body>
</html>