<table>
    <thead>
    <tr>
       <th>Numero Ordine</th>
       <th>Data ordine</th>
       <th>Gateway Pagamento</th>
       <th>Codice Pagamento</th>
       <th>Codice Articolo</th>
       <th>Quantità</th>
       <th>Prezzo Unitario</th>
       <th>% Sconto</th>
       <th>Codice Fiscale</th>
       <th>Partita IVA</th>
       <th>Email</th>
       <th>Ragione Sociale</th>
       <th>Indirizzo</th>
       <th>Città</th>
       <th>Provincia</th>
       <th>CAP</th>
    </tr>
    </thead>
    <tbody>
    @foreach($orders as $order)
        <tr>
            <td>CF{{$order->id}}</td>
            <td>{{$order->created_at->format('d/m/Y')}}</td>
            <td>{{$order->payment_gateway}}</td>
            <td>{{$order->payment_id}}</td>
            <td>{{$shipping_sku}}</td>
            <td>1</td>
            <td>{{$order->shipping_price}}</td>
            <td>{{0}}</td>
            <td>{{$order->fiscal_code ? $order->fiscal_code : $order->vat}}</td>
            <td>{{$order->vat}}</td>
            <td>{{$order->email}}</td>
            <td>{{$order->billing_address_full_name}}</td>
            <td>{{$order->billing_address_address}}</td>
            <td>{{$order->billing_address_city}}</td>
            <td>{{$order->billing_address_province}}</td>
            <td>{{$order->billing_address_postal_code}}</td>
        </tr>
        @foreach($order->products as $product)
        <tr>
            <td>CF{{$order->id}}</td>
            <td>{{$order->created_at->format('d/m/Y')}}</td>
            <td>{{$order->payment_gateway}}</td>
            <td>{{$order->payment_id}}</td>
            <td>{{$product->sku}}</td>
            <td>{{$product->pivot->quantity}}</td>
            <td>{{$product->pivot->price}}</td>
            <td>{{0}}</td>
            <td>{{$order->fiscal_code}}</td>
            <td>{{$order->vat}}</td>
            <td>{{$order->email}}</td>
            <td>{{$order->billing_address_full_name}}</td>
            <td>{{$order->billing_address_address}}</td>
            <td>{{$order->billing_address_city}}</td>
            <td>{{$order->billing_address_province}}</td>
            <td>{{$order->billing_address_postal_code}}</td>
        </tr>
        @endforeach
    @endforeach
    </tbody>
</table>