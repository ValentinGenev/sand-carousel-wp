# sand-carousel-wp
This plugin creates custom post type named Slide that's displayed in the Sand Slider.
**WARNING** the project is still in development.

## About
The carousels created with this plugin can be placed everywhere with the use of the the plugin's shortcode:

```html
[sand_carousel group='slides-group-slug']
```

To create a carousel, one needs to create a post of the plugin's post type *Slides* and then assign it to a *Slides Group*. The newliy created Slides Group slug should be placed in the shorcode.

## To do
- Demo of the carousel;
- usage tutorial;
- review of the code;
- change the fontello font with a svg;
- minimize code;
- make the carousel pause on blur (tab change, window change).

## Known issues
- The carousel doesn't stop when the user changes tabs or the window and the CSS animation causes visual inconsistencies when the user comes back to the page.