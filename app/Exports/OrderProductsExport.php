<?php

namespace App\Exports;

use App\Models\OrderStatus;
use App\Models\OrderProduct;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrderProductsExport implements FromQuery, WithHeadings, WithMapping
{
    protected $from;
    protected $paied_status_id;

    public function __construct($from = null)
    {
        $this->from = $from;
        $this->paied_status_id = OrderStatus::where('name','paied')->first()->id;
    }
    
    public function headings(): array
    {
        return [
            'Numero Ordine',
            'Data ordine',
            'Gateway Pagamento',
            'Codice Pagamento',
            'Codice Articolo',
            'Quantità',
            'Prezzo Unitario',
            '% Sconto',
            'Codice Fiscale',
            'Partita IVA',
            'Email',
            'Ragione Sociale',
            'Indirizzo',
            'Città',
            'Provincia',
            'CAP'
        ];
    }
        
    /**
    * @var OrderProduct $order_product
    */
    public function map($order_product): array
    {
        return [
            $order_product->order->id,
            $order_product->order->created_at->format('d/m/Y'),
            $order_product->order->payment_gateway,
            $order_product->order->payment_id,
            $order_product->product->sku,
            $order_product->quantity,
            $order_product->price,
            strval(($order_product->discount/$order_product->price)),
            $order_product->order->fiscal_code,
            $order_product->order->vat,
            $order_product->order->email,
            $order_product->order->billingAddress()->company ? $order_product->order->billingAddress()->company : $order_product->order->billingAddress()->full_name,
            $order_product->order->billingAddress()->address,
            $order_product->order->billingAddress()->city,
            $order_product->order->billingAddress()->province,
            $order_product->order->billingAddress()->postal_code

        ];
    }

    public function query()
    {
        return OrderProduct::with(['order','product'])
            ->when($this->from, fn ($query) => 
                $query->whereHas('order',  fn ($query) =>
                    $query->whereHas('history', fn ($query) => $query->where('order_status_id', $this->paied_status_id))
                        ->where('updated_at','>=', $this->from) 
                )
            );
    }
}
