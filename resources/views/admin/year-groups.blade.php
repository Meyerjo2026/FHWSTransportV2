@php
$tabs = ['/admin/dashboard' => 'Dashboard', '/admin' => 'Consolidate Trips', '/admin/review' => 'Approve / Reject', '/admin/journeys' => 'AI Trip Planner', '/admin/finalise' => 'Finalise Trips', '/admin/quotes' => 'Create RFQ', '/admin/sites' => 'Clinical Sites', '/admin/map' => 'Map', '/admin/year-groups' => 'Year Groups'];
@endphp
<x-shell :user="$user" :active="'/admin/year-groups'" :tabs="$tabs">
    <div class="card">
        <h2>Year group staff assignments</h2>
        <p class="hint">
            Each year group can have one staff member responsible for it. That staff member will only see trip requests from students in their assigned year group(s) when reviewing/approving. Admins always see every request regardless of year group.
        </p>
        <table>
            <thead><tr><th>Year group</th><th>Responsible staff member</th><th></th></tr></thead>
            <tbody>
                @foreach ($years as $year)
                    @php $assignment = $assignments[$year] ?? null; @endphp
                    <tr>
                        <td>{{ $year }}</td>
                        <td class="muted">{{ $assignment?->staff?->name ?? 'Unassigned' }}</td>
                        <td>
                            <form method="POST" action="/admin/year-groups/{{ rawurlencode($year) }}" style="display:flex;gap:8px;align-items:center;">
                                @csrf
                                <select name="staff_id">
                                    <option value="">— Unassigned —</option>
                                    @foreach ($staffMembers as $staff)
                                        <option value="{{ $staff->id }}" @selected($assignment?->staff_id === $staff->id)>{{ $staff->name }} ({{ $staff->email }})</option>
                                    @endforeach
                                </select>
                                <button class="btn small" type="submit">Save</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-shell>
