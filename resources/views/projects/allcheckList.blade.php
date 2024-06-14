@if (isset($project->checks) && $project->checks->count() > 0)
@php $count = 1; @endphp
    @foreach ($project->checks as $check)
        @php $hazmatsCount = count($check->check_hazmats); @endphp
        @if ($hazmatsCount == 0)
            <tr id="checkListTr_{{ $check->id }}" @if($check->markAsChange == 1) style="background-color: #f7ff005e; color: #000000" @endif>
                <td>{{ $count }}</td>
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
                        <a href="javascript:;" class="modalAddCheckImage ml-2" data-id="{{ $check->id }}" data-projectId="{{ $check->project_id }}" title="Add New Check Image">
                            <i class="fas fa-plus-square text-primary" style="font-size: 1rem"></i>
                        </a>
                    </td>
                @endcan
            </tr>
            @php $count++; @endphp
        @endif
        @foreach ($check->check_hazmats as $index => $hazmat)
            <tr id="checkListTr_{{ $check->id }}" @if($check->markAsChange == 1) style="background-color: #f7ff005e; color: #000000" @endif>
            <td>{{ $count }}</td>
                <td>{{ $check->initialsChekId }}</td>
                <td>{{ $check->name }}</td>
                <td>{{ $check->type }}</td>
                <td>{{ $check->location }}   @if($check->sub_location)
        {{ ',' . $check->sub_location }}
    @endif</td>
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
                        <a href="javascript:;" class="modalAddCheckImage ml-2" data-id="{{ $check->id }}" data-projectId="{{ $check->project_id }}"
                            title="Add New Check Image">
                            <i class="fas fa-plus-square text-primary" style="font-size: 1rem"></i>
                        </a>
                    </td>
                @endcan
            </tr>
            @php $count++; @endphp
        @endforeach
    @endforeach
@endif
