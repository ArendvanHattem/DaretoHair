@props(['active' => false])

<a class="nav-link {{ $active ? 'active' : '' }}" aria-current="{{ request()->is('/') ? 'page' : 'false' }}" {{$attributes}}>{{$slot}}</a>