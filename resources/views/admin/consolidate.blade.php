@php
$tabs = ['/admin' => 'Consolidate Trips', '/admin/review' => 'Approve / Reject', '/admin/finalise' => 'Finalise Trips', '/admin/quotes' => 'Generate Quotes'];
@endphp
<x-shell :user="$user" :active="'/admin'" :tabs="$tabs">
    <div class="card">
        <h2>Consolidated trips <span class="badge-count">{{ $groups->count() }} groups</span></h2>
        <p class="muted" style="font-size:13px;">Approved requests grouped by date, site and time slot — mirrors the trip roster used for scheduling and invoicing.</p>
        @if ($groups->isEmpty())
            <div class="empty">No approved trips yet.</div>
        @else
            <table>
                <thead><tr><th>Date</th><th>Site</th><th>Time</th><th>Students</th><th>Count</th><th>Status</th></tr></thead>
                <tbody>
                    @foreach ($groups as $g)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($g['date'])->format('d M Y') }}</td>
                            <td>{{ $g['site'] }}</td>
                            <td>{{ $g['time'] }}</td>
                            <td class="muted">{{ $g['items']->pluck('student_name')->implode(', ') }}</td>
                            <td>{{ $g['items']->count() }}</td>
                            <td><span class="pill {{ $g['allFinal'] ? 'finalised' : 'approved' }}">{{ $g['allFinal'] ? 'finalised' : 'approved' }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</x-shell>
