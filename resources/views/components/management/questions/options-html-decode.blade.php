<div>
    @foreach($options as $option)
        {!! $option !!} {{ !$loop->last ? "\n" : null }}
    @endforeach
</div>