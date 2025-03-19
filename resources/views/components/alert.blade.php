<div class="fixed top-4 right-4 z-50" x-data="{ show: {{ $message !== null ? 'true' : 'false' }} }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-cloak>
    <div
        class="p-4 rounded-md shadow-md"
        :class="{
            'bg-green-100 text-green-800 border border-green-300': '{{ $type }}' === 'success',
            'bg-red-100 text-red-800 border border-red-300': '{{ $type }}' === 'error'
        }"
    >
        <div class="flex items-center">
            <svg x-show="'{{ $type }}' === 'success'" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-green-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <svg x-show="'{{ $type }}' === 'error'" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-red-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            <span>{{ $message }}</span>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
