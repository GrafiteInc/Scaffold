<ul class="nav nav-tabs d-none d-sm-none d-md-flex">
    <li class="nav-item">
        <a class="{{ route_link_class(['user.billing', 'user.billing.details']) }}" href="{{ route('user.billing') }}">Billing</a>
    </li>
    @can('subscribed', auth()->user())
        @can('subscription-not-cancelled', auth()->user())
            <li class="nav-item">
                <a class="{{ route_link_class('user.billing.payment-method') }}" href="{{ route('user.billing.payment-method') }}">Payment Method</a>
            </li>
            <li class="nav-item">
                <a class="{{ route_link_class('user.billing.change-plan') }}" href="{{ route('user.billing.change-plan') }}">Change Plan</a>
            </li>
            <li class="nav-item">
                <a class="{{ route_link_class(['user.billing.coupons']) }}" href="{{ route('user.billing.coupons') }}">Coupon</a>
            </li>
        @endcan
        @can('subscription-cancelled', auth()->user())
            <li class="nav-item">
                <a class="{{ route_link_class(['user.billing.renew']) }}" href="{{ route('user.billing.renew') }}">Renew Subscription</a>
            </li>
        @endcan
        <li class="nav-item">
            <a class="{{ route_link_class(['user.billing.invoices']) }}" href="{{ route('user.billing.invoices') }}">Invoices</a>
        </li>
    @endcan
</ul>
