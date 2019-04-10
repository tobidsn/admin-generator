<div class="form-group ">
    <label>
         {{ $setting->name }}
    </label>
    @if(!empty($setting->value))
        <img src="{{ imageview($setting->value) }}" style="display: block; margin: 5px 0 10px 0; width: 100px;">
    @endif
    <div class="input-group">
        <div class="input-group-btn">
            <a href="{{ url('/') }}/filemanager/dialog.php?akey={{ env('FILE_KEY') }}&type=1&field_id={{ $setting->key }}&relative_url=1" class="file-iframe-btn" data-fancybox-type="iframe">
                <button type="button" class="btn btn-block btn-default btn-flat">Browse</button>
            </a>
        </div>
        <input name="{{ $setting->key }}" id="{{ $setting->key }}" type="text" class="form-control" value="{{ !empty($setting->value) ? $setting->value : '' }}" placeholder="Image">
    </div>
    <p class="help-block"></p>
</div>