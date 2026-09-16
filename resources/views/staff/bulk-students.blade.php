<x-shell :user="$user" :active="'/staff/students'" :tabs="['/staff' => 'Approve Trips', '/staff/bulk' => 'Bulk Upload Trips', '/staff/students' => 'Bulk Upload Students']">
    <div class="card" style="max-width:720px;">
        <h2>Bulk upload students</h2>
        <p class="muted" style="font-size:13px;">Upload a CSV with columns: <code>name,email,number</code> (header row required). Each new student gets a temporary password they must change the first time they log in. Rows whose email already has an account are skipped.</p>
        @if ($errors->any())
            <div class="msg error">{{ $errors->first() }}</div>
        @endif
        <form method="POST" action="/staff/students" enctype="multipart/form-data">
            @csrf
            <div class="field">
                <input type="file" name="file" accept=".csv" required>
            </div>
            <button class="btn" type="submit">Upload &amp; create accounts</button>
        </form>
        <div class="hint">Example row: <code>Thandi Nkosi,thandi@mycput.ac.za,0821234567</code></div>
    </div>
    <div class="card">
        <h3>Download CSV template</h3>
        <a class="btn secondary" href="data:text/csv;charset=utf-8,name%2Cemail%2Cnumber%0AThandi%20Nkosi%2Cthandi%40mycput.ac.za%2C0821234567" download="students-template.csv">Download template.csv</a>
    </div>

    @isset($created)
        <div class="card">
            <h2>Accounts created <span class="badge-count">{{ count($created) }}</span></h2>
            @if (count($skipped))
                <div class="msg error">Skipped (email already has an account): {{ implode(', ', $skipped) }}</div>
            @endif
            @if (empty($created))
                <div class="empty">No new accounts were created.</div>
            @else
                <div class="msg success">These temporary passwords are shown <strong>once</strong> — they are not stored anywhere in plain text. Distribute them to students now (download the CSV below if useful), then leave this page.</div>
                <table>
                    <thead><tr><th>Name</th><th>Email</th><th>Number</th><th>Temporary password</th></tr></thead>
                    <tbody>
                        @foreach ($created as $c)
                            <tr>
                                <td>{{ $c['name'] }}</td>
                                <td>{{ $c['email'] }}</td>
                                <td>{{ $c['number'] }}</td>
                                <td><code>{{ $c['password'] }}</code></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <a class="btn secondary" style="margin-top:10px;"
                   href="data:text/csv;charset=utf-8,{{ urlencode("name,email,number,temporary_password\n".collect($created)->map(fn ($c) => "{$c['name']},{$c['email']},{$c['number']},{$c['password']}")->implode("\n")) }}"
                   download="new-student-credentials.csv">Download credentials CSV</a>
            @endif
        </div>
    @endisset
</x-shell>
