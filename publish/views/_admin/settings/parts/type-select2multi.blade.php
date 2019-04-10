@php
$class = $setting->model;
$results = class_exists($class) ? $class::where('status', $class::STATUS_ACTIVE)->get() : [];
$selected = !empty($setting->value) ? unserialize($setting->value) : [];
@endphp

<div class="form-group">
    <label class="d-block" for="{{ $setting->key }}">{{ $setting->name }}</label>

    @if (class_exists($class))
    <select name="{{ $setting->key }}[]" class="form-control" id="{{ $setting->key }}" style="width: 100%;" multiple>
        @foreach($results as $model)
        <option value="{{ $model->id }}" @if(in_array($model->id, $selected) == true) selected @endif>{{ isset($model->title) ? $model->title : $model->name }}</option>
        @endforeach
    </select>
    @else
    <div class="badge badge-warning">Model not specified!</div>
    @endif

    @if (!empty($setting->help))
    <small class="form-text text-muted">{{ $setting->help }}</small>
    @endif
</div>

@push('bottom-script')
<script>
    $(document).ready(function() {
        $('#{{ $setting->key }}').select2({
            placeholder: '- Select -',
            width: 'resolve'
        });
    });
</script>
@endpush
