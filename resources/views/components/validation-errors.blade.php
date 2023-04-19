@if ($errors->any())
    <div {{ $attributes->merge([ 'class' => 'text-error' ]) }}>
        <div class="text-sm">{{ __('Whoops! Something went wrong.') }}</div>
        {{-- <ul class="mt-3 text-xs list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul> --}}
    </div>
@endif