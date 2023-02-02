<table>
    <thead>
        <tr>
            <th><strong>Date</strong></th>
            <th><strong>Hour</strong></th>
            <th><strong>Assignment</strong></th>
            <th><strong>Activity</strong></th>
            <th><strong>Time Spent</strong></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($activities as $activity)
            <tr>
                <td>{{ $activity->start->format('D, d M y') }}</td>
                <td>{{ $activity->start->format('H:i') }}</td>
                <td>
                    @if ($activity->project)
                    {{ $activity->project->name }}
                    @else
                    General
                    @endif
                </td>
                <td>{{ $activity->description }}</td>
                <td>
                    @if ($activity->duration)
                        @if ($activity->duration > 60)
                            {{ floor($activity->duration / 60) }} hours
                            @if ($activity->duration - floor($activity->duration / 60) * 60 > 0)
                                {{ $activity->duration - floor($activity->duration / 60) * 60 }} minutes
                            @endif
                        @else
                            {{ $activity->duration }} minutes
                        @endif
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
