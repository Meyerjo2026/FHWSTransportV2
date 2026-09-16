<x-shell :user="$user" :active="'/staff'" :tabs="['/staff' => 'Approve Trips', '/staff/bulk' => 'Bulk Upload Trips', '/staff/students' => 'Bulk Upload Students']">
    <div class="card">
        <h2>Pending requests <span class="badge-count">{{ $pending->count() }}</span></h2>
        @if ($pending->isEmpty())
            <div class="empty">No pending requests.</div>
        @else
            <table>
                <thead>
                    <tr><th>Student</th><th>Contact</th><th>Date</th><th>Time</th><th>Site</th><th>Department</th><th></th></tr>
                </thead>
                <tbody>
                    @foreach ($pending as $r)
                        <tr>
                            <td>{{ $r->student_name }}</td>
                            <td class="muted">{{ $r->student_email }}<br>{{ $r->student_number }}</td>
                            <td>{{ \Carbon\Carbon::parse($r->date)->format('d M Y') }}</td>
                            <td>{{ $r->time }}</td>
                            <td>{{ $r->site }}</td>
                            <td class="muted">{{ $r->department }}</td>
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
        <h3>Recently actioned</h3>
        @if ($recent->isEmpty())
            <div class="empty">Nothing yet.</div>
        @else
            <table>
                <thead><tr><th>Student</th><th>Date</th><th>Site</th><th>Status</th></tr></thead>
                <tbody>
                    @foreach ($recent as $r)
                        <tr>
                            <td>{{ $r->student_name }}</td>
                            <td>{{ \Carbon\Carbon::parse($r->date)->format('d M Y') }}</td>
                            <td>{{ $r->site }}</td>
                            <td><span class="pill {{ $r->status }}">{{ $r->status }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</x-shell>
