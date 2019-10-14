window.addEventListener("load", () => {
	const { slideDuration, transitionDuration, resizable, autoPlay, sliderControls } = sliderOptions;

	const SAND_CAROUSEL = new SandCarousel('.sand-carousel', '.slide', slideDuration, transitionDuration, resizable, autoPlay);

	// It's important to not use == operator because
	// the variable is string ("0"/"1") rather than bool.
	if (sliderControls == true) {
		SAND_CAROUSEL.arrowControls();
	}
	else {
		SAND_CAROUSEL.dotControls();
	};
});