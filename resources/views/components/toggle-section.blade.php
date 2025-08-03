@props([
    'title',
    'description' => null,
    'icon' => null,
    'iconColor' => 'blue',
    'expanded' => false
])

<div x-data="{ isExpanded: {{ $expanded ? 'true' : 'false' }} }" {{ $attributes }}>
    <div class="bg-gradient-to-r from-{{ $iconColor }}-50 to-indigo-50 dark:from-{{ $iconColor }}-900/20 dark:to-indigo-900/20 rounded-2xl p-6 border border-{{ $iconColor }}-200 dark:border-{{ $iconColor }}-800">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                @if($icon)
                    <div class="w-12 h-12 bg-{{ $iconColor }}-100 dark:bg-{{ $iconColor }}-900/30 rounded-xl flex items-center justify-center">
                        {!! $icon !!}
                    </div>
                @endif
                <div>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $title }}</h3>
                    @if($description)
                        <p class="text-gray-600 dark:text-gray-400">{{ $description }}</p>
                    @endif
                </div>
            </div>
            
            <!-- Toggle Button -->
            <button 
                @click="isExpanded = !isExpanded"
                class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-white dark:bg-gray-700 shadow-lg border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 transition-all duration-200"
                :class="{ 'rotate-180': isExpanded }"
            >
                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Content -->
    <div x-show="isExpanded" 
         x-transition:enter="transition-all duration-300 ease-out"
         x-transition:enter-start="opacity-0 max-h-0"
         x-transition:enter-end="opacity-100 max-h-96"
         x-transition:leave="transition-all duration-300 ease-in"
         x-transition:leave-start="opacity-100 max-h-96"
         x-transition:leave-end="opacity-0 max-h-0"
         class="overflow-hidden">
        <div class="mt-6">
            {{ $slot }}
        </div>
    </div>
</div>

<style>
.rotate-180 {
    transform: rotate(180deg);
}
</style> 