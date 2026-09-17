@php
$tabs = ['/admin/dashboard' => 'Dashboard', '/admin' => 'Consolidate Trips', '/admin/review' => 'Approve / Reject', '/admin/journeys' => 'AI Trip Planner', '/admin/finalise' => 'Finalise Trips', '/admin/quotes' => 'Create RFQ', '/admin/sites' => 'Clinical Sites', '/admin/map' => 'Map', '/admin/group-assignments' => 'Staff Assignments'];
@endphp
<x-shell :user="$user" :active="'/admin/review'" :tabs="$tabs">
    <div class="card">
        <h2>Pending requests <span class="badge-count">{{ $pending->count() }}</span></h2>
        @if ($pending->isEmpty())
            <div class="empty">Nothing pending.</div>
        @else
            <table>
                <thead><tr><th>Student</th><th>Date</th><th>Time</th><th>Site</th><th>Department</th><th>Year</th><th></th></tr></thead>
                <tbody>
                    @foreach ($pending as $r)
                        <tr>
                            <td>{{ $r->student_name }}</td>
                            <td>{{ \Carbon\Carbon::parse($r->date)->format('d M Y') }}</td>
                            <td>{{ $r->time }}</td>
                            <td>{{ $r->site }}</td>
                            <td class="muted">{{ $r->department }}</td>
                            <td class="muted">{{ $r->year }}</td>
                            <td class="row-actions">
                                <form method="POST" action="/requests/{{ $r->id }}/status">
                                    @csrf
                                    <input type="hidden" name="status" value="approved">
                                    <button class="btn small green" type="submit">Approve</button>
                                </form>
                                <form method="POST" action="/requests/{{ $r->id }}/status">
                                    @csrf
                                    <input type="hidden" name="status" value="rejected">
                                    <button class="btn small red" type="submit">Reject</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
    <div class="card">
        <h2>Approved (not yet finalised) <span class="badge-count">{{ $approved->count() }}</span></h2>
        @if ($approved->isEmpty())
            <div class="empty">None.</div>
        @else
            <table>
                <thead><tr><th>Student</th><th>Date</th><th>Time</th><th>Site</th><th>Department</th><th>Year</th><th></th></tr></thead>
                <tbody>
                    @foreach ($approved as $r)
                        <tr>
                            <td>{{ $r->student_name }}</td>
                            <td>{{ \Carbon\Carbon::parse($r->date)->format('d M Y') }}</td>
                            <td>{{ $r->time }}</td>
                            <td>{{ $r->site }}</td>
                            <td class="muted">{{ $r->department }}</td>
                            <td class="muted">{{ $r->year }}</td>
                            <td class="row-actions">
                                <form method="POST" action="/requests/{{ $r->id }}/status">
                                    @csrf
                                    <input type="hidden" name="status" value="rejected">
                                    <button class="btn small red" type="submit">Reject</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</x-shell>
