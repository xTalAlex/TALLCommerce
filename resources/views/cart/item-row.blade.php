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
            @for( $i=1 ; $i<=10 ; $i++)
                <option value="{{$i}}" >{{ $i }}</option>
            @endfor
        </select> --}}
        <x-number-input wire:model="item.qty" min="1" max="{{$product->quantity}}"/>
    </td>
    <td class="px-6 py-4">
        <span >{{ $product->pricePerQuantity($item['qty']) }}€</span>
        
    </td>
    <td class="px-6 py-4 text-right">
        <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
            wire:click.prevent="moveToWishlist({{$product}})"
        >
            {{ __('shopping_cart.move.wishlist') }}
        </a>
    </td>
</tr>