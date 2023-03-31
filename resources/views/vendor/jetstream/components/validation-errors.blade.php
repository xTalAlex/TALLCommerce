@if ($errors->any())
    <div {{ $attributes->merge([ 'class' => 'text-danger-500' ]) }}>
        <div class="text-sm font-medium">{{ __('Whoops! Something went wrong.') }}</div>

        <ul class="mt-3 text-xs list-disc list-inside text-danget-500">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
