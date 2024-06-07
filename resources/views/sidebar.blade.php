
@extends('layouts.adminMaster')
@push('css')
    {{-- css links --}}
@endpush
@section('content')

<?php dump($menus) ?>
<ul class="sidebar-menu">
    @foreach($menus as $menu)
        <li>
            <a href="{{ $menu->uri }}">{{ $menu->title }}</a>
            @if($menu->children->isNotEmpty())
                <ul class="submenu">
                    @foreach($menu->children as $child)
                        <li>
                            <a href="{{ $child->uri }}">{{ $child->title }}</a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </li>
    @endforeach
</ul>
@endsection
@push('scripts')
    <script>
        // Your custom JavaScript code
    </script>
@endpush