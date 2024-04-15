@php
    $checks = !empty($deck->checks) ? $deck->checks : (!empty($checks) ? $checks : null);
@endphp
@if (isset($checks) && $checks->count() > 0)
    @foreach ($checks as $dot)
        <div class="dot ui-draggable ui-draggable-handle" data-checkId="{{ $dot->id }}"
            data-check="{{ $dot }}"
            style="top: {{ $dot->position_top - ($dot->isApp == 1 ? 20 : 0) }}px; left: {{ $dot->position_left - ($dot->isApp == 1 ? 20 : 0) }}px;"
            id="dot_{{ $loop->iteration }}">
            {{ $loop->iteration }}
        </div>
    @endforeach
@endif
