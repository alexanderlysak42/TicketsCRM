<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tickets</h2>
                <p class="text-sm text-gray-500">Tickets management</p>
            </div>

            <div class="text-sm text-gray-600">
                Всего: <span class="font-semibold text-gray-900">{{ $tickets->total() }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-900">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Filters --}}
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="GET" action="{{ route('manager.tickets.index') }}" class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-4">
                            <div class="lg:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Дата от</label>
                                <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}"
                                       class="w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div class="lg:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Дата до</label>
                                <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}"
                                       class="w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div class="lg:col-span-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Статус</label>
                                <select name="status"
                                        class="w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Все</option>
                                    @foreach($statuses as $code => $label)
                                        <option value="{{ $code }}" @selected(($filters['status'] ?? '') === $code)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="lg:col-span-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="text" name="email" value="{{ $filters['email'] ?? '' }}" placeholder="user@example.com"
                                       class="w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div class="lg:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Телефон</label>
                                <input type="text" name="phone" value="{{ $filters['phone'] ?? '' }}" placeholder="+380..."
                                       class="w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-between">
                            @php
                                $hasFilters = collect($filters)->filter(fn ($v) => filled($v))->isNotEmpty();
                            @endphp

                            <div class="text-sm text-gray-500">
                                @if($hasFilters)
                                    Фильтры активны
                                @else
                                    Фильтры не выбраны
                                @endif
                            </div>

                            <div class="flex gap-3">
                                <a href="{{ route('manager.tickets.index') }}"
                                   class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                                    Сбросить
                                </a>

                                <button type="submit"
                                        class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                                    Применить
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Table --}}
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Дата</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Статус</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Тема</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Клиент</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
                        </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($tickets as $t)
                            @php
                                $statusCode = $t->status->value ?? (string) $t->status;
                                $statusLabel = $statuses[$statusCode] ?? $statusCode;

                                $badge = match ($statusCode) {
                                    'new' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                                    'in_progress' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                                    'done' => 'bg-green-50 text-green-700 ring-green-600/20',
                                    default => 'bg-gray-50 text-gray-700 ring-gray-600/20',
                                };
                            @endphp

                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $t->id }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    <div class="font-medium text-gray-900">{{ $t->created_at->format('Y-m-d') }}</div>
                                    <div class="text-xs text-gray-500">{{ $t->created_at->format('H:i') }}</div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $badge }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-sm">
                                    <div class="font-medium text-gray-900">
                                        {{ $t->subject }}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ \Illuminate\Support\Str::limit($t->message, 90) }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <div class="font-medium text-gray-900">
                                        {{ $t->customer?->name ?? '—' }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $t->customer?->phone ?? '' }}
                                        @if($t->customer?->email)
                                            <span class="mx-1">·</span>{{ $t->customer->email }}
                                        @endif
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    <a href="{{ route('manager.tickets.show', $t) }}"
                                       class="inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-900 font-medium">
                                        Открыть
                                        <span aria-hidden="true">→</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500">
                                    Ничего не найдено
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $tickets->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
