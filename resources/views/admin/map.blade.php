@php
$tabs = ['/admin/dashboard' => 'Dashboard', '/admin' => 'Consolidate Trips', '/admin/review' => 'Approve / Reject', '/admin/journeys' => 'AI Trip Planner', '/admin/finalise' => 'Finalise Trips', '/admin/quotes' => 'Create RFQ', '/admin/sites' => 'Clinical Sites', '/admin/map' => 'Map'];
@endphp
<x-shell :user="$user" :active="'/admin/map'" :tabs="$tabs">
    <div class="card">
        <h2>Student placements</h2>
        <p class="muted" style="font-size:13px;">Approved and finalised trips, mapped by clinical site. Pickup point is always {{ $pickup['name'] }}. Trips already combined into a journey (via the <a href="/admin/journeys">AI Trip Planner</a>) are drawn as a single recommended travel route instead of separate pins.</p>
        <form method="GET" action="/admin/map">
            <div class="grid">
                <div class="field">
                    <label>Department</label>
                    <select name="department" onchange="this.form.submit()">
                        <option value="">All departments</option>
                        @foreach ($departments as $dept)
                            <option value="{{ $dept }}" {{ $filters['department'] === $dept ? 'selected' : '' }}>{{ $dept }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Date</label>
                    <input type="date" name="date" value="{{ $filters['date'] }}" onchange="this.form.submit()">
                </div>
                <div class="field">
                    <label>Shift</label>
                    <select name="shift" onchange="this.form.submit()">
                        <option value="">Day &amp; night</option>
                        <option value="day" {{ $filters['shift'] === 'day' ? 'selected' : '' }}>Day</option>
                        <option value="night" {{ $filters['shift'] === 'night' ? 'selected' : '' }}>Night</option>
                    </select>
                </div>
                <div class="field">
                    <label>Show</label>
                    <select name="view" onchange="this.form.submit()">
                        <option value="all" {{ $filters['view'] === 'all' ? 'selected' : '' }}>Everything</option>
                        <option value="journeys" {{ $filters['view'] === 'journeys' ? 'selected' : '' }}>Combined journey routes only</option>
                        <option value="individual" {{ $filters['view'] === 'individual' ? 'selected' : '' }}>Individual trips only</option>
                    </select>
                </div>
            </div>
            @if ($filters['department'] || $filters['date'] || $filters['shift'] || $filters['view'] !== 'all')
                <a class="btn secondary small" href="/admin/map">Clear filters</a>
            @endif
        </form>
    </div>

    <div class="card">
        <div class="grid" style="margin-bottom:16px;">
            <div>
                <div class="muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.03em;">Placements shown</div>
                <div style="font-size:22px;font-weight:700;">{{ $totalPlacements }}</div>
            </div>
            <div>
                <div class="muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.03em;">Individual sites</div>
                <div style="font-size:22px;font-weight:700;">{{ $totalSites }}</div>
            </div>
            <div>
                <div class="muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.03em;">Combined routes</div>
                <div style="font-size:22px;font-weight:700;">{{ $totalJourneys }}</div>
            </div>
        </div>
        <div id="placement-map" style="height:460px;border-radius:10px;overflow:hidden;border:1px solid var(--border);"></div>
    </div>

    @if ($journeys->isNotEmpty())
        <div class="card">
            <h3>Recommended travel routes for combined journeys</h3>
            <p class="hint">Stop order is a nearest-neighbour route from {{ $pickup['name'] }} — not necessarily the mathematically shortest possible route, but a good fast approximation, and matches the route drawn on the map.</p>
            @foreach ($journeys as $j)
                <div style="border:1px solid var(--border);border-radius:10px;padding:14px;margin-bottom:12px;">
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:8px;">
                        <div>
                            <strong>{{ $j['label'] }}</strong>
                            <div class="muted" style="font-size:12px;margin-top:2px;">
                                {{ \Carbon\Carbon::parse($j['date'])->format('d M Y') }} &middot; {{ $j['time'] }} &middot; {{ $j['studentCount'] }} student{{ $j['studentCount'] === 1 ? '' : 's' }} &middot; ~{{ $j['totalKm'] }}km round trip
                            </div>
                        </div>
                        <span class="pill {{ $j['allFinalised'] ? 'finalised' : 'approved' }}">{{ $j['allFinalised'] ? 'finalised' : 'approved' }}</span>
                    </div>
                    <ol style="margin:10px 0 0;padding-left:20px;font-size:13px;">
                        <li class="muted">Depart {{ $pickup['name'] }}</li>
                        @foreach ($j['stops'] as $stop)
                            <li>{{ $stop['name'] }} <span class="muted">({{ $j['legsKm'][$stop['order'] - 1] }}km leg &middot; {{ $stop['count'] }} student{{ $stop['count'] === 1 ? '' : 's' }})</span></li>
                        @endforeach
                        <li class="muted">Return to {{ $pickup['name'] }} <span class="muted">({{ end($j['legsKm']) }}km leg)</span></li>
                    </ol>
                </div>
            @endforeach
        </div>
    @endif

    <div class="card">
        <h3>Individual sites in view</h3>
        @if ($markers->isEmpty())
            <div class="empty">No individual (not-yet-combined) placements match these filters.</div>
        @else
            <table>
                <thead><tr><th>Site</th><th>Address</th><th>Students</th><th>Department(s)</th></tr></thead>
                <tbody>
                    @foreach ($markers->sortByDesc('count') as $m)
                        <tr>
                            <td>{{ $m['name'] }}</td>
                            <td class="muted">{{ $m['address'] }}</td>
                            <td>{{ $m['count'] }}</td>
                            <td class="muted">{{ $m['departments']->implode(', ') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        const pickup = @json($pickup);
        const markers = @json($markers);
        const journeys = @json($journeys);
        const routeColors = ['#8e3a92', '#1c8a5c', '#c8503f', '#b6791a', '#2f6fed', '#0f766e'];

        const map = L.map('placement-map').setView([pickup.lat, pickup.lng], 10);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '&copy; OpenStreetMap contributors',
        }).addTo(map);

        const pickupIcon = L.divIcon({
            className: '',
            html: '<div style="background:#1c1622;color:#fff;border-radius:999px;padding:4px 10px;font-size:11px;font-weight:600;white-space:nowrap;box-shadow:0 1px 4px rgba(0,0,0,.3);">CPUT Bellville Campus</div>',
            iconSize: [0, 0],
        });
        L.marker([pickup.lat, pickup.lng], {icon: pickupIcon}).addTo(map);

        const bounds = [[pickup.lat, pickup.lng]];

        // Individual (not-yet-combined) placements: plain circle markers.
        markers.forEach(m => {
            const radius = Math.min(10 + m.count * 2, 34);
            const circle = L.circleMarker([m.lat, m.lng], {
                radius,
                color: '#6f2c73',
                weight: 2,
                fillColor: '#8e3a92',
                fillOpacity: 0.55,
            }).addTo(map);
            circle.bindPopup(
                '<strong>' + m.name + '</strong><br>' +
                (m.address ? m.address + '<br>' : '') +
                m.count + ' student' + (m.count === 1 ? '' : 's') +
                (m.departments.length ? '<br><span style="color:#6b6478;">' + m.departments.join(', ') + '</span>' : '') +
                '<br><a href="https://www.google.com/maps/search/?api=1&query=' + m.lat + ',' + m.lng + '" target="_blank" rel="noopener">Verify on Google Maps &rarr;</a>'
            );
            L.polyline([[pickup.lat, pickup.lng], [m.lat, m.lng]], {
                color: '#8e3a92', weight: 1, opacity: 0.35, dashArray: '4,5',
            }).addTo(map);
            bounds.push([m.lat, m.lng]);
        });

        // Combined journeys: a solid, numbered route from pickup through
        // each stop in recommended order, and back.
        journeys.forEach((journey, idx) => {
            const color = routeColors[idx % routeColors.length];
            const path = [[pickup.lat, pickup.lng], ...journey.stops.map(s => [s.lat, s.lng]), [pickup.lat, pickup.lng]];

            L.polyline(path, { color, weight: 3, opacity: 0.85 }).addTo(map);

            journey.stops.forEach(stop => {
                const icon = L.divIcon({
                    className: '',
                    html: '<div style="background:' + color + ';color:#fff;border-radius:999px;width:26px;height:26px;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;box-shadow:0 1px 4px rgba(0,0,0,.35);">' + stop.order + '</div>',
                    iconSize: [26, 26],
                    iconAnchor: [13, 13],
                });
                const marker = L.marker([stop.lat, stop.lng], { icon }).addTo(map);
                marker.bindPopup(
                    '<strong>Stop ' + stop.order + ': ' + stop.name + '</strong><br>' +
                    (stop.address ? stop.address + '<br>' : '') +
                    stop.count + ' student' + (stop.count === 1 ? '' : 's') + ' &middot; journey: ' + journey.label +
                    '<br><a href="https://www.google.com/maps/search/?api=1&query=' + stop.lat + ',' + stop.lng + '" target="_blank" rel="noopener">Verify on Google Maps &rarr;</a>'
                );
                bounds.push([stop.lat, stop.lng]);
            });
        });

        if (bounds.length > 1) {
            map.fitBounds(bounds, { padding: [30, 30] });
        }
    </script>
</x-shell>
