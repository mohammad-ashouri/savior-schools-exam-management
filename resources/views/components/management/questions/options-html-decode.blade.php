<div style="display: flex; flex-wrap: wrap; gap: 20px; align-items: center;">
    @foreach($options as $option)
        <div style="display: flex; align-items: center; gap: 5px;">
            <span>{{ count($options)>1 ? "$loop->iteration- " : '' }}</span>
            <span>{!! strip_tags($option, '<sup><sub><img>') !!}</span>
        </div>
    @endforeach
</div>