@php
$tabs = ['/admin/dashboard' => 'Dashboard', '/admin' => 'Consolidate Trips', '/admin/review' => 'Approve / Reject', '/admin/finalise' => 'Finalise Trips', '/admin/quotes' => 'Create RFQ', '/admin/sites' => 'Clinical Sites'];
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
        <table>
            <thead><tr><th>Site name</th><th>Address</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @foreach ($sites as $site)
                    <tr>
                        <td>{{ $site->name }}</td>
                        <td class="muted">{{ $site->address }}</td>
                        <td><span class="pill {{ $site->active ? 'approved' : 'rejected' }}">{{ $site->active ? 'active' : 'inactive' }}</span></td>
                        <td>
                            <form method="POST" action="/admin/sites/{{ $site->id }}/toggle">
                                @csrf
                                <button class="btn small secondary" type="submit">{{ $site->active ? 'Deactivate' : 'Activate' }}</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-shell>
