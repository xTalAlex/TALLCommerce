{{--
    Use visible attribute to show even if wishlist is empty
--}}
<div {{ $attributes->merge(['class' => '' ] ) }}
    x-data="{
        count : {{ Cart::instance('wishlist')->count() }},
        visible : {{ $attributes->get('visible') ?? 'false' }}
    }"
    x-init="
        document.addEventListener('wishlist-updated', event => {
            count = event.detail.count;
        });
    "
    x-text="count"
    
    x-show="count || visible"

    x-cloak

    style="display:none;"
>
</div>