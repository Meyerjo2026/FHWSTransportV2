<x-shell :user="$user" :active="'/student'" :tabs="['/student' => 'New Request', '/student/mine' => 'My Requests']">
    <div class="card" style="max-width:560px;">
        <h2>Request transport</h2>
        @if ($errors->any())
            <div class="msg error">{{ $errors->first() }}</div>
        @endif
        <form method="POST" action="/student">
            @csrf
            <div class="grid">
                <div class="field">
                    <label>Name</label>
                    <input value="{{ $user->name }}" disabled>
                </div>
                <div class="field">
                    <label>Email</label>
                    <input value="{{ $user->email }}" disabled>
                </div>
            </div>
            <div class="field">
                <label>Student number</label>
                <input value="{{ $user->number }}" disabled>
            </div>
            <div class="grid">
                <div class="field">
                    <label>Clinical site</label>
                    <select name="clinical_site_id" id="clinical_site_id" required onchange="document.getElementById('site-address').textContent = this.options[this.selectedIndex].dataset.address || '';">
                        <option value="" disabled selected>Select a clinical site</option>
                        @foreach ($sites as $site)
                            <option value="{{ $site->id }}" data-address="{{ $site->address }}">{{ $site->name }}</option>
                        @endforeach
                    </select>
                    <div class="hint" id="site-address"></div>
                </div>
                <div class="field">
                    <label>Date</label>
                    <input name="date" type="date" required>
                </div>
            </div>
            <div class="grid">
                <div class="field">
                    <label>Department</label>
                    <select name="department" id="department" required onchange="populateQualifications()">
                        <option value="" disabled selected>Select a department</option>
                        @foreach ($departments as $dept)
                            <option value="{{ $dept }}">{{ $dept }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Qualification (studying)</label>
                    <select name="qualification" id="qualification" required disabled>
                        <option value="" disabled selected>Select a department first</option>
                    </select>
                </div>
            </div>
            <script>
                const qualificationsByDepartment = @json($qualificationsByDepartment);
                function populateQualifications() {
                    const deptSelect = document.getElementById('department');
                    const qualSelect = document.getElementById('qualification');
                    const quals = qualificationsByDepartment[deptSelect.value] || [];
                    qualSelect.innerHTML = '';
                    if (quals.length === 0) {
                        qualSelect.disabled = true;
                        qualSelect.appendChild(new Option('Select a department first', '', true, true));
                        return;
                    }
                    qualSelect.disabled = false;
                    qualSelect.appendChild(new Option('Select a qualification', '', true, true));
                    qualSelect.options[0].disabled = true;
                    quals.forEach(q => qualSelect.appendChild(new Option(q, q)));
                }
            </script>
            <div class="field">
                <label>Time slot</label>
                <select name="time_select" id="time_select" onchange="document.getElementById('custom-time-wrap').style.display = this.value === 'Custom' ? 'block' : 'none'; document.getElementById('time').value = this.value === 'Custom' ? '' : this.value;">
                    @foreach ($timeSlots as $slot)
                        <option value="{{ $slot }}">{{ $slot }}</option>
                    @endforeach
                    <option value="Custom">Custom</option>
                </select>
                <input type="hidden" name="time" id="time" value="{{ $timeSlots[0] }}">
            </div>
            <div class="field" id="custom-time-wrap" style="display:none;">
                <label>Custom time (e.g. 08:00 - 16:00)</label>
                <input placeholder="08:00 - 16:00" oninput="document.getElementById('time').value = this.value;">
            </div>
            <div class="field">
                <label>Notes (optional)</label>
                <textarea name="notes" rows="2" placeholder="e.g. group of 2, pickup point details"></textarea>
            </div>
            <button class="btn" type="submit">Submit request</button>
        </form>
    </div>
</x-shell>
