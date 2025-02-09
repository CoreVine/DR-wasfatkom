<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       @foreach ($breadcrumb_items as $breadcrumb_item_key => $breadcrumb_item_val )
            @if ($breadcrumb_item_val == "active")
                <li class="breadcrumb-item active">{{ $breadcrumb_item_key }}</li>
            @else
                @if (is_null($breadcrumb_item_val))
                <li class="breadcrumb-item">
                    <a href="javascript:void(0);">{{ $breadcrumb_item_key }}</a>
                </li>
                @else
                <li class="breadcrumb-item">
                    <a href="{{ $breadcrumb_item_val }}">{{ $breadcrumb_item_key }}</a>
                </li>
                @endif
            @endif
       @endforeach



    </ol>
</nav>
