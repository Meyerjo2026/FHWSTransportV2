<x-shell :user="$user" :active="'/student/mine'" :tabs="['/student' => 'New Request', '/student/mine' => 'My Requests']">
    <div class="card">
        <h2>My requests <span class="badge-count">{{ $list->count() }}</span></h2>
        @if ($list->isEmpty())
            <div class="empty">No requests yet. Submit one from "New Request".</div>
        @else
            <table>
                <thead>
                    <tr><th>Date</th><th>Time</th><th>Site</th><th>Department</th><th>Status</th><th>Notes</th></tr>
                </thead>
                <tbody>
                    @foreach ($list as $r)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($r->date)->format('d M Y') }}</td>
                            <td>{{ $r->time }}</td>
                            <td>{{ $r->site }}</td>
                            <td class="muted">{{ $r->department }}</td>
                            <td><span class="pill {{ $r->status }}">{{ $r->status }}</span></td>
                            <td class="muted">{{ $r->notes }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</x-shell>
