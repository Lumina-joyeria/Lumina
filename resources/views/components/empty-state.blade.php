@props(['icon' => 'gem', 'title', 'description', 'action' => null, 'href' => null])

<div {{ $attributes->class(['empty-state']) }}>
    <span class="empty-state__icon"><x-icon :name="$icon" size="28" /></span>
    <h2>{{ $title }}</h2>
    <p>{{ $description }}</p>
    @if($action && $href)
        <a class="button button--primary" href="{{ $href }}"><x-icon name="plus" size="18" />{{ $action }}</a>
    @endif
</div>
