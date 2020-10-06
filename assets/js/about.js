// Specific media queries
if (window.innerHeight > window.innerWidth) {
	// iphone 5/SE -> portrait
	if (window.innerWidth <= 320) {
		$(".round").css("top", "8%");
	}
	// iphone 6/7/8 -> portrait
	if (window.innerWidth >= 321 && window.innerWidth <= 375) {
		$(".round").css("top", "10.5%");
		$(".wrapper__sites").css({
			fontSize: "6.5rem"
		});
	}
	// iphone 6/7/8 Plus -> portrait
	if (window.innerWidth >= 376 && window.innerWidth <= 414) {
		$(".round").css({
			top: "13.5%",
			marginLeft: "-13.5rem",
			width: "28rem",
			height: "28rem",
		});
		$(".wrapper__sites").css({
			fontSize: "8rem",
			marginBottom: "7rem"
		});
	}
	// iphone X -> portrait
	if (window.innerWidth == 375 && window.innerHeight == 812) {
		$(".round").css({
			top: "12.2%",
			marginLeft: "-13.8rem",
			width: "28rem",
			height: "28rem",
		});
		$(".wrapper__sites").css({
			fontSize: "7.5rem"
		});
	}
	// iphone XR -> portrait
	if (window.innerWidth == 414 && window.innerHeight == 896) {
		$(".round").css({
			top: "15.8%",
			marginLeft: "-13.8rem",
			width: "28rem",
			height: "28rem",
		});
		$(".wrapper__sites").css({
			fontSize: "7.5rem"
		});
	}
} else {
	// iphone 5/SE -> landscape
	if (window.innerWidth <= 568) {
		$(".round").css({
			top: "9.5%",
			marginLeft: "-9.8rem",
			width: "20rem",
			height: "20rem",
		});
		$(".wrapper__sites").css({
			fontSize: "7rem"
		});
	}
	// iphone 6/7/8 -> landscape
	if (window.innerWidth >= 569 && window.innerWidth <= 667) {
		$(".round").css({
			top: "10%",
			marginLeft: "-11.5rem",
			width: "23rem",
			height: "23rem",
		});
		$(".wrapper__sites").css({
			fontSize: "7rem"
		});
	}
	// iphone 6/7/8 Plus -> landscape
	if (window.innerWidth >= 668 && window.innerWidth <= 736) {
		$(".round").css({
			top: "10.5%",
			marginLeft: "-12.5rem",
			width: "25rem",
			height: "25rem",
		});
		$(".wrapper__sites").css({
			fontSize: "7rem"
		});
	}
	// iphone X -> landscape
	if (window.innerWidth >= 737 && window.innerWidth <= 812) {
		$(".round").css({
			top: "10%",
			marginLeft: "-12.5rem",
			width: "25rem",
			height: "25rem",
		});
		$(".wrapper__sites").css({
			fontSize: "8rem"
		});
	}
	// iphone XR -> landscape
	if (window.innerWidth == 896 && window.innerHeight == 414) {
		$(".round").css({
			top: "10.5%",
			marginLeft: "-13.8rem",
			width: "28rem",
			height: "28rem",
		});
		$(".wrapper__sites").css({
			fontSize: "7.5rem"
		});
	}
}

// Toggle info-sub-circle class on /about-me page
jQuery(function ($) {
	"use strict";
	// Active class toggle methods
	var removeClasses = function removeClasses(nodes, value) {
		if (nodes)
			return nodes.forEach(function (node) {
				return node.classList.contains(value) && node.classList.remove(value);
			});
		else return false;
	};
	var addClass = function addClass(nodes, index, value) {
		return nodes ? nodes[index].classList.add(value) : 0;
	};
	var App = {

		initServicesCircle: function initServicesCircle() {
			// Info circle
			var circles = document.querySelectorAll(".info-sub-circle");
			var circleContents = document.querySelectorAll(".info-circle-content-item");
			var parent = document.querySelector(".info-circle");
			if (parent) {
				var spreadCircles = function spreadCircles() {
					// Spread the sub-circles around the circle
					parent = document
						.querySelector(".info-circle")
						.getBoundingClientRect();
					var centerX = 0;
					var centerY = 0;
					Array.from(circles)
						.reverse()
						.forEach(function (circle, index) {
							var angle = index * (360 / circles.length);
							var x =
								centerX +
								(parent.width / 2) * Math.cos((angle * Math.PI) / 180);
							var y =
								centerY +
								(parent.height / 2) * Math.sin((angle * Math.PI) / 180);
							circle.style.transform =
								"translate3d(" +
								parseFloat(x).toFixed(5) +
								"px," +
								parseFloat(y).toFixed(5) +
								"px,0)";
						});
				};
				spreadCircles();
				var resizeTimer = void 0;
				window.addEventListener("resize", function () {
					clearTimeout(resizeTimer);
					resizeTimer = setTimeout(function () {
						spreadCircles();
					}, 50);
				});
				circles.forEach(function (circle, index) {
					var circlesToggleFnc = function circlesToggleFnc() {
						var index = circle.dataset.circleIndex;
						if (!circle.classList.contains("active")) {
							removeClasses(circles, "active");
							removeClasses(circleContents, "active");
							addClass(circles, index, "active");
							addClass(circleContents, index, "active");
						}
					};
					circle.addEventListener("click", circlesToggleFnc, true);
					circle.addEventListener("mouseover", circlesToggleFnc, true);
				});
			}
		}
	};

	window.addEventListener("scroll", function () {
		// Set positions of .info-circle childrens
		if (window.scrollY >= 950) {
			App.initServicesCircle();
		} else {
			// Set translate3D states to 0 e.g middle of there parent = .info-circle	
			$(`.info-sub-circle.one, 
				.info-sub-circle.two, 
				.info-sub-circle.three, 
				.info-sub-circle.four, 
				.info-sub-circle.five, 
				.info-sub-circle.six`)
				.css({
					transform: "translate3d(0, 0, 0)"
				});
		}
	});
});