@if (isset($project->checks) && $project->checks->count() > 0)
    @foreach ($project->checks as $check)
        @php $hazmatsCount = count($check->check_hazmats); @endphp
        @if ($hazmatsCount == 0)
            <tr id="checkListTr_{{ $check->id }}">
                <td>{{ $check->initialsChekId }}</td>
                <td>{{ $check->name }}</td>
                <td>{{ $check->type }}</td>
                <td>{{ $check->location }}</td>
                <td>{{ $check->equipment }} <br> {{ $check->component }} </td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                @can('projects.edit')
                    <td class="text-center">
                        <a href="javascript:;" class="editCheckbtn" data-dotId="dot_{{ $loop->iteration }}"
                            data-id="{{ $check->id }}"><i class="fas fa-edit text-primary" style="font-size: 1rem"></i></a>
                        <a href="javascript:;" class="modalCheckbtn ml-2" data-id="{{ $check->id }}"><i
                                class="fas fa-images text-primary" style="font-size: 1rem"></i></a>
                    </td>
                @endcan
            </tr>
        @endif
        @foreach ($check->check_hazmats as $index => $hazmat)
            <tr id="checkListTr_{{ $check->id }}">
                <td>{{ $check->initialsChekId }}</td>
                <td>{{ $check->name }}</td>
                <td>{{ $check->type }}</td>
                <td>{{ $check->location }}</td>
                <td>{{ $check->equipment }} <br> {{ $check->component }} </td>

                <td>{{ $hazmat->hazmat->name }}</td>
                <td>{{ $hazmat->type }}</td>
                @can('projects.edit')
                    <td class="text-center">
                        <a href="javascript:;" class="editCheckbtn" data-dotId="dot_{{ $loop->iteration }}"
                            data-id="{{ $check->id }}"><i class="fas fa-edit text-primary"
                                style="font-size: 1rem"></i></a>
                        <a href="javascript:;" class="modalCheckbtn ml-2" data-id="{{ $check->id }}"><i
                                class="fas fa-images text-primary" style="font-size: 1rem"></i></a>
                    </td>
                @endcan
            </tr>
        @endforeach
    @endforeach
@endif
