@php
    $checkss = !empty($deck->checks) ? $deck->checks : (!empty($checks) ? $checks : null);
@endphp
@if (isset($checkss) && $checkss->count() > 0)
    @foreach ($checkss as $dot)
        <li class="country-sales-content list-group-item">
            <span class="mr-2">
                <i class="flag-icon flag-icon-us" title="us" id="us"></i>
            </span>
            <span class>{{ $loop->iteration }}.{{ $dot->name }}</span>
            <div class="float-right">
                <a href="javascript:;" id="editCheckbtn" data-dotId="dot_{{ $loop->iteration }}"><i class="fas fa-edit text-primary" style="font-size: 1rem"></i></a>
                <a href="{{ route('check.delete', ['id' => $dot->id]) }}" class="deleteCheckbtn" data-id="{{ $dot->id }}"
                    onclick="return confirm('Are you sure you want to delete this check?');" title="Delete"><i class="fas fa-trash-alt text-danger" style="font-size: 1rem"></i></a>
            </div>
        </li>
    @endforeach
@endif
