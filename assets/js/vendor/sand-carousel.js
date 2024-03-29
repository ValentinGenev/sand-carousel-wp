class SandCarousel {
    /** 
     * @param {string} carousel a string of the selector for the carousel
     * @param {string} slides a string of the selector for the slided
     * @param {number} slideDuration the time it takes for the slide change animation to finish (see the transitions in the CSS file)
     * @param {number} animationDuration
     * @param {boolean} autoplay
     * @param {boolean} resizable 
     */
    constructor(carousel, slides, slideDuration, animationDuration = 500, resizable = false, autoplay = true) {
        this.carousel           = document.querySelector(carousel);
        this.slides             = document.querySelectorAll(slides);
        this.slideClass         = this.slides[0].className;
        this.slideDuration      = slideDuration;
        this.animationDuration  = animationDuration;
        this.resizable          = resizable;
        this.autoplay           = resizable ? false : autoplay;
        this.currentSlide       = 0;
 
        // Dotted control items:
        this.controlDots        = [];
 
        // The timeout variable:
        this.theLoop            = undefined;

        // Page Visibility API:
        this.pageIsVisible      = true;
 
        // Binding this:
        this.initTheCarousel    = this.initTheCarousel.bind(this);
        this.changeSlide        = this.changeSlide.bind(this);
        this.startLoop          = this.startLoop.bind(this);
        this.setVisibilityAPI   = this.setVisibilityAPI.bind(this);
    }
 
    set slidesSetter(items) {
        this.slides = items;
    }
    set currentSlideSetter(index) {
        this.currentSlide = index;
    }
    set controlDotsSetter(items) {
        this.controlDots = items;
    }
    set theLoopSetter(loop) {
        this.theLoop = loop;
    }
    set pageIsVisibleSetter(bool) {
        this.pageIsVisible = bool;
    }
 
    /**
     * Initiates the carousel with dot controls and fade animation
     */
    dotControls() {
        const { carousel, slides, animationDuration, initTheCarousel, changeSlide, throttle } = this;
 
        if (slides.length > 1) {
            // The animation:
            carousel.classList.add("fading");
    
            // The controls
            let contorlsContainer       = document.createElement("ul");
            contorlsContainer.className = "controls";
    
            if (slides.length > 1) slides.forEach((_slide, key) => {
                let controlsItem = document.createElement("li");
                
                controlsItem.addEventListener("click", throttle(() => changeSlide(1, key), animationDuration)); 
                contorlsContainer.appendChild(controlsItem);
            });
            carousel.appendChild(contorlsContainer);
    
            this.controlDotsSetter = document.querySelectorAll(".controls li");

            initTheCarousel();
        }
        else {
            carousel.classList.add("one-slide");
        }
    }
 
    /**
     * Initiates the carousel with arrow controls and slide animation
     */
    arrowControls() {
        const { carousel, slides, animationDuration, initTheCarousel, changeSlide, createArrowIcon, throttle } = this;

        if (slides.length > 1) {
            // The animation:
            carousel.classList.add("sliding");
    
            // The controls:
            let previousSlideBtn = document.createElement("button");
            previousSlideBtn.className = "controls sand-controls previous-button";
            previousSlideBtn.addEventListener("click", throttle(() => changeSlide(-1), animationDuration));
            createArrowIcon(previousSlideBtn);

            let nextSlideBtn = document.createElement("button");
            nextSlideBtn.className = "controls sand-controls next-button";
            nextSlideBtn.addEventListener("click", throttle(() => changeSlide(1), animationDuration));
            createArrowIcon(nextSlideBtn);
    
            carousel.appendChild(previousSlideBtn);
            carousel.appendChild(nextSlideBtn);
    
            // Temporary fix for slides count equal to 2
            if (slides.length === 2) {
                let slidesDuplicates = [];
                // If the slides count is equal to 2 I need to copy
                // the two slides so I can use the "previous-slide",
                // "current-slide", "next-slide" structure
                slides.forEach(slide => {
                    let slideDuplicate = slide.cloneNode(true);
                    slide.parentNode.appendChild(slideDuplicate);
                });
    
                // Updates the slides' Node List variable with the newly added ones
                this.slidesSetter = slides[0].parentNode.querySelectorAll(".slide");
            }

            initTheCarousel();
        }
        else {
            carousel.classList.add("one-slide");
        }
    }

    /**
     * Initiates the carousel
     */
    initTheCarousel() {
        const { carousel, slides, slideDuration, animationDuration, autoplay, resizable, changeSlide, setVisibilityAPI } = this;
        
        // Sets the animation duration for the timer to the slide duration
        slides.forEach(slide => {
            slide.style.animationDuration   = slideDuration / 1000 + 's';
            slide.style.transitionDuration  = animationDuration / 1000 + 's';
            slide.style.transitionDelay     = animationDuration / 1000 + 's';
        });

        if (resizable == true) {
            carousel.classList.add("resizable");
            carousel.style.transitionDuration = animationDuration / 1000 + 's';
        }

        if (autoplay == true) {
            carousel.classList.add("autoplay");

            // This class addition ensured that the class will be removed right away
            // from the class toggle in startLoop() for the first few seconds (milis.)
            // of the first slide.
            carousel.classList.add("disabled");
        }

        // Activates the visibility API that detects if the user is on the page
        setVisibilityAPI();

        // Initial slide:
        changeSlide(1);
    }
 
    /**
     * Changes the slide
     * 
     * @param {number} direction -1 or 1 for "previois" and "next" slide
     * @param {number} index the index of the current slide
     */
    changeSlide(direction, index = this.currentSlide) {
        const { carousel, slides, slideClass, autoplay, resizable, controlDots, startLoop, checkIfFirstLast, resetClasses } = this;
        const slidesCount = slides.length;
 
        resetClasses(slides, slideClass);
     
        let currentSlide = checkIfFirstLast(index, slidesCount, direction);
        let previousSlide = checkIfFirstLast(currentSlide, slidesCount, -1);
        let nextSlide = checkIfFirstLast(currentSlide, slidesCount, 1);
        this.currentSlideSetter = currentSlide;
     
        slides[currentSlide].classList.add("current-slide");
        slides[previousSlide].classList.add("previous-slide");
        slides[nextSlide].classList.add("next-slide");
     
        if (direction === 1) {
            slides[previousSlide].classList.add("active");
 
            if (controlDots.length != 0) {
                resetClasses(controlDots, "");
                controlDots[previousSlide].classList.add("active");
            }
        }
        else {
            slides[nextSlide].classList.add("active");
        }

        // Resizes the carousel to the slide's content
        if (resizable == true) {
            if (controlDots.length != 0) {
                carousel.style.height = slides[previousSlide].offsetHeight + "px";
            }
            else {
                carousel.style.height = slides[currentSlide].offsetHeight + "px";
            }
        }

        // Resets the loop from the new current slide.
        // This call is part of the recursiuon
        if (autoplay == true) startLoop();
    }
 
    /**
     * Starts the loop
     */
    startLoop() {
        const { carousel, slideDuration, animationDuration, theLoop, pageIsVisible, changeSlide } = this;
        
        // Disables the carousel during the transition animation
        carousel.classList.toggle("disabled");
        setTimeout(() => carousel.classList.remove("disabled"), animationDuration);

        // Clears the timeout when the function is called
        // after user's interaction with the controls
        if (theLoop) clearTimeout(theLoop);
     
        // Loops from the last slide; creates recursion
        if (pageIsVisible) this.theLoopSetter = setTimeout(() => changeSlide(1) , slideDuration);
    }
 
    /**
     * Checks if the current slide is first or rast
     * 
     * @param {number} index current slide
     * @param {number} listCount the number of slides
     * @param {number} direction -1 or 1 for "previois" and "next" slide
     * 
     * @returns returns the index ot the next slide according to the direction
     */
    checkIfFirstLast(index, listCount, direction) {
        if (index === listCount - 1 && direction === 1) return 0;
        else if (index === 0 && direction === -1 ) return listCount - 1;
        else return index + direction;
    }

    /**
     * Resets the className to a given string
     * 
     * @param {NodeList} elements
     * @param {string} className 
     */
    resetClasses(elements, className) {
        elements.forEach(element => element.className = className);
    }

    /**
     * Creates a SVG element and appends it to an element
     * 
     * @param {Node} element
     */
    createArrowIcon(element) {
        let arrowIcon = document.createElementNS("http://www.w3.org/2000/svg", "svg");
        arrowIcon.setAttributeNS("http://www.w3.org/2000/xmlns/", "xmlns:xlink", "http://www.w3.org/1999/xlink");
        arrowIcon.setAttribute("x", "0px");
        arrowIcon.setAttribute("y", "0px");
        arrowIcon.setAttribute("viewBox", "0 0 32 55");
        arrowIcon.setAttribute("style", "enable-background: new 0 0 32 55;");

        let thePath = document.createElementNS("http://www.w3.org/2000/svg", 'path');
        thePath.setAttribute("d", "M2.985,0.579L0.581,2.973c-0.775,0.772-0.775,2.024,0,2.796L22.401,27.5L0.581,49.232c-0.775,0.772-0.775,2.024,0,2.796 l2.403,2.394c0.775,0.772,2.032,0.772,2.807,0l25.627-25.523c0.775-0.772,0.775-2.024,0-2.796L5.792,0.579 C5.017-0.193,3.76-0.193,2.985,0.579z");

        arrowIcon.appendChild(thePath);
        element.appendChild(arrowIcon);
    }
 
    /**
     * The throttle function
     * Many thanks to Jonathan Sampson
     * https://jsfiddle.net/jonathansampson/m7G64/
     * 
     * @param {function} callback the function that should be called with delay
     * @param {number} limit the delay
     */
    throttle(callback, limit) {
        var wait = false;
        return () => {
            if (!wait) {
                callback.call();
                wait = true;
                setTimeout(() => wait = false, limit);
            }
        }
    }

    /**
     * Sets the Page Visibilyt API that stops the
     * carousel when the tab is switched
     * https://developer.mozilla.org/en-US/docs/Web/API/Page_Visibility_API
     */
    setVisibilityAPI() {
        const { autoplay, startLoop } = this;
        let hidden = "hidden";

        const HANDLE_VISIBILITY_CHANGE = () => {
            if (document[hidden]) {
                this.pageIsVisibleSetter = false;
            }
            else {
                this.pageIsVisibleSetter = true;
                if (autoplay == true) startLoop();
            }
        }
        
        if (hidden in document) document.addEventListener("visibilitychange", HANDLE_VISIBILITY_CHANGE);
        else if ((hidden = "mozHidden") in document) document.addEventListener("mozvisibilitychange", HANDLE_VISIBILITY_CHANGE);
        else if ((hidden = "webkitHidden") in document) document.addEventListener("webkitvisibilitychange", HANDLE_VISIBILITY_CHANGE);
        else if ((hidden = "msHidden") in document) document.addEventListener("msvisibilitychange", HANDLE_VISIBILITY_CHANGE);
        // IE 9 and lower:
        else if ("onfocusin" in document) document.onfocusin = document.onfocusout = HANDLE_VISIBILITY_CHANGE;
        // All others:
        else window.onpageshow = window.onpagehide = window.onfocus = window.onblur = HANDLE_VISIBILITY_CHANGE;
    }
}