@include('head')
@section('title', 'Checkout')

<main class="min-h-screen bg-[#f8fafc] flex items-center justify-center px-4">
    <div class="w-full max-w-6xl bg-white rounded-xl shadow-lg
                grid grid-cols-1 md:grid-cols-2 overflow-hidden">

        <!-- ================= LEFT : ORDER SUMMARY ================= -->
        <aside class="bg-gray-50 p-8 flex flex-col justify-between">

            <div>
                <h2 class="text-lg font-semibold mb-6">
                    Order Summary
                </h2>

                <div class="space-y-4 text-sm">

                    <div>
                        <p class="font-semibold text-gray-900">
                            {{ $order->ticket->event->name }}
                        </p>
                        <p class="text-gray-500">
                            {{ $order->ticket->ticket_name }}
                        </p>
                    </div>

                    <div class="flex justify-between">
                        <span>Price</span>
                        <span>RM {{ number_format($order->unit_price, 2) }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span>Quantity</span>
                        <span>{{ $order->quantity }}</span>
                    </div>

                    <hr>

                    <div class="flex justify-between text-base font-bold">
                        <span>Total</span>
                        <span>
                            RM {{ number_format($order->total_amount, 2) }}
                        </span>
                    </div>
                </div>
            </div>

            <p class="text-xs text-gray-400 mt-10">
                Secure payment powered by Stripe
            </p>
        </aside>

        <!-- ================= RIGHT : PAYMENT ================= -->
        <section class="p-8 flex flex-col justify-center">

            <h2 class="text-xl font-bold mb-6">
                Payment
            </h2>

            <form id="payment-form" class="space-y-6">

                <!-- Card Element -->
                <div>
                    <label class="block text-sm font-medium mb-2">
                        Card Information
                    </label>

                    <div id="card-element"
                         class="p-4 border rounded-md bg-white"></div>
                </div>

                <button
                    class="w-full h-12 rounded-md bg-black
                           text-white font-semibold text-lg
                           hover:bg-gray-900 transition">
                    Pay RM {{ number_format($order->total_amount, 2) }}
                </button>
            </form>

            <p class="text-xs text-gray-400 mt-4 text-center">
                By confirming, you agree to Stripe’s secure payment processing.
            </p>
        </section>

    </div>
</main>

<!-- ================= STRIPE ================= -->
<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe("{{ $stripeKey }}");
    const elements = stripe.elements();

    const card = elements.create('card', {
        style: {
            base: {
                fontSize: '16px',
                color: '#111827',
                '::placeholder': { color: '#9CA3AF' },
            },
        }
    });

    card.mount('#card-element');

    document
        .getElementById('payment-form')
        .addEventListener('submit', async (e) => {
            e.preventDefault();

            const { error } = await stripe.confirmCardPayment(
                "{{ $clientSecret }}",
                { payment_method: { card } }
            );

            if (error) {
                alert(error.message);
            } else {
                window.location.href =
                    "{{ route('ticket-orders.success', $order->id) }}";
            }
        });
</script>

@include('foot')
