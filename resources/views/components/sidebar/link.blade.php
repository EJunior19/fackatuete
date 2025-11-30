@props(['href' => '#', 'icon' => null, 'active' => false])

<a href="{{ $href }}"
   class="flex items-center space-x-2 px-3 py-2 rounded
          {{ $active ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
    
    @if($icon)
        <x-icon name="{{ $icon }}" class="w-5 h-5" />
    @endif

    <span>{{ $slot }}</span>
</a>
