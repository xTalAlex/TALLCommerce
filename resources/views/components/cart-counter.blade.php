<span {{ $attributes->merge(['']) }}
    x-data="{
        count : {{ Cart::instance('default')->count() }},
        visible : {{ $attributes->get('visible') ?? 'false' }},
        get label() {return this.count > 99 ? '99+' : this.count},
    }"
    x-init="
        document.addEventListener('cart-updated', event => {
            count = event.detail.count;
        });
    "
    x-text="label"

    x-show="count || visible"

    x-cloak

    style="display:none;"
>
</span>
