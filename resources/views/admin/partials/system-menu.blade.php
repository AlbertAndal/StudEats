<!-- System Menu (relocated outside primary nav for prominence) -->
<div class="relative" id="admin-system-menu-wrapper">
    <button type="button" id="systemMenuButton"
            aria-haspopup="true" aria-expanded="false"
            class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md bg-gray-900 text-gray-100 hover:bg-gray-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500/60 shadow"
            onclick="adminSystemMenu.toggle(this)" data-system-trigger>
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
        <span>System</span>
        <svg class="w-4 h-4 ml-1 transition-transform duration-200" data-system-caret fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
        </svg>
    </button>

    <div id="systemDropdown" role="menu" aria-labelledby="systemMenuButton"
         class="hidden absolute mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 z-50 py-2" data-system-dropdown>
        <button type="button" onclick="adminSystemMenu.checkHealth()" role="menuitem"
                class="flex items-center w-full px-4 py-2 text-gray-700 hover:bg-gray-100 text-left text-sm">
            <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            System Health
        </button>
        <button type="button" disabled role="menuitem"
                class="flex items-center w-full px-4 py-2 text-gray-400 cursor-not-allowed text-left text-sm">
            <svg class="w-4 h-4 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
            </svg>
            Admin Logs <span class="ml-auto text-[10px] uppercase tracking-wide text-amber-500/70">Soon</span>
        </button>
        <button type="button" disabled role="menuitem"
                class="flex items-center w-full px-4 py-2 text-gray-400 cursor-not-allowed text-left text-sm">
            <svg class="w-4 h-4 mr-2 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
            </svg>
            Maintenance Mode <span class="ml-auto text-[10px] uppercase tracking-wide text-amber-500/70">Soon</span>
        </button>
    </div>
</div>

<script>
(function(){
    const apiRoute = "{{ route('admin.system-health') }}";
    const margin = 8;
    const paddingViewport = 4;

    function calcPosition(trigger, dropdown){
        // Reset
        dropdown.style.top = dropdown.style.left = dropdown.style.bottom = dropdown.style.right = '';
        dropdown.classList.remove('dropdown-above');
        dropdown.classList.remove('hidden');
        dropdown.style.visibility = 'hidden';
        dropdown.style.opacity = '0';
        dropdown.style.transform = 'translateY(0)';

        const triggerRect = trigger.getBoundingClientRect();
        const dropdownRect = dropdown.getBoundingClientRect();
        const viewportHeight = window.innerHeight;
        const spaceBelow = viewportHeight - triggerRect.bottom;
        const spaceAbove = triggerRect.top;
        let placeAbove = false;
        if (spaceBelow < dropdownRect.height + margin && spaceAbove > spaceBelow){ placeAbove = true; }

        let left = triggerRect.left; 
        const overflowRight = left + dropdownRect.width - window.innerWidth + paddingViewport;
        if (overflowRight > 0){ left -= overflowRight; }
        if (left < paddingViewport){ left = paddingViewport; }

        const scrollX = window.scrollX || window.pageXOffset;
        const scrollY = window.scrollY || window.pageYOffset;

        if (placeAbove){
            let top = triggerRect.top + scrollY - dropdownRect.height - margin;
            if (top < scrollY + paddingViewport){ top = scrollY + paddingViewport; }
            dropdown.style.top = top + 'px';
            dropdown.classList.add('dropdown-above');
        } else {
            let top = triggerRect.bottom + scrollY + margin;
            const maxTop = scrollY + viewportHeight - dropdownRect.height - paddingViewport;
            if (top > maxTop){ top = maxTop; }
            dropdown.style.top = top + 'px';
        }
        dropdown.style.left = (left + scrollX) + 'px';
        dropdown.style.position = 'absolute';
        dropdown.style.zIndex = '1100';
        requestAnimationFrame(()=>{
            dropdown.style.visibility = 'visible';
            dropdown.style.transition = 'opacity 120ms ease, transform 120ms ease';
            dropdown.style.opacity = '1';
            dropdown.style.transform = placeAbove ? 'translateY(-4px)' : 'translateY(4px)';
        });
    }

    window.adminSystemMenu = {
        toggle(trigger){
            const dropdown = document.getElementById('systemDropdown');
            const caret = trigger.querySelector('[data-system-caret]');
            const expanded = trigger.getAttribute('aria-expanded') === 'true';
            if (expanded){ this.close(); return; }
            calcPosition(trigger, dropdown);
            trigger.setAttribute('aria-expanded','true');
            caret.classList.add('rotate-180');
        },
        close(){
            const trigger = document.querySelector('[data-system-trigger]');
            const dropdown = document.getElementById('systemDropdown');
            const caret = trigger?.querySelector('[data-system-caret]');
            if (dropdown && !dropdown.classList.contains('hidden')){
                dropdown.classList.add('hidden');
                dropdown.removeAttribute('style');
                trigger?.setAttribute('aria-expanded','false');
                caret?.classList.remove('rotate-180');
            }
        },
        checkHealth(){
            fetch(apiRoute)
              .then(r=>r.json())
              .then(data=>{
                 let status = 'All systems operational';
                 if (data.database.status !== 'healthy' || data.storage.status !== 'healthy') { status = 'System issues detected'; }
                 alert(`System Health Check:\n${status}\n\nDatabase: ${data.database.status}\nStorage: ${data.storage.status}\nMemory: ${data.memory_usage.percentage}%`);
              })
              .catch(()=> alert('Error checking system health'))
              .finally(()=> this.close());
        }
    };

    // Outside click / escape
    document.addEventListener('click', (e)=>{
        const dropdown = document.getElementById('systemDropdown');
        if (!dropdown) { return; }
        const trigger = e.target.closest('[data-system-trigger]');
        if (trigger){ return; }
        if (!dropdown.contains(e.target)){ window.adminSystemMenu.close(); }
    });
    document.addEventListener('keydown', (e)=>{ if (e.key === 'Escape'){ window.adminSystemMenu.close(); } });
    ['resize','scroll'].forEach(evt => window.addEventListener(evt, ()=>{
        const trigger = document.querySelector('[data-system-trigger]');
        const dropdown = document.getElementById('systemDropdown');
        if (!trigger || !dropdown || dropdown.classList.contains('hidden')){ return; }
        dropdown.classList.add('hidden');
        trigger.setAttribute('aria-expanded','false');
        window.adminSystemMenu.toggle(trigger);
    }, { passive: true }));
})();
</script>
