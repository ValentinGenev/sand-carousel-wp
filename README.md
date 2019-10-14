# Sand Carousel WP
This plugin creates custom post type named Slide that's displayed in the [Sand Carousel](https://github.com/ValentinGenev/sand-carousel).
**WARNING** the project is still in development.

## About
The carousels created with this plugin can be placed everywhere with the use of the plugin's shortcode:

```html
[sand_carousel group='slides-group-slug']
```

The slides of the carousel are stored in the custom post type **Slide**. The plugin also creates a custom taxonomy called **Slides Groups** to handle packaging the slides into carousels. 

## Shortcode options
- `group` controls which group of slides should be shown in the carousel; if no group was specified, the carousel will show all slides.
- `duration` sets the duration of the slides in ms. The default is `5000`.
- `transition` sets the duration of the transition animation in ms. The default is `500`.
- `resizable` controls whether or not the carousel should be resizable; if resizable is set to `1` the `autoplay` will be set to `0` automatically. The default is `0`.
- `autoplay` controls whether or not the carousel should autoplay. The default is `1`.
- `arrows` determines the controls of the carousel. The default is `1` for arrow controls; `0` is for dots.
- `id` adds id to the carousel's containing element.
- `class` adds class to the carousel's containing element.