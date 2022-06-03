<tr class="border-b dark:bg-gray-800 dark:border-gray-700 odd:bg-white even:bg-gray-50 odd:dark:bg-gray-800 even:dark:bg-gray-700"
>
    <td scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
        <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
            wire:click.prevent="removeFromCart({{ $product->id }})"
        >
            Remove
        </a>
    </td>
    <td scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
        <img class="h-20" src="{{ $product->imagePath() }}"/>
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
        <select wire:model.lazy="item.qty">
            @for( $i=1 ; $i<=10 ; $i++)
                <option value="{{$i}}" >{{ $i }}</option>
            @endfor
        </select>
    </td>
    <td class="px-6 py-4">
        <span >{{ $product->pricePerQuantity($item['qty']) }}€</span>
        
    </td>
    <td class="px-6 py-4 text-right">
        <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
            wire:click.prevent="moveToWishlist({{$product}})"
        >
            Move to Wishlist
        </a>
    </td>
</tr>