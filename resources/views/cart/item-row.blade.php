<div class="relative flex flex-wrap items-center mb-12">

    <div class="flex flex-col w-full mb-6 sm:flex-row md:w-6/12 lg:6/12 md:mb-0">
        <div class="relative w-full md:w-1/2">
            <a href="{{ route('product.show', $product) }}">
                <div class="flex items-start justify-center w-full h-40 md:justify-start">
                    <img class="object-contain object-top h-full aspect-video" src="{{ $product->image }}" alt="{{ $product->name }}">
                </div>
            </a>
            @if(!$this->wishlistContains($product))
                <div class="absolute flex items-center justify-center w-6 h-6 p-1 leading-none text-center rounded-full shadow-xl cursor-pointer text-primary-500 hover:text-primary-600 hover:bg-primary-100 bg-primary-50 -top-1 -left-2"
                    title="{{ __('shopping_cart.add.wishlist') }}"
                    wire:click.prevent="addToWishlist({{ $product->id }})"
                >
                    <x-icons.heart class="w-full h-full"
                        filled="false"
                    />
                </div>
            @else
                <div class="absolute flex items-center justify-center w-6 h-6 p-1 leading-none text-center rounded-full shadow-xl cursor-pointer text-primary-500 hover:text-primary-600 hover:bg-primary-100 bg-primary-50 -top-1 -left-2"
                    title="{{ __('shopping_cart.remove.wishlist') }}"
                    wire:click.prevent="removeFromWishlist({{ $product->id }})"
                >
                    <x-icons.heart class="w-full h-full"
                    />
                </div>
            @endif
        </div>

        <div class="w-full mt-6 md:w-1/2 sm:mt-0 sm:pl-6">
            <a href="{{ route('product.show', $product) }}">
                <div class="font-bold"
                >{{ $product->name }}</div>
            </a>
            @if(!$product->short_description)
                <div class="text-gray-500">
                    {{ $product->short_description}}
                </div>
            @endif
            @if(count($product->attributeValues))
                <div class="my-6 text-gray-500">
                    @foreach($product->attributeValues as $attributeValue)
                        {{ $attributeValue->label}}
                        @if(!$loop->last)
                            , 
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="items-center justify-center hidden w-full lg:px-4 lg:w-2/12 lg:flex">
        <div class="w-full overflow-hidden text-base font-black text-gray-500 overflow-ellipsis">
            {{$product->taxed_selling_price}}€
        </div>
    </div>

    <div class="order-2 w-full mt-6 text-center md:order-1 md:mt-0 md:w-3/12 lg:w-2/12"
        x-data="quantityInput({{$item['qty']}})"
    >
        <div class="flex text-gray-500 border border-secondary-300 flex-nowrap focus-within:border-secondary-300 focus-within:ring focus-within:ring-secondary-200 focus-within:ring-opacity-50"
            wire:model.lazy="item.qty"
        >
            <button class="grid grow place-items-center text-secondary-500 disabled:opacity-50"
                @click.debounce.200ms="decrease"
            >
                <x-icons.minus/>
            </button>
            <input class="w-16 px-2 py-4 font-semibold text-center text-gray-900 border-none appearance-none lg:text-right focus:ring-transparent focus:outline-none disabled:opacity-50"
                type="number"
                @change="validate"
                @input.stop=""
                x-ref="inputNumber"
                value="{{$item['qty']}}"
            >
            <button class="grid grow place-items-center text-secondary-500 disabled:opacity-50"
                @click.debounce.200ms="increase"
            >
                <x-icons.plus/>
            </button>
        </div>
    </div>

    <div class="order-1 w-full px-4 mt-6 text-center md:px-0 md:pl-4 md:order-2 md:text-right md:mt-0 md:w-3/12 lg:w-2/12">
        <div class="text-lg font-black text-gray-900">
            {{ $product->applyTax( $product->pricePerQuantity($item['qty']!='' ? $item['qty'] : 1, $product->price) ) }}€
        </div>
    </div>
    <div class="absolute flex items-center justify-center w-6 h-6 p-1 leading-none text-center text-gray-500 rounded-full shadow-xl cursor-pointer hover:text-gray-900 hover:bg-secondary-100 bg-secondary-50 -top-1 -right-2"
        wire:click.prevent="removeFromCart({{ $product->id }})"
        title="{{ __('shopping_cart.remove.cart') }}"
    >
        <x-icons.trash class="w-full h-full"/>
    </div>
</div>

@once
    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('quantityInput', ( itemQty = 0 ) => ({
                    itemQty : itemQty,
                    min: 1,
                    max: 99,
                    validate(){
                        if(this.$refs.inputNumber){
                            if(!this.$refs.inputNumber.value ||  this.$refs.inputNumber.value<this.min || isNaN( this.$refs.inputNumber.value))
                                this.$refs.inputNumber.value = this.min;
                            if(this.$refs.inputNumber.value > this.max)
                                this.$refs.inputNumber.value = this.max;
                            //this.$dispatch('change', this.$refs.inputNumber.value);
                            this.itemQty = this.$refs.inputNumber.value;
                        }
                    },
                    increase(){
                        if(this.$refs.inputNumber){
                            if(this.max == null || this.$refs.inputNumber.value<this.max)
                            {
                                this.$refs.inputNumber.value++;
                                this.$dispatch('change', this.$refs.inputNumber.value);
                            }
                        }
                    },
                    decrease(){
                        if(this.$refs.inputNumber){
                            if(this.min == null || this.$refs.inputNumber.value>this.min)
                            {
                                this.$refs.inputNumber.value--;
                                this.$dispatch('change', this.$refs.inputNumber.value);
                            }
                        }
                    },

                    init() {
                        this.min = this.$refs.inputNumber.attributes.min != undefined ? this.$refs.inputNumber.attributes.min.value : this.min;
                        this.max = this.$refs.inputNumber.attributes.max != undefined ? this.$refs.inputNumber.attributes.max.value : this.max;
                    }
                }))
            })
        </script>
    @endpush
@endonce