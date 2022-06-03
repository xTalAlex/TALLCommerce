<table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">

    @isset($title )
    <h3 class="my-3 ml-3">
        {{$title}} 
    </h3>
    @endif

    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <tr>
            {{ $heading }}
        </tr>
    </thead>

    <tbody>
        {{ $slot }}
    </tbody>
    
</table>