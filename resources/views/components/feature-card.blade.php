<div class="p-6 bg-white dark:bg-[#161615] rounded-lg shadow-sm hover:shadow-md transition">
    <div class="flex items-start gap-4">
        <div class="flex-shrink-0 bg-[#FDFDFC] dark:bg-[#1D0002] rounded-full w-10 h-10 flex items-center justify-center">
            {{ $icon ?? '' }}
        </div>
        <div>
            <h3 class="font-medium text-gray-900 dark:text-white">{{ $title }}</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $slot }}</p>
            @if(isset($url))
                <a href="{{ $url }}" class="mt-3 inline-block text-sm text-[#F53003] font-medium">Bekijk →</a>
            @endif
        </div>
    </div>
</div>
