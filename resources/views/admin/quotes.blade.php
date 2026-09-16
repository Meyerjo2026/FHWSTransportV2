@php
$tabs = ['/admin' => 'Consolidate Trips', '/admin/review' => 'Approve / Reject', '/admin/finalise' => 'Finalise Trips', '/admin/quotes' => 'Generate Quotes'];
@endphp
<x-shell :user="$user" :active="'/admin/quotes'" :tabs="$tabs">
    <div class="card">
        <h2>Generate quote</h2>
        <p class="muted" style="font-size:13px;">Builds a quote from finalised trips not yet quoted, in the HG Travelling Services invoice format (per trip, "and return").</p>
        @if ($errors->any())
            <div class="msg error">{{ $errors->first() }}</div>
        @endif
        <form method="POST" action="/admin/quotes">
            @csrf
            <div class="grid">
                <div class="field">
                    <label>Quote / period label</label>
                    <input name="period" placeholder="e.g. May 2026" required>
                </div>
                <div class="field">
                    <label>Rate per trip (ZAR)</label>
                    <input name="rate" type="number" step="0.01" value="{{ $defaultRate }}" required>
                </div>
            </div>
            <button class="btn" type="submit" style="margin-top:10px;">Generate quote</button>
        </form>
        @if ($groups->isEmpty())
            <div class="empty" style="margin-top:10px;">No finalised trips awaiting a quote.</div>
        @else
            <table style="margin-top:14px;">
                <thead><tr><th>Date</th><th>Site</th><th>Time</th><th>Trips</th></tr></thead>
                <tbody>
                    @foreach ($groups as $g)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($g['date'])->format('d M Y') }}</td>
                            <td>{{ $g['site'] }}</td>
                            <td>{{ $g['time'] }}</td>
                            <td>{{ $g['items']->count() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
    <div class="card">
        <h3>Past quotes</h3>
        @if ($quotes->isEmpty())
            <div class="empty">None generated yet.</div>
        @else
            <table>
                <thead><tr><th>Ref</th><th>Period</th><th>Date generated</th><th>Total</th><th></th></tr></thead>
                <tbody>
                    @foreach ($quotes as $q)
                        <tr>
                            <td>{{ $q->ref }}</td>
                            <td>{{ $q->period }}</td>
                            <td>{{ $q->created_at->format('d M Y') }}</td>
                            <td>R {{ number_format($q->total, 2) }}</td>
                            <td><a class="btn small secondary" href="/admin/quotes/{{ $q->id }}">View</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</x-shell>
