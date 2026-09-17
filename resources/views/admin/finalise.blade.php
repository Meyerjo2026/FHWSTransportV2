@php
$tabs = ['/admin/dashboard' => 'Dashboard', '/admin' => 'Consolidate Trips', '/admin/review' => 'Approve / Reject', '/admin/journeys' => 'AI Trip Planner', '/admin/finalise' => 'Finalise Trips', '/admin/quotes' => 'Create RFQ', '/admin/sites' => 'Clinical Sites', '/admin/map' => 'Map'];
@endphp
<x-shell :user="$user" :active="'/admin/finalise'" :tabs="$tabs">
    <div class="card">
        <h2>Finalise trips</h2>
        <p class="muted" style="font-size:13px;">Lock approved trip groups so they're ready for quoting. Finalised trips can no longer be rejected.</p>
        @if ($groups->isEmpty())
            <div class="empty">Nothing left to finalise.</div>
        @else
            <form method="POST" action="/admin/finalise">
                @csrf
                <table>
                    <thead><tr><th></th><th>Date</th><th>Site</th><th>Time</th><th>Trip</th><th>Count</th></tr></thead>
                    <tbody>
                        @foreach ($groups as $g)
                            <tr>
                                <td><input type="checkbox" name="ids[]" value="{{ $g['items']->pluck('id')->implode(',') }}" checked></td>
                                <td>{{ \Carbon\Carbon::parse($g['date'])->format('d M Y') }}</td>
                                <td>{{ $g['site'] }}</td>
                                <td>{{ $g['time'] }}</td>
                                <td>{{ $g['tripParts'] > 1 ? "Trip {$g['tripPart']} of {$g['tripParts']}" : '—' }}</td>
                                <td>{{ $g['items']->count() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <button class="btn" type="submit" style="margin-top:10px;">Finalise selected</button>
            </form>
        @endif
    </div>
</x-shell>
