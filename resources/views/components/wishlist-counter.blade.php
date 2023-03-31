{{--
    Use visible attribute to show even if wishlist is empty
--}}
<div {{ $attributes->merge(['class' => '' ] ) }}
    x-data="{
        count : {{ Cart::instance('wishlist')->count() }},
        visible : {{ $attributes->get('visible') ?? 'false' }},
        get label() {return this.count > 99 ? '99+' : this.count},
    }"
    x-init="
        document.addEventListener('wishlist-updated', event => {
            count = event.detail.count;
        });
    "
    x-text="label"
    
    x-show="count || visible"

    x-cloak

    style="display:none;"
>
</div>