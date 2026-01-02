@include('head')
@section('title', 'My Tickets - University Event Manager')

<!-- Main Page Layout -->
<div class="flex-1 max-w-[1440px] mx-auto w-full px-4 md:px-6 py-8">
    <!-- Page Heading -->
    <div class="mb-8">
        <h1 class="text-[#111318] dark:text-white text-3xl font-black leading-tight tracking-[-0.033em]">My Tickets</h1>
        <p class="text-[#616f89] dark:text-slate-400 text-base font-normal mt-2">View and manage your purchased event tickets.</p>
    </div>

    @if ($ticketOrders->isEmpty())
        <!-- Empty State -->
        <div class="bg-white dark:bg-surface-dark rounded-xl border border-[#dbdfe6] dark:border-slate-700 p-12 text-center">
            <div class="flex justify-center mb-4">
                <span class="material-symbols-outlined text-6xl text-[#616f89] dark:text-slate-500">confirmation_number</span>
            </div>
            <h3 class="text-[#111318] dark:text-white text-lg font-bold mb-2">No Tickets Yet</h3>
            <p class="text-[#616f89] dark:text-slate-400 mb-6">You haven't purchased any tickets yet. Explore events and buy tickets to attend!</p>
            <a href="{{ route('events.index') }}" class="inline-flex items-center gap-2 px-6 py-2 bg-primary text-white font-bold rounded-lg hover:bg-blue-700 transition">
                <span class="material-symbols-outlined">event</span>
                Browse Events
            </a>
        </div>
    @else
        <!-- Tickets Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($ticketOrders as $order)
                <div class="bg-white dark:bg-surface-dark rounded-xl border border-[#dbdfe6] dark:border-slate-700 overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                    <!-- Ticket Header with Event Image -->
                    <div class="relative h-40 bg-cover bg-center" style="background-image: url('{{ asset('storage/' . ($order->ticket->event->image_path ?? 'placeholder.jpg')) }}')">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <!-- Status Badge -->
                        <div class="absolute top-3 right-3">
                            @if ($order->status === 'paid')
                                <span class="px-3 py-1 bg-green-500 text-white text-xs font-bold rounded-full flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">check_circle</span>
                                    Confirmed
                                </span>
                            @elseif ($order->status === 'pending')
                                <span class="px-3 py-1 bg-yellow-500 text-white text-xs font-bold rounded-full flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">schedule</span>
                                    Pending
                                </span>
                            @else
                                <span class="px-3 py-1 bg-red-500 text-white text-xs font-bold rounded-full flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">cancel</span>
                                    Cancelled
                                </span>
                            @endif
                        </div>
                        <!-- Event Name Overlay -->
                        <div class="absolute bottom-0 left-0 right-0 p-4">
                            <h3 class="text-white font-bold text-lg line-clamp-2">{{ $order->ticket->event->event_name ?? 'Event' }}</h3>
                        </div>
                    </div>

                    <!-- Ticket Details -->
                    <div class="p-4 flex flex-col gap-4">
                        <!-- Ticket Type & Quantity -->
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-[#616f89] dark:text-slate-400 text-xs font-medium uppercase tracking-wide">Ticket Type</p>
                                <p class="text-[#111318] dark:text-white font-bold text-sm mt-1">{{ $order->ticket->ticket_name }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[#616f89] dark:text-slate-400 text-xs font-medium uppercase tracking-wide">Quantity</p>
                                <p class="text-[#111318] dark:text-white font-bold text-sm mt-1">{{ $order->quantity }} x Ticket</p>
                            </div>
                        </div>

                        <div class="border-t border-[#f0f2f4] dark:border-slate-700 pt-3"></div>

                        <!-- Event Date & Location -->
                        <div class="flex flex-col gap-2 text-sm">
                            <div class="flex items-center gap-2 text-[#616f89] dark:text-slate-400">
                                <span class="material-symbols-outlined text-[18px]">event</span>
                                <span>{{ $order->ticket->event->event_date ? \Carbon\Carbon::parse($order->ticket->event->event_date)->format('M d, Y') : 'N/A' }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-[#616f89] dark:text-slate-400">
                                <span class="material-symbols-outlined text-[18px]">location_on</span>
                                <span class="line-clamp-1">{{ $order->ticket->event->location ?? 'TBD' }}</span>
                            </div>
                        </div>

                        <div class="border-t border-[#f0f2f4] dark:border-slate-700 pt-3"></div>

                        <!-- Price & Order Date -->
                        <div class="flex justify-between">
                            <div>
                                <p class="text-[#616f89] dark:text-slate-400 text-xs font-medium uppercase">Total Price</p>
                                <p class="text-primary font-bold text-lg mt-1">RM {{ number_format($order->total_price, 2) }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[#616f89] dark:text-slate-400 text-xs font-medium uppercase">Order Date</p>
                                <p class="text-[#111318] dark:text-white font-bold text-sm mt-1">{{ $order->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <a href="{{ route('events.show', $order->ticket->event->event_id) }}" class="w-full mt-2 px-4 py-2 bg-primary/10 text-primary font-bold rounded-lg hover:bg-primary/20 transition text-center text-sm flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">visibility</span>
                            View Event
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Summary Section -->
        <div class="mt-8 bg-white dark:bg-surface-dark rounded-xl border border-[#dbdfe6] dark:border-slate-700 p-6">
            <h3 class="text-[#111318] dark:text-white text-lg font-bold mb-4">Ticket Summary</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="flex items-center gap-4">
                    <div class="bg-blue-100 dark:bg-blue-900/30 p-3 rounded-lg">
                        <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">confirmation_number</span>
                    </div>
                    <div>
                        <p class="text-[#616f89] dark:text-slate-400 text-sm">Total Tickets</p>
                        <p class="text-[#111318] dark:text-white text-2xl font-bold">{{ $ticketOrders->sum('quantity') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="bg-green-100 dark:bg-green-900/30 p-3 rounded-lg">
                        <span class="material-symbols-outlined text-green-600 dark:text-green-400">check_circle</span>
                    </div>
                    <div>
                        <p class="text-[#616f89] dark:text-slate-400 text-sm">Confirmed</p>
                        <p class="text-[#111318] dark:text-white text-2xl font-bold">{{ $ticketOrders->where('status', 'paid')->count() }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="bg-orange-100 dark:bg-orange-900/30 p-3 rounded-lg">
                        <span class="material-symbols-outlined text-orange-600 dark:text-orange-400">attach_money</span>
                    </div>
                    <div>
                        <p class="text-[#616f89] dark:text-slate-400 text-sm">Total Spent</p>
                        <p class="text-[#111318] dark:text-white text-2xl font-bold">RM {{ number_format($ticketOrders->sum('total_price'), 2) }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@include('foot')