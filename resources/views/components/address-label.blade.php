<div>
    <div>{{ $address->full_name }}</div>
    <div>{{ $address->address }}</div>
    <div>
        <span>{{ $address->city }}</span>
        @if($address->province)
            <span>({{ $address->province }})</span>
        @endif
        @if($address->postal_code)
            <span>, {{ $address->postal_code }}</span>
        @endif
    </div>
    @if($address->country_region)
        <div>{{ $address->country_region }}</div>
    @endif
</div>