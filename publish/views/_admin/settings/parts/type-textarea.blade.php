<div class="form-group">
    <label for="{{ $setting->key }}">{{ $setting->name }}</label>
    <textarea name="{{ $setting->key }}" id="{{ $setting->key }}" rows="5" class="form-control" placeholder="{{ $setting->placeholder }}">{{ $setting->value }}</textarea>
    @if (!empty($setting->help))
    <small class="form-text text-muted">{{ $setting->help }}</small>
    @endif
</div>