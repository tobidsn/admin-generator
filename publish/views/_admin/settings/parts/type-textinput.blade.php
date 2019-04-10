<div class="form-group">
    <label for="{{ $setting->key }}">{{ $setting->name }}</label>
    <input type="text" name="{{ $setting->key }}" value="{{ $setting->value }}" class="form-control" id="{{ $setting->key }}" placeholder="{{ $setting->placeholder }}">
    @if (!empty($setting->help))
    <small class="form-text text-muted">{{ $setting->help }}</small>
    @endif
</div>