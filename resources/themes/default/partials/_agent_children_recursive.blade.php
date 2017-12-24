<tr data-tt-id="{{ $agent->id }}"
    @if($agent->parent_id != null)
    data-tt-parent-id="{{ $agent->parent_id }}"
    @endif
    >
    <td>
        @if(isset($with_action) && !$with_action)
            @foreach(range(0, $level) as $i)
                <span style="width: 4px;">-</span>
            @endforeach
        @endif
        {{ $agent->name }}
    </td>
    <td>{{ $agent->agent_code }}</td>
    <td>{{ $agent->agentPositionName }}</td>
    <td class="text-right">{{ \App\Money::format('%(.2n', $agent->total_nominal) }}</td>
    <td class="text-right">{{ \App\Money::format('%(.2n', $agent->total_fyp) }}</td>
    @if(isset($with_action) && $with_action)
        <td><a class="btn btn-action btn-default btn-sm" href="{{ route('agents.show', ['id' => $agent->id]) }}"><i class="fa fa-eye"></i> View</a></td>
    @endif
</tr>
@foreach($agent->childrenRecursive as $a)
    @include('partials._agent_children_recursive', ['agent' => $a, 'with_action' => $with_action, 'level' => $level + 1])
@endforeach