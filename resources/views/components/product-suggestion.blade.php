<div class="grid place-items-center">
    <div class="py-12">
        <p>
            {{ __("Haven't found anything, yet?") }}
        </p>
        <div class="flex flex-col w-full mt-6 space-y-2 sm:space-y-0 sm:space-x-2 sm:flex-row">
            <form class="w-full sm:w-1/2" method="GET" action="{{ route('product.index') }}">
                <x-button class="w-full">{{ __('To Shop') }}</x-button>
            </form>
            @if($product)
            <form class="w-full sm:w-1/2" method="GET" action="{{ route('product.show', $product) }}">
                <x-button class="w-full">{{ __('Random Product') }}</x-button>
            </form>
            @endif
        </div>
    </div>
</div>
