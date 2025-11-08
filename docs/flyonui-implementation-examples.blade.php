{{-- Example: Update Meal Plan Form Submit Button with FlyonUI Loading Button --}}

{{-- BEFORE (Current Implementation) --}}
<button type="submit" 
        class="w-full px-4 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors duration-200"
        id="submitBtn">
    <span class="flex items-center justify-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
        </svg>
        Create Meal Plan
    </span>
</button>

{{-- AFTER (With FlyonUI Loading Button Component) --}}
<x-loading-button 
    variant="success" 
    type="submit"
    class="w-full px-4 py-3"
    id="submitBtn"
>
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
    </svg>
    Create Meal Plan
</x-loading-button>

{{-- JavaScript to handle loading state --}}
<script>
document.querySelector('form').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    
    // Replace with FlyonUI loading button
    submitBtn.outerHTML = `
        <button class="btn btn-success btn-disabled w-full px-4 py-3" disabled>
            <span class="loading loading-spinner loading-sm"></span>
            <span>Creating meal plan...</span>
        </button>
    `;
});
</script>


{{-- ========================================== --}}
{{-- Example: Delete Meal Plan Button --}}
{{-- ========================================== --}}

{{-- BEFORE --}}
<button type="button" 
        onclick="showRemoveMealModal()"
        class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-600 hover:text-red-700 hover:bg-red-50">
    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6"/>
    </svg>
    Remove
</button>

{{-- AFTER --}}
<x-loading-button 
    variant="error" 
    size="sm"
    type="button"
    onclick="showRemoveMealModal()"
    class="inline-flex items-center"
>
    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6"/>
    </svg>
    Remove
</x-loading-button>


{{-- ========================================== --}}
{{-- Example: Admin Recipe Edit Form --}}
{{-- ========================================== --}}

{{-- Save Button with Loading State --}}
<div class="flex gap-4">
    <x-loading-button 
        variant="success" 
        type="submit"
        size="lg"
        loadingText="Saving changes..."
        loadingType="ring"
    >
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        Save Recipe
    </x-loading-button>

    <x-loading-button 
        variant="secondary" 
        type="button"
        size="lg"
        onclick="window.location='{{ route('admin.recipes.index') }}'"
    >
        Cancel
    </x-loading-button>
</div>


{{-- ========================================== --}}
{{-- Example: Profile Photo Upload --}}
{{-- ========================================== --}}

<div x-data="{ uploading: false }">
    <form @submit.prevent="uploading = true; uploadPhoto()">
        <x-loading-button 
            variant="primary" 
            size="sm"
            type="submit"
            x-bind:class="uploading ? 'btn-disabled' : ''"
        >
            <template x-if="!uploading">
                <span class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Upload Photo
                </span>
            </template>
            <template x-if="uploading">
                <span class="flex items-center">
                    <span class="loading loading-spinner loading-sm mr-2"></span>
                    Uploading...
                </span>
            </template>
        </x-loading-button>
    </form>
</div>


{{-- ========================================== --}}
{{-- Example: Native FlyonUI in Modal Confirm --}}
{{-- ========================================== --}}

<div class="modal-actions flex gap-3">
    {{-- Confirm Delete Button --}}
    <button class="btn btn-error btn-disabled" id="confirmDeleteBtn">
        <span class="loading loading-spinner loading-sm"></span>
        <span>Deleting...</span>
    </button>
    
    {{-- Cancel Button --}}
    <button type="button" 
            onclick="hideModal()" 
            class="btn btn-secondary">
        Cancel
    </button>
</div>

<script>
function confirmDelete() {
    const btn = document.getElementById('confirmDeleteBtn');
    
    // Show loading state
    btn.disabled = true;
    btn.innerHTML = `
        <span class="loading loading-spinner loading-sm"></span>
        <span>Deleting...</span>
    `;
    
    // Perform delete action
    fetch('/api/delete', { method: 'DELETE' })
        .then(response => {
            if (response.ok) {
                window.location.reload();
            }
        })
        .catch(error => {
            // Restore button state on error
            btn.disabled = false;
            btn.innerHTML = 'Delete';
        });
}
</script>


{{-- ========================================== --}}
{{-- Example: Multiple Button States --}}
{{-- ========================================== --}}

<div class="flex gap-4">
    {{-- Primary Action --}}
    <x-loading-button 
        variant="success" 
        loadingText="Processing..."
        id="primaryBtn"
    >
        Process Order
    </x-loading-button>
    
    {{-- Secondary Action --}}
    <x-loading-button 
        variant="warning" 
        loadingText="Saving draft..."
        id="draftBtn"
    >
        Save as Draft
    </x-loading-button>
    
    {{-- Tertiary Action --}}
    <x-loading-button 
        variant="secondary"
        id="cancelBtn"
    >
        Cancel
    </x-loading-button>
</div>


{{-- ========================================== --}}
{{-- Example: Icon-Only Loading Buttons --}}
{{-- ========================================== --}}

<div class="flex gap-2">
    {{-- Refresh Button --}}
    <x-loading-button 
        variant="primary" 
        :loading="true"
        square
        aria-label="Refreshing data"
        loadingType="ring"
    />
    
    {{-- Download Button --}}
    <button class="btn btn-success btn-square">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
        </svg>
    </button>
    
    {{-- Settings Button --}}
    <button class="btn btn-secondary btn-square">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
    </button>
</div>
