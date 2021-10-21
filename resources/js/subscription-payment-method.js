const paymentMethodForm = document.getElementById('payment-method-form');

if (paymentMethodForm) {
    let _color = (! window.dark_mode) ? '#111' : '#FFF';

    const stripe = window.Stripe(window.stripe_key);
    const elements = stripe.elements();
    const cardElement = elements.create('card', {
        style: { base: { color: _color } },
        hidePostalCode: true
    });

    cardElement.mount('#card-element');

    const cardButton = document.getElementById('card-button');
    const clientSecret = cardButton.dataset.secret;

    cardButton.addEventListener('click', async (e) => {
        cardButton.disabled = true;
        window.pending();

        const { setupIntent, error } = await stripe.confirmCardSetup(
            clientSecret, {
                payment_method: {
                    card: cardElement
                }
            }
        );

        if (error) {
            window.pendingHide();
            window.notify.warning(error.message);
            cardButton.disabled = false;
        } else {
            window.axios.post(route('ajax.billing.subscription.payment-method'), {
                payment_method: setupIntent.payment_method
            })
                .then((results) => {
                    window.location = route('user.billing.details');
                })
                .catch((err) => {
                    window.notify.warning(err.data.data.message);
                });
        }
    });
}
