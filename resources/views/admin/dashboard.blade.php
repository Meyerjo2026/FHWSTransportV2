@php
$tabs = ['/admin/dashboard' => 'Dashboard', '/admin' => 'Consolidate Trips', '/admin/review' => 'Approve / Reject', '/admin/journeys' => 'AI Trip Planner', '/admin/finalise' => 'Finalise Trips', '/admin/quotes' => 'Create RFQ', '/admin/sites' => 'Clinical Sites', '/admin/map' => 'Map'];
@endphp
<x-shell :user="$user" :active="'/admin/dashboard'" :tabs="$tabs">
    <div class="card">
        <h2>Overview</h2>
        <div class="grid">
            <div class="field" style="margin:0;">
                <div class="muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.03em;">Total requests</div>
                <div style="font-size:24px;font-weight:700;">{{ $totalRequests }}</div>
            </div>
            @foreach ($statuses as $status)
                <div class="field" style="margin:0;">
                    <div class="muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.03em;">{{ ucfirst($status) }}</div>
                    <div style="font-size:24px;font-weight:700;">{{ $statusCounts->get($status, 0) }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="card">
        <h2>Department usage</h2>
        @if ($departmentStats->isEmpty())
            <div class="empty">No requests with a department recorded yet.</div>
        @else
            <table>
                <thead><tr><th>Department</th><th>Usage</th><th>Total</th><th>Pending</th><th>Approved</th><th>Rejected</th><th>Finalised</th></tr></thead>
                <tbody>
                    @foreach ($departmentStats as $row)
                        <tr>
                            <td>{{ $row['label'] }}</td>
                            <td style="min-width:160px;">
                                <div style="background:var(--border);border-radius:4px;overflow:hidden;height:8px;">
                                    <div style="background:var(--primary);width:{{ $row['pct'] }}%;height:8px;"></div>
                                </div>
                            </td>
                            <td>{{ $row['total'] }}</td>
                            <td class="muted">{{ $row['pending'] }}</td>
                            <td class="muted">{{ $row['approved'] }}</td>
                            <td class="muted">{{ $row['rejected'] }}</td>
                            <td class="muted">{{ $row['finalised'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="card">
        <h2>Qualification usage</h2>
        @if ($qualificationStats->isEmpty())
            <div class="empty">No requests with a qualification recorded yet.</div>
        @else
            <table>
                <thead><tr><th>Qualification</th><th>Usage</th><th>Total</th><th>Pending</th><th>Approved</th><th>Rejected</th><th>Finalised</th></tr></thead>
                <tbody>
                    @foreach ($qualificationStats as $row)
                        <tr>
                            <td>{{ $row['label'] }}</td>
                            <td style="min-width:160px;">
                                <div style="background:var(--border);border-radius:4px;overflow:hidden;height:8px;">
                                    <div style="background:var(--green);width:{{ $row['pct'] }}%;height:8px;"></div>
                                </div>
                            </td>
                            <td>{{ $row['total'] }}</td>
                            <td class="muted">{{ $row['pending'] }}</td>
                            <td class="muted">{{ $row['approved'] }}</td>
                            <td class="muted">{{ $row['rejected'] }}</td>
                            <td class="muted">{{ $row['finalised'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="card">
        <h2>Export trip requests</h2>
        <p class="muted" style="font-size:13px;">Download a CSV of trip requests for the statuses you select below.</p>
        <form method="GET" action="/admin/export">
            <div class="checkbox-row" style="margin-bottom:6px;">
                <input type="checkbox" name="status[]" value="approved" id="st-approved" checked>
                <label for="st-approved" style="margin:0;">Approved</label>
            </div>
            <div class="checkbox-row" style="margin-bottom:6px;">
                <input type="checkbox" name="status[]" value="rejected" id="st-rejected" checked>
                <label for="st-rejected" style="margin:0;">Rejected</label>
            </div>
            <div class="checkbox-row" style="margin-bottom:6px;">
                <input type="checkbox" name="status[]" value="pending" id="st-pending">
                <label for="st-pending" style="margin:0;">Pending</label>
            </div>
            <div class="checkbox-row" style="margin-bottom:12px;">
                <input type="checkbox" name="status[]" value="finalised" id="st-finalised">
                <label for="st-finalised" style="margin:0;">Finalised</label>
            </div>
            <button class="btn" type="submit">Export CSV</button>
        </form>
    </div>
</x-shell>
