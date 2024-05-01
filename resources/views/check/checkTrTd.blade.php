<tr id="checkListTr_{{ $check->id }}">
    <td>{{ $check->initialsChekId ?? '' }}</td>
    <td>{{ $check->name ?? '' }}</td>
    <td>{{ $check->type ?? '' }}</td>
    <td>{{ $check->location ?? '' }}</td>
    <td>{{ $check->equipment ?? '' }} <br> {{ $check->component ?? '' }} </td>
    <td>
        @if (!empty($check->check_hazmats))
            @foreach ($check->check_hazmats as $hazmat)
                <div class="m-2"><a class="btn btn-rounded btn-dark text-white"
                        style="padding: .375rem .75rem; cursor: default;">{{ $hazmat->short_name }}</a>
                </div>
            @endforeach
        @endif
    </td>
    <td>
        @if (!empty($check->check_hazmats))
            @foreach ($check->check_hazmats as $hazmat)
                <div class="m-2"><a href="javascript:;" class="btn btn-rounded btn-dark text-white"
                        style="padding: .375rem .75rem; cursor: default;">{{ $hazmat->short_name }}</a>
                </div>
            @endforeach
        @endif
    </td>
    <td class="text-center">
        @if ($check)
            <a href="javascript:;" class="editCheckbtn" data-dotId="dot_{{ $loop->iteration ?? '' }}"
                data-id="{{ $check->id }}"><i class="fas fa-edit text-primary" style="font-size: 1rem"></i></a>
            <a href="javascript:;" class="modalCheckbtn ml-2" data-id="{{ $check->id }}"><i
                    class="fas fa-images text-primary" style="font-size: 1rem"></i></a>
        @endif
    </td>
</tr>
