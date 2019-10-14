var sandCarousel = new SandCarousel(
    sliderOptions.carouselId,
    sliderOptions.carouselSlides,
    sliderOptions.slideDuration,
    sliderOptions.transitionDuration,
    parseInt(sliderOptions.resizable),
    parseInt(sliderOptions.autoPlay)
);

if (parseInt(sliderOptions.carouselControls) == true) {
    sandCarousel.arrowControls();
}
else {
    sandCarousel.dotControls();
};