<div class="form-fieldset">
    @foreach ($languages as $lang)
    <div class="form-group">
        <label for="{{ $setting->key }}">{{ $setting->name }} - {{ sprintf('[%s] %s', $lang->locale, $lang->name) }}</label>

        <input type="text"
            name="{{ sprintf('%s[%s]', $setting->key, $lang->locale) }}"
            value="{{ optional($setting->translation($lang))->content }}"
            class="form-control"
            id="{{ sprintf('%s-%s', $setting->key, $lang->locale) }}"
            placeholder="{{ sprintf('[%s] %s', $lang->locale, $lang->name) }} {{ $setting->placeholder }}">

        @if (!empty($setting->help))
        <small class="form-text text-muted">{{ $setting->help }}</small>
        @endif
    </div>
    @endforeach
</div>