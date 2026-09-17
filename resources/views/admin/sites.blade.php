@php
$tabs = ['/admin/dashboard' => 'Dashboard', '/admin' => 'Consolidate Trips', '/admin/review' => 'Approve / Reject', '/admin/journeys' => 'AI Trip Planner', '/admin/finalise' => 'Finalise Trips', '/admin/quotes' => 'Create RFQ', '/admin/sites' => 'Clinical Sites', '/admin/map' => 'Map'];
@endphp
<x-shell :user="$user" :active="'/admin/sites'" :tabs="$tabs">
    <div class="card" style="max-width:640px;">
        <h2>Add a clinical site</h2>
        @if ($errors->any())
            <div class="msg error">{{ $errors->first() }}</div>
        @endif
        <form method="POST" action="/admin/sites">
            @csrf
            <div class="field">
                <label>Site name</label>
                <input name="name" placeholder="e.g. Tygerberg" required>
            </div>
            <div class="field">
                <label>Address</label>
                <input name="address" placeholder="e.g. Francie van Zijl Dr, Parow, Cape Town">
            </div>
            <button class="btn" type="submit">Add site</button>
        </form>
    </div>
    <div class="card">
        <h2>Clinical sites <span class="badge-count">{{ $sites->count() }}</span></h2>
        <p class="hint">
            Coordinates come from OpenStreetMap geocoding, not manual entry — click "Verify" to open that exact pin in Google Maps and confirm it against street view / satellite imagery.
            <span style="color:var(--amber);">Shared estimate</span> means this site's coordinates were approximated at suburb level (no exact match found) and are shared with at least one other site — check these first.
        </p>
        <table>
            <thead><tr><th>Site name</th><th>Address</th><th>Coordinates</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @foreach ($sites as $site)
                    @php
                        $coordKey = $site->lat !== null ? round($site->lat, 5).','.round($site->lng, 5) : null;
                        $isSharedEstimate = $coordKey && $duplicateCoordKeys->contains($coordKey);
                    @endphp
                    <tr id="site-row-{{ $site->id }}">
                        <td>{{ $site->name }}</td>
                        <td class="muted">{{ $site->address }}</td>
                        <td class="muted">
                            @if ($site->lat !== null)
                                {{ $site->lat }}, {{ $site->lng }}
                                <br>
                                <a href="https://www.google.com/maps/search/?api=1&query={{ $site->lat }},{{ $site->lng }}" target="_blank" rel="noopener">Verify on Google Maps &rarr;</a>
                                @if ($isSharedEstimate)
                                    <br><span style="color:var(--amber);font-size:11px;">Shared estimate</span>
                                @endif
                            @else
                                &mdash;
                            @endif
                        </td>
                        <td><span class="pill {{ $site->active ? 'approved' : 'rejected' }}">{{ $site->active ? 'active' : 'inactive' }}</span></td>
                        <td style="white-space:nowrap;">
                            <button type="button" class="btn small secondary" onclick="document.getElementById('site-edit-{{ $site->id }}').style.display='table-row'; this.closest('tr').style.display='none';">Edit</button>
                            <form method="POST" action="/admin/sites/{{ $site->id }}/toggle" style="display:inline;">
                                @csrf
                                <button class="btn small secondary" type="submit">{{ $site->active ? 'Deactivate' : 'Activate' }}</button>
                            </form>
                        </td>
                    </tr>
                    <tr id="site-edit-{{ $site->id }}" style="display:none;">
                        <td colspan="5">
                            <form method="POST" action="/admin/sites/{{ $site->id }}" style="display:flex;gap:8px;align-items:flex-end;flex-wrap:wrap;">
                                @csrf
                                <div class="field" style="margin:0;">
                                    <label>Site name</label>
                                    <input name="name" value="{{ $site->name }}" required>
                                </div>
                                <div class="field" style="margin:0;">
                                    <label>Address</label>
                                    <input name="address" value="{{ $site->address }}">
                                </div>
                                <div class="field" style="margin:0;width:120px;">
                                    <label>Latitude</label>
                                    <input name="lat" value="{{ $site->lat }}">
                                </div>
                                <div class="field" style="margin:0;width:120px;">
                                    <label>Longitude</label>
                                    <input name="lng" value="{{ $site->lng }}">
                                </div>
                                <button class="btn small" type="submit">Save</button>
                                <button type="button" class="btn small secondary" onclick="document.getElementById('site-row-{{ $site->id }}').style.display='table-row'; this.closest('tr').style.display='none';">Cancel</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-shell>
