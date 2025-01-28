<table class="table table-bordered border table-secondary border-light table-striped">
    @foreach ($items as $k => $item)
        <tr>
            <td><strong>{{__('users.the supplier')}}</strong><br>{{$item->supplier->name}}</td>
            <td><strong>{{__('global.mobile')}}</strong><br>{{$item->supplier->mobile}}</td>
            <td><strong>{{__('products.name.name')}}</strong><br>{{$item->detailKey->detail->product->name_ar}}</td>
            <td><strong>{{__('products.qty')}}</strong><br>{{$item->quantity}}</td>
            <td><strong>{{__('products.price')}}</strong><br>{{$item->price}}</td>
            <td class=""><strong>{{__('orders.Total Amount With VAT')}}</strong><br>{{$item->price*$item->quantity}}</td>
        </tr>
    @endforeach
    <tr>
        <td colspan="6" class="bg-dark-subtle">
            <div class="col-md-6 text-end">
                <h6><strong>{{ __('orders.Change Status') }}</strong></h6>
                <div class=" d-flex align-items-center">
                    <select class="form-select mb-0" id="order_status_select_{{ $id }}">
                        @foreach (App\Models\Order::$ORDER_STATUS as $status)
                            <option value="{{ $status }}">{{ __('orders.' . $status) }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-sm btn-success col-md-4 mx-2"
                        onclick="change_order_status({{ $id }})">{{ __('orders.Change Status') }}</button>
                </div>
            </div>
        </td>
    </tr>
</table>
