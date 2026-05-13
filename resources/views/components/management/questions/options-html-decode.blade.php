<div>
    @foreach($options as $option)
        <div>
            <span>{{ $loop->iteration }}-</span>
            <span>{!! strip_tags($option) !!}</span>
        </div>
    @endforeach
</div>