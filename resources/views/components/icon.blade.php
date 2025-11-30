@props(['name'])

@if ($name === 'home')
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path d="M3 12l9-9 9 9M4 10v10h16V10" stroke-width="2" stroke-linecap="round"/>
    </svg>

@elseif ($name === 'users')
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <circle cx="9" cy="7" r="4" stroke-width="2" />
        <path d="M17 11c1.7 0 3-1.3 3-3s-1.3-3-3-3" stroke-width="2"/>
        <path d="M17 22v-2c0-2.2-2.5-4-5.5-4S6 17.8 6 20v2" stroke-width="2"/>
    </svg>

@else
    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="10" stroke-width="2" />
    </svg>
@endif
