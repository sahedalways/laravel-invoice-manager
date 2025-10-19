@section('title', siteSetting()->site_title)

<div>
    <div class="row" wire:poll.60s>
        <div class="col-lg-12">
            <div class="row mt-5 g-4">

                {{-- @php
                    $cardStylePrimary = 'background-color: #f0f4f8; color: #111827; border-radius: 12px;';
                    $cardStyleSecondary = 'background-color: #e3eaf2; color: #111827; border-radius: 12px;';
                @endphp

                <!-- Today Bookings -->
                <x-dashboard-card title="Today’s Bookings" :value="$todaysBookings" icon="fa-calendar-day"
                    style="{{ $cardStylePrimary }}" />

                <!-- Monthly Bookings -->
                <x-dashboard-card title="Monthly Bookings" :value="$monthlyBookings" icon="fa-calendar-alt"
                    style="{{ $cardStylePrimary }}" />

                <!-- Yearly Bookings -->
                <x-dashboard-card title="Yearly Bookings" :value="$yearlyBookings" icon="fa-calendar"
                    style="{{ $cardStylePrimary }}" />

                <!-- Total Profit -->
                <x-dashboard-card title="Total Profit" :value="'৳' . number_format($totalProfit, 2)" icon="fa-dollar-sign"
                    style="{{ $cardStylePrimary }}" />

                <!-- Pending Bookings -->
                <x-dashboard-card title="Pending Bookings" :value="$pendingBookings" icon="fa-clock"
                    style="{{ $cardStyleSecondary }}" />

                <!-- Confirmed Bookings -->
                <x-dashboard-card title="Confirmed Bookings" :value="$confirmedBookings" icon="fa-thumbs-up"
                    style="{{ $cardStyleSecondary }}" />

                <!-- Cancelled Bookings -->
                <x-dashboard-card title="Cancelled Bookings" :value="$cancelledBookings" icon="fa-times-circle"
                    style="{{ $cardStyleSecondary }}" />

                <!-- Completed Bookings -->
                <x-dashboard-card title="Completed Bookings" :value="$completedBookings" icon="fa-check-circle"
                    style="{{ $cardStyleSecondary }}" />



                <!-- Total Revenue -->
                <x-dashboard-card title="Total Revenue" :value="'৳' . number_format($totalRevenue, 2)" icon="fa-coins"
                    style="{{ $cardStylePrimary }}" />



                <!-- Total Users -->
                <x-dashboard-card title="Total Users" :value="$totalUsers" icon="fa-users"
                    style="{{ $cardStylePrimary }}" />

                <!-- Customer Feedbacks -->
                <x-dashboard-card title="Customer Feedbacks" :value="$customerFeedbacks" icon="fa-comments"
                    style="{{ $cardStylePrimary }}" />

                <!-- Hotels & Resorts -->
                <x-dashboard-card title="Hotels & Resorts" :value="$hotelsResorts" icon="fa-hotel"
                    style="{{ $cardStylePrimary }}" /> --}}

            </div>
        </div>
    </div>
</div>
