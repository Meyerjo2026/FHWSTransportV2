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
                    <label>Site</label>
                    <select name="site" required>
                        <option value="" disabled selected>Select a site</option>
                        @foreach ($sites as $site)
                            <option value="{{ $site }}">{{ $site }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Date</label>
                    <input name="date" type="date" required>
                </div>
            </div>
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
