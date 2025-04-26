@extends('layouts.app')
@section('content')
<div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-4">Start Simple Details #{{ $solution->id }}</h1>

        <h2 class="text-xl font-semibold mb-2">🔄 Last Modified:</h2>
        <table class="table-auto w-full mb-6 border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2">Field</th>
                    <th class="px-4 py-2">New Value</th>
                    <th class="px-4 py-2">Old Value</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($solution->getAttributes() as $key => $newValue)
                    @php
                        // استخدم الدوال الجديدة من الموديل
                        $displayNew = $solution->formatForDisplay($newValue);
                        $displayOld = $solution->formatForDisplay($oldData[$key] ?? null);
                        $isDifferent = $solution->isDifferent($displayNew, $displayOld);
                    @endphp
        
                    <tr class="transition-colors @if($isDifferent) bg-yellow-50 hover:bg-yellow-100 @else hover:bg-gray-50 @endif cursor-pointer"
                        onclick="showModal('{{ addslashes($key) }}', `{!! addslashes($displayNew) !!}`, `{!! addslashes($displayOld) !!}`)">
                        <td class="border px-4 py-2 font-medium break-words max-w-xs">{{ $key }}</td>
                        <td class="border px-4 py-2 break-words max-w-xs" 
                            style="{{ $isDifferent ? 'background-color:#d1fae5; color:#065f46; font-weight:bold; padding:8px; border-radius:6px;' : '' }}">
                            {!! $displayNew !!}
                        </td>
                        <td class="border px-4 py-2 break-words max-w-xs" 
                            style="{{ $isDifferent ? 'background-color:#fee2e2; color:#991b1b; font-weight:bold; padding:8px; border-radius:6px;' : '' }}">
                            {!! $displayOld !!}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h2 class="text-xl font-semibold mb-4">🕘 Change Log:</h2>
<div class="space-y-4">
    @foreach ($auditLogs as $index => $log)
    <div class="border rounded-lg">
        <a href="#log-{{ $index}}" 
           class="accordion-link block p-4 bg-gray-50 hover:bg-gray-100 transition-colors cursor-pointer" 
           onclick="toggleLog(event, '{{ $index }}')">
            <div class="flex justify-between items-center">
                <span>
                    Edited on {{ $log->created_at->format('Y-m-d H:i') }} 
                    by {{ optional($log->user)->name }}
                </span>
                <span class="transform transition-transform duration-200" id="arrow-{{ $index }}">▼</span>
            </div>
        </a>
        
        <div id="log-{{ $index }}" class="hidden log-details">
            <div class="p-4">
                <table class="table-auto w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2">Field</th>
                            <th class="px-4 py-2">New Value</th>
                            <th class="px-4 py-2">Old Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($log->new_data as $key => $newVal)
                            @php
                                $displayNew = $solution->formatForDisplay($newVal);
                                $displayOld = $solution->formatForDisplay($log->old_data[$key] ?? null);
                                $isDifferent = $solution->isDifferent($displayNew, $displayOld);
                            @endphp
                            
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="border px-4 py-2">{{ $key }}</td>
                                <td class="border px-4 py-2 break-words max-w-xs" 
                                    style="{{ $isDifferent ? 'background-color:#d1fae5; color:#065f46; font-weight:bold; padding:8px; border-radius:6px;' : '' }}">
                                    {!! $displayNew !!}
                                </td>
                                <td class="border px-4 py-2 break-words max-w-xs" 
                                    style="{{ $isDifferent ? 'background-color:#fee2e2; color:#991b1b; font-weight:bold; padding:8px; border-radius:6px;' : '' }}">
                                    {!! $displayOld !!}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endforeach
</div>
    </div>
</div>

<script>
function toggleLog(event, index) {
    event.preventDefault();
    const details = document.getElementById(`log-${index}`);
    const arrow = document.getElementById(`arrow-${index}`);
    
    document.querySelectorAll('.log-details').forEach(item => {
        if (item.id !== `log-${index}`) {
            item.classList.add('hidden');
            const otherArrow = item.previousElementSibling.querySelector(`#arrow-${item.id.split('-')[1]}`);
            if (otherArrow) otherArrow.innerHTML = '▼';
        }
    });
    
    details.classList.toggle('hidden');
    arrow.innerHTML = details.classList.contains('hidden') ? '▼' : '▲';
}
</script>
@endsection