@php
$tabs = ['/admin' => 'Consolidate Trips', '/admin/review' => 'Approve / Reject', '/admin/finalise' => 'Finalise Trips', '/admin/quotes' => 'Generate Quotes'];
@endphp
<x-shell :user="$user" :active="'/admin/quotes'" :tabs="$tabs">
    <div class="card" id="quotePreview">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;">
            <div>
                <h2 style="margin:0;">Quote</h2>
                <div class="muted">HG Travelling Services &middot; Bellville Campus</div>
            </div>
            <div style="text-align:right;font-size:13px;">
                <div><strong>Ref:</strong> {{ $quote->ref }}</div>
                <div><strong>Date:</strong> {{ $quote->created_at->format('Y/m/d') }}</div>
                <div><strong>Period:</strong> {{ $quote->period }}</div>
            </div>
        </div>
        <table style="margin-top:16px;">
            <thead><tr><th>#</th><th>Description</th><th>Qty</th><th>Unit price</th><th>Extended price</th></tr></thead>
            <tbody>
                @foreach ($groups as $i => $g)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($g['date'])->format('d M Y') }} {{ $g['time'] }} - {{ $g['site'] }} and return - 7-Seater</td>
                        <td>{{ $g['items']->count() }},00 TRIP</td>
                        <td>R {{ number_format($quote->rate, 2) }}</td>
                        <td>R {{ number_format($quote->rate * $g['items']->count(), 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="invoice-total">Total (ZAR): R {{ number_format($quote->total, 2) }}</div>
        <div class="no-print" style="margin-top:14px;">
            <button class="btn secondary" onclick="window.print()">Print / Save PDF</button>
        </div>
    </div>
</x-shell>
