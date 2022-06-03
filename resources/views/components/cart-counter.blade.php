{{--
    Use visible attribute to show even if cart is empty
--}}
<div {{ $attributes->merge(['']) }}
    x-data="{
        count : {{ Cart::instance('default')->count() }},
        visible : {{ $attributes->get('visible') ?? 'false' }}
    }"
    x-init="
        document.addEventListener('cart-updated', event => {
            count = event.detail.count;
        });
    "
    x-text="count"

    x-show="count || visible"

    x-cloak

    style="display:none;"
>
</div>
