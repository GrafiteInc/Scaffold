const subscriptionForm = document.getElementById('subscription-form');

if (subscriptionForm) {
    let _color = (window.dark_mode === "false") ? '#111' : '#FFF';

    const stripe = Stripe(window.stripe_key);
    const elements = stripe.elements();
    const cardElement = elements.create('card', {
        style: { base: { color: _color } },
        hidePostalCode: true
    });

    cardElement.mount('#card-element');

    const cardHolderPlan = document.getElementById('card-holder-plan');
    const cardHolderName = document.getElementById('card-holder-name');
    const cardHolderEmail = document.getElementById('card-holder-email');
    const cardHolderState = document.getElementById('card-holder-state');
    const cardHolderCountry = document.getElementById('card-holder-country');
    const cardButton = document.getElementById('card-button');
    const clientSecret = cardButton.dataset.secret;

    cardButton.addEventListener('click', async (e) => {
        if (
            cardHolderPlan.value == ''
            || cardHolderName.value == ''
            || cardHolderEmail.value == ''
            || cardHolderState.value == ''
            || cardHolderCountry.value == ''
        ) {
            window.snotify.warning("Please complete all fields.");

            return false;
        }

        cardButton.disabled = true;

        const { setupIntent, error } = await stripe.confirmCardSetup(
            clientSecret, {
                payment_method: {
                    card: cardElement,
                    billing_details: {
                        name: cardHolderName.value
                    }
                }
            }
        );

        if (error) {
            window.snotify.warning(error.message);
            cardButton.disabled = false;
        } else {
            window.pendingModal();
            window.axios.post(route('ajax.billing.subscription.create'), {
                plan: cardHolderPlan.value,
                name: cardHolderName.value,
                email: cardHolderEmail.value,
                state: cardHolderState.value,
                country: cardHolderCountry.value,
                payment_method: setupIntent.payment_method
            })
            .then(results => {
                window.location = route('user.billing.details');
            })
            .catch(err => {
                window.snotify.warning(err.data.data.message);

                if (err.data.data.redirect) {
                    window.location = err.data.data.redirect;
                }
            });
        }
    });
}