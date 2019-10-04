window.addEventListener("load", () => {
	const { slideDuration, sliderControls } = sliderOptions;
	const SLIDER			= document.querySelector(".sand-slider");
	const SLIDES			= document.querySelectorAll(".slide");

	// Sets the background color of the slides to the body background color:
	SLIDER.style.backgroundColor = getComputedStyle(document.querySelector("body")).backgroundColor;

	if (SLIDES.length > 1) {
		const Sand_Carousel_WP = new SandSlider(SLIDER, SLIDES, slideDuration, 350);

		// It's important to not use == operator because
		// the variable is string ("0"/"1") rather than bool.
		if (sliderControls == true) {
			Sand_Carousel_WP.arrowControls();
		}
		else {
			Sand_Carousel_WP.dotControls();
		};
	}
	else {
		SLIDES[0].style.opacity = 1;
	}
});