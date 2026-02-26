<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div class="min-w-0">
                <h2 class="truncate font-semibold text-xl text-gray-800 leading-tight">
                    Ticket #{{ $ticket->id }}
                </h2>
                <p class="text-sm text-gray-500">
                    {{ $ticket->created_at->format('Y-m-d H:i') }}
                    @if($ticket->answered_at)
                        · обработано: {{ $ticket->answered_at->format('Y-m-d H:i') }}
                    @endif
                </p>
            </div>

            <a href="{{ route('manager.tickets.index') }}"
               class="inline-flex items-center gap-2 rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                ← Назад
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-900">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-900">
                    <div class="font-semibold mb-2">Проверьте форму:</div>
                    <ul class="list-disc pl-5 space-y-1 text-sm">
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @php
                $statusCode  = $ticket->status->value ?? (string) $ticket->status;
                $statusLabel = $statuses[$statusCode] ?? $statusCode;

                $badge = match ($statusCode) {
                    'new' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                    'in_progress' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                    'done' => 'bg-green-50 text-green-700 ring-green-600/20',
                    default => 'bg-gray-50 text-gray-700 ring-gray-600/20',
                };
            @endphp

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                {{-- Left --}}
                <div class="lg:col-span-8 space-y-6 min-w-0">
                    <div class="bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <h3 class="text-lg font-semibold text-gray-900 break-words">
                                        {{ $ticket->subject }}
                                    </h3>
                                    <div class="mt-2">
                                        <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $badge }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </div>
                                </div>

                                <div class="text-sm text-gray-500">
                                    ID: <span class="font-medium text-gray-700">{{ $ticket->id }}</span>
                                </div>
                            </div>

                            <div class="mt-5 text-gray-800 whitespace-pre-wrap break-words leading-relaxed">
                                {{ $ticket->message }}
                            </div>
                        </div>
                    </div>

                    <div class="bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center justify-between gap-4">
                                <h3 class="text-base font-semibold text-gray-900">Вложения</h3>
                                <span class="text-sm text-gray-500">{{ $attachments->count() }} шт.</span>
                            </div>

                            @if($attachments->isEmpty())
                                <div class="mt-4 text-sm text-gray-500">Нет вложений</div>
                            @else
                                <div class="mt-4 space-y-3">
                                    @foreach($attachments as $m)
                                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 rounded-lg border border-gray-200 p-4">
                                            <div class="min-w-0">
                                                <div class="font-medium text-gray-900 truncate">
                                                    {{ $m['name'] }}
                                                </div>

                                                <div class="text-xs text-gray-500 mt-1">
                                                    {{ $m['mime_type'] ?? 'file' }}
                                                    <span class="mx-1">·</span>

                                                    @if(!empty($m['size']))
                                                        {{ $m['size'] }}
                                                    @else
                                                        {{ number_format(((int)($m['size_bytes'] ?? 0))/1024, 0) }} KB
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="flex gap-2 shrink-0">
                                                <a href="{{ $m['url'] }}"
                                                   target="_blank"
                                                   rel="noopener"
                                                   class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                                                    Открыть
                                                </a>

                                                <a href="{{ $m['url'] }}"
                                                   download
                                                   class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                                                    Скачать
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Right --}}
                <div class="lg:col-span-4 space-y-6">
                    <div class="bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-base font-semibold text-gray-900">Клиент</h3>

                            <dl class="mt-4 space-y-3 text-sm">
                                <div>
                                    <dt class="text-gray-500">Имя</dt>
                                    <dd class="font-medium text-gray-900 break-words">{{ $ticket->customer?->name ?? '—' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-gray-500">Телефон</dt>
                                    <dd class="text-gray-700 break-words">{{ $ticket->customer?->phone ?? '—' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-gray-500">Email</dt>
                                    <dd class="text-gray-700 break-words">{{ $ticket->customer?->email ?? '—' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <div class="bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-base font-semibold text-gray-900">Статус</h3>

                            <div class="mt-3 text-sm text-gray-600">
                                Текущий:
                                <span class="font-medium text-gray-900">{{ $statuses[$statusCode] ?? $statusCode }}</span>
                            </div>

                            <form method="POST" action="{{ route('manager.tickets.updateStatus', $ticket) }}" class="mt-4 space-y-3">
                                @csrf
                                @method('PATCH')

                                <select name="status"
                                        class="w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    @foreach($statuses as $code => $label)
                                        <option value="{{ $code }}" @selected(($ticket->status->value ?? (string)$ticket->status) === $code)>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>

                                <button type="submit"
                                        class="w-full inline-flex items-center justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                                    Обновить
                                </button>
                            </form>

                            <p class="mt-4 text-xs text-gray-500">
                                При переводе в “Обработана” будет установлено <span class="font-medium">answered_at</span>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
