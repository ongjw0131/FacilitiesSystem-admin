@include('head')
@section('title', 'My Tickets')

<div class="max-w-[1440px] mx-auto px-4 md:px-8 py-10">

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-black text-[#111318] dark:text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-[32px]">
                    confirmation_number
                </span>
                My Tickets
            </h1>
            <p class="text-[#616f89] dark:text-slate-400 mt-1">
                View all ticket orders you have made for university events
            </p>
        </div>

        <a href="{{ route('profile.show') }}"
           class="inline-flex items-center gap-2 h-10 px-4 rounded-lg border border-[#dbdfe6]
                  dark:border-slate-700 text-sm font-bold text-[#111318] dark:text-slate-300
                  hover:bg-[#f0f2f4] dark:hover:bg-slate-800 transition">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span>
            Back to Profile
        </a>
    </div>

    {{-- Empty State --}}
    @if($orders->isEmpty())
        <div class="bg-white dark:bg-surface-dark rounded-xl p-12 text-center border border-[#dbdfe6] dark:border-slate-700">
            <span class="material-symbols-outlined text-gray-400 text-6xl mb-4">
                confirmation_number
            </span>
            <h3 class="text-xl font-bold text-[#111318] dark:text-white mb-2">
                No Ticket Orders
            </h3>
            <p class="text-[#616f89] dark:text-slate-400">
                You haven’t purchased any tickets yet.
            </p>
        </div>
    @else

    <!-- Ticket Orders Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @foreach($orders as $order)
            <div class="group bg-white dark:bg-surface-dark rounded-xl shadow
                        hover:shadow-lg transition overflow-hidden border
                        border-[#dbdfe6] dark:border-slate-700">

                <!-- Card Header -->
                <div class="relative h-28 bg-gradient-to-br from-primary to-blue-700
                            flex items-center justify-center">
                    <span class="material-symbols-outlined text-white/30"
                          style="font-size:72px">
                        event
                    </span>

                    <div class="absolute top-3 right-3">
                        <span class="px-3 py-1 rounded-full text-xs font-bold
                            {{ $order->status === 'paid'
                                ? 'bg-green-100 text-green-700'
                                : 'bg-orange-100 text-orange-700' }}">
                            {{ strtoupper($order->status) }}
                        </span>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="p-5 space-y-3">
                    <h3 class="text-lg font-bold text-[#111318] dark:text-white
                               group-hover:text-primary transition">
                        {{ $order->ticket->event->name }}
                    </h3>

                    <p class="text-sm text-[#616f89] dark:text-slate-400">
                        🎟️ {{ $order->ticket->ticket_name }}
                    </p>

                    <div class="flex justify-between items-center text-sm">
                        <span class="text-[#616f89] dark:text-slate-400">
                            Quantity
                        </span>
                        <span class="font-bold">
                            {{ $order->quantity }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center text-sm">
                        <span class="text-[#616f89] dark:text-slate-400">
                            Total Paid
                        </span>
                        <span class="font-black text-lg text-primary">
                            RM {{ number_format($order->total_amount, 2) }}
                        </span>
                    </div>
                </div>

                <!-- Card Footer -->
                <div class="px-5 py-4 border-t border-[#f0f2f4] dark:border-slate-700
                            text-xs text-[#616f89] dark:text-slate-400">
                    Ordered on {{ \Carbon\Carbon::parse($order->ordered_at)->format('M d, Y • h:i A') }}
                </div>
            </div>
        @endforeach
    </div>
    @endif

</div>

@include('foot')
