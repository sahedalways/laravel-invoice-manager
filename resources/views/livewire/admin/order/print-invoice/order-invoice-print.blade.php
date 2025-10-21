<div>
    <!DOCTYPE html>
    <html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta charset="utf-8" />
        <title>Print Invoice - {{ $order->order_number }}</title>
        <link rel="icon" type="image/png" href="{{ siteSetting()->favicon_url }}">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700&display=swap"
            rel="stylesheet">
        <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/css/argon-dashboard.min28b5.css?v=2.0.0') }}" rel="stylesheet" />
        <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" />
    </head>

    <body onload="window.print()">
        <div class="row">
            <div class="col-lg-9 mx-auto">
                <div class="card mb-4">

                    <!-- Header -->
                    <div class="card-header p-4">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <h5 class="text-uppercase fw-500">{{ siteSetting()->site_title }}</h5>
                                <p class="text-sm mb-0">{{ siteSetting()->site_phone_number }}</p>
                                <p class="text-sm mb-0">{{ siteSetting()->site_email }}</p>
                            </div>
                            <div class="col-6 text-end">
                                <h6 class="text-uppercase fw-500">Order ID: <span
                                        class="fw-600">#{{ $order->order_number }}</span></h6>
                                <p class="text-sm mb-1">Order Date: <span
                                        class="fw-600">{{ $order->date->format('d/m/Y') }}</span></p>
                                <div class="d-flex justify-content-end align-items-center">
                                    <div>Order Status:</div>
                                    <div class="dropdown ms-2">
                                        <button
                                            class="btn btn-xs bg-secondary mb-0 text-white">{{ $order->status }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Customer Info -->
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6 class="fw-500 mb-1">Customer Info:</h6>
                                <p class="text-sm mb-0">Name: {{ $order->customer->name ?? 'Walk-In Customer' }}</p>
                                <p class="text-sm mb-0">Phone: {{ $order->customer->phone ?? 'N/A' }}</p>
                                <p class="text-sm mb-0">Email: {{ $order->customer->email ?? 'N/A' }}</p>
                                <p class="text-sm mb-0">Address: {{ $order->customer->address ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Product Name</th>
                                        <th class="text-center">QTY</th>
                                        <th>Discount</th>
                                        <th>Tax</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orderdetails as $item)
                                        @php $product = $item->product; @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $product->image_url }}" class="avatar avatar-sm me-3">
                                                    <div class="d-flex flex-column">
                                                        <h6 class="mb-1 text-sm">{{ $product->name }}</h6>
                                                        <span
                                                            class="text-xs fw-600 text-primary">${{ number_format($item->total, 2) }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">{{ $item->qty }}</td>
                                            <td>{{ $order->discount ?? 0 }}%</td>
                                            <td>N/A</td>
                                            <td>${{ number_format($item->total, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Payment Info -->
                    <div class="card-footer px-4 mt-3">
                        <h6 class="fw-500 mb-2">Payment Details:</h6>
                        <div class="row">
                            <div class="col text-sm fw-600">Payment Mode:</div>
                            <div class="col-auto text-sm">Cash</div>
                        </div>
                        <div class="row">
                            <div class="col text-sm fw-600">Gross Total:</div>
                            <div class="col-auto text-sm">${{ number_format($order->total_price, 2) }}</div>
                        </div>
                        <div class="row">
                            <div class="col text-sm fw-600">Discount:</div>
                            <div class="col-auto text-sm">{{ $order->discount ?? 0 }}%</div>
                        </div>
                        <div class="row">
                            <div class="col text-sm fw-600">Paid Amount:</div>
                            <div class="col-auto text-sm">${{ number_format($order->total_price, 2) }}</div>
                        </div>
                        <div class="row">
                            <div class="col text-sm fw-600">Due Amount:</div>
                            <div class="col-auto text-sm">$0.00</div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </body>

    </html>
</div>

<script type="text/javascript">
    "use strict";
    window.onload = function() {
        window.print();
        setTimeout(function() {}, 1);
    }
</script>
<script>
    window.addEventListener('print-ticket', event => {
        let printWindow = window.open(event.detail.url, '_blank');
        printWindow.focus();
        printWindow.print();
    });
</script>
