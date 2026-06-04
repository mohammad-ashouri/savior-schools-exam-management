<div style="display: flex; flex-wrap: wrap; gap: 20px; align-items: center;">
    @php
        $letters = ['A', 'B', 'C', 'D'];
        $index = 0;
    @endphp
    @foreach($options as $option)
        <div style="display: flex; align-items: center; gap: 5px;">
            <span>{{ count($options)>1 ? $letters[$index++]."- " : '' }}</span>
            <span>{!! strip_tags($option, '<sup><sub><img>') !!}</span>
        </div>
    @endforeach
</div>