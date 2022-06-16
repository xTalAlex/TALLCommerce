<tr class="border-b 
    @if($invalid)
    dark:bg-red-600 dark:border-red-500 odd:bg-red-300 even:bg-red-400 odd:dark:bg-red-600 even:dark:bg-red-500
    @else
    dark:bg-gray-800 dark:border-gray-700 odd:bg-white even:bg-gray-50 odd:dark:bg-gray-800 even:dark:bg-gray-700
    @endif
    "
>
    <td scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
        <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
            wire:click.prevent="removeFromCart({{ $product->id }})"
        >
            {{ __('Remove') }}
        </a>
    </td>
    <td scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
        <img class="h-20" src="{{ $product->image }}"/>
    </td>
    <td scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
        {{ $product->name }}
    </td>
    <td class="px-6 py-4">
        @foreach($product->categories as $category)
            {{ $category->name}}
            @if(!$loop->last)
                , 
            @endif
        @endforeach
    </td>
    <td class="px-6 py-4">
        {{-- <select wire:model.lazy="item.qty">
            @for( $i=1 ; $i<=($product->quantity>10 ? 10 : $product->quantity ) ; $i++)
                <option value="{{$i}}" >{{ $i }}</option>
            @endfor
        </select> --}}
        <div class="flex items-center justify-center"
            x-data="{
                inputValue : $refs.root.value,
                min : $refs.inputNumber.min,
                max : $refs.inputNumber.max,
                validate(event){
                    if(!event.target.value || event.target.value < event.target.min)
                        event.target.value = event.target.min
                },
                increase(){
                    {{-- if(this.max && this.$refs.inputNumber.value<this.max)
                    { --}}
                        this.$refs.inputNumber.value++;
                        $dispatch('input',this.$refs.inputNumber.value);
                    {{-- } --}}
                },
                decrease(){
                    if(this.min && this.$refs.inputNumber.value>this.min)
                    {
                        this.$refs.inputNumber.value--;
                        $dispatch('input',this.$refs.inputNumber.value);
                    }
                },
            }"
            wire:model="item.qty"
            x-ref="root"
        >
            <span class="w-8 h-full p-2 mr-2 font-medium text-center bg-gray-200 rounded-lg cursor-pointer select-none"
                @click="decrease()"
                x-show="true"
            >-</span>
            <x-jet-input type="number" x-bind:value="inputValue" min="1" max="{{$product->quantity}}"
                @change="validate($event)"
                x-ref="inputNumber"
            />
            <span class="w-8 h-full p-2 ml-2 font-medium text-center bg-gray-200 rounded-lg cursor-pointer select-none"
                @click="increase()"
                x-show="true"
            >+</span>
        </div>
    </td>
    <td class="px-6 py-4">

        <span >{{ $product->pricePerQuantity($item['qty']!='' ? $item['qty'] : 1) }}€</span>
        
    </td>
    <td class="px-6 py-4 text-right">
        <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
            wire:click.prevent="moveToWishlist({{$product}})"
        >
            {{ __('shopping_cart.move.wishlist') }}
        </a>
    </td>
</tr>