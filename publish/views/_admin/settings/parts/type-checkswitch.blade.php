<div class="form-fieldset">
    <label class="custom-switch">
        <input type="hidden" name="{{ $setting->key }}" value="0">
        <input class="custom-switch-input" name="{{ $setting->key }}" value="1" type="checkbox" {{ $setting->value ? "checked" : null }}/>
        <span class="custom-switch-indicator"></span>
        <span class="custom-switch-description">{{ $setting->name }}</span>
    </label>
    @if (!empty($setting->help))
    <small class="form-text text-muted">{{ $setting->help }}</small>
    @endif
</div>