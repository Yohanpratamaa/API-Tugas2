<div x-data="{ open: false }">
    <button
        @click="open = !open"
        :class="open ? 'bg-[#22385e] text-white' : 'text-gray-400'"
        type="button"
        @click.away="open = false"
        class="text-center w-[167px] h-[39px] outline outline-1 hover:bg-[#22385e] hover:text-white rounded-xl transition-all duration-300">
        {{ $slot }}
    </button>
</div>
