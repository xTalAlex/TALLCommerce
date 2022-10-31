<div class="relative flex flex-wrap items-center mb-6 -mx-4 md:mb-3">
    <div class="w-full px-4 mb-6 md:w-4/6 lg:w-6/12 md:mb-0">
        <div class="flex flex-wrap items-center -mx-4">
            <div class="w-full px-4 mb-3 md:w-1/3">
                <a href="{{ route('product.show', $product) }}">
                <div class="flex items-center justify-center w-full h-32 bg-gray-100 md:w-24"
                >
                    <img class="object-contain h-full" src="{{ $product->image }}" alt="{{ $product->name }}">
                </div>
                </a>
            </div>
            <div class="w-full px-4 md:w-2/3">
                <a href="{{ route('product.show', $product) }}">
                <h3 @class([
                        'mb-2 text-xl font-bold font-heading text',
                        'text-red-500' => $invalid
                    ])
                >{{ $product->name }}</h3>
                </a>
                <p class="text-gray-500">
                    @foreach($product->attributeValues as $attributeValue)
                        {{ $attributeValue->value}}
                        @if(!$loop->last)
                            , 
                        @endif
                    @endforeach
                </p>
            </div>
        </div>
        <a href="#" class="mt-2 text-sm font-medium text-primary-600 dark:text-primary-500 hover:underline"
            wire:click.prevent="moveToWishlist({{$product}})"
        >
            {{ __('shopping_cart.move.wishlist') }}
        </a>
    </div>
    <div class="hidden px-4 lg:block lg:w-2/12">
        <p class="text-lg font-bold text-primary-500 font-heading">{{ $product->taxed_price }}€</p>
        {{-- <span class="text-xs text-gray-500 line-through">$33.69</span> --}}
    </div>
    <div class="w-auto text-center md:w-1/6 lg:w-2/12"
        x-data="{
            initialValue : {{ $item['qty'] }},
            min : $refs.inputNumber.min,
            modified : false,
            validate(){
                {{-- if(event.target.value < event.target.min)
                    event.target.value = event.target.min; --}}
                    if(!$refs.inputNumber.value ||  $refs.inputNumber.value<0)
                        $refs.inputNumber.value = 1;
                    $dispatch('change', $refs.inputNumber.value);
                    this.modified = false;
                    $refs.inputNumber.value = {{ $item['qty']}};
            },
            increase(event){
                {{-- if(this.max && this.$refs.inputNumber.value<this.max)
                { --}}
                    this.$refs.inputNumber.value++;
                    this.modified = true;
                    this.validate();
                {{-- } --}}
            },
            decrease(event){
                if(this.min && this.$refs.inputNumber.value>this.min)
                {
                    this.$refs.inputNumber.value--;
                    this.modified = true;
                    this.validate();
                }
            },
        }"
        wire:model.lazy="item.qty"
    >
        <div class="inline-flex items-center px-4 mx-auto font-semibold text-gray-500 border border-gray-200 rounded-md font-heading focus:ring-primary-300 focus:border-primary-300">
            <button class="py-2 hover:text-gray-700"
                @click="decrease($event)"
            >
                <x-icons.minus/>
            </button>
            <input class="w-12 px-2 py-4 m-0 text-center border-0 rounded-md md:text-right focus:ring-transparent focus:outline-none"
                type="number" 
                value="{{$item['qty']}}" min="1" max="999"
                @change.stop="validate"
                @input.stop=""
                x-ref="inputNumber"
            >
            <button class="py-2 hover:text-gray-700"
                @click="increase($event)"
            >
                <x-icons.plus/>
            </button>
        </div>
    </div>
    <div class="w-auto px-4 text-right md:w-1/6 lg:w-2/12">
        <p class="text-lg font-bold text-primary-500 font-heading">
            {{ $product->pricePerQuantity($item['qty']!='' ? $item['qty'] : 1, $product->taxed_price) }}€
        </p>
    </div>
    <a href="#" class="absolute top-0 right-0 font-medium text-primary-600 dark:text-primary-500 hover:underline"
        wire:click.prevent="removeFromCart({{ $product->id }})"
    >
        <x-icons.x class="w-4 h-4 text-gray-600"/>
    </a>
</div>