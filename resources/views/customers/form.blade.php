<div class="form-group mb-3">
    <label class="form-label">First Name</label>
    <input type="text" name="first_name" class="form-control"
        value="{{ old('first_name', $customer->first_name ?? '') }}" required>
</div>

<div class="form-group mb-3">
    <label class="form-label">Last Name</label>
    <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $customer->last_name ?? '') }}"
        required>
</div>

<div class="form-group mb-3">
    <label class="form-label">Company Name</label>
    <input type="text" name="company_name" class="form-control"
        value="{{ old('company_name', $customer->company_name ?? '') }}">
</div>

<div class="form-group mb-3">
    <label class="form-label">Company Address</label>
    <textarea name="company_address"
        class="form-control">{{ old('company_address', $customer->company_address ?? '') }}</textarea>
</div>

<div class="form-group mb-3">
    <label class="form-label">Email</label>
    <input type="email" name="email" class="form-control" value="{{ old('email', $customer->email ?? '') }}" required>
</div>

<div class="form-group mb-3">
    <label class="form-label">Phone</label>
    <input type="text" name="phone" class="form-control" value="{{ old('phone', $customer->phone ?? '') }}">
</div>