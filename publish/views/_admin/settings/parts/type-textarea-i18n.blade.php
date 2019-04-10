<div class="form-fieldset">
    @foreach ($languages as $lang)
    <div class="form-group">
        <label for="{{ $setting->key }}">{{ $setting->name }} - {{ sprintf('[%s] %s', $lang->locale, $lang->name) }}</label>

        <textarea name="{{ sprintf('%s[%s]', $setting->key, $lang->locale) }}"
            id="{{ sprintf('%s-%s', $setting->key, $lang->locale) }}"
            rows="3"
            class="form-control"
            placeholder="{{ sprintf('[%s] %s', $lang->locale, $lang->name) }} {{ $setting->placeholder }}">{{ optional($setting->translation($lang))->content }}</textarea>

        @if (!empty($setting->help))
        <small class="form-text text-muted">{{ $setting->help }}</small>
        @endif
    </div>
    @endforeach
</div>