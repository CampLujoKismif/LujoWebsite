@props(['component', 'props' => []])

<div data-vue-component="{{ $component }}" data-props="{{ json_encode($props) }}">
    <!-- Vue component will be mounted here -->
</div>
