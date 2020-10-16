const yellow = "#ffbe41";
const black = "#27282c";

$(".button--bubble").css("color", black);
$(".button").css("background-color", yellow);

$('.round').css('display', 'none');
// Specific media queries
if (window.innerHeight > window.innerWidth) {
	// iphone 5/SE -> portrait
	if (window.innerWidth <= 320) {
		$(".round").css("top", "9%");
		$(".wrapper__sites").css({
			fontSize: "8rem",
			marginBottom: "-14rem"
		});
	}
	// iphone 6/7/8 -> portrait
	if (window.innerWidth >= 321 && window.innerWidth <= 375) {
		$(".round").css("top", "10.5%");
		$(".wrapper__sites").css({
			fontSize: "8.5rem",
			marginBottom: "-13rem"
		});
	}
	// iphone 6/7/8 Plus -> portrait
	if (window.innerWidth >= 376 && window.innerWidth <= 414) {
		$(".round").css({
			top: "10.5%",
			marginLeft: "-13.5rem",
			width: "28rem",
			height: "28rem",
		});
		$(".wrapper__sites").css({
			fontSize: "10rem",
			marginBottom: "7rem"
		});
	}
	// iphone X -> portrait
	if (window.innerWidth === 375 && window.innerHeight === 812) {
		$(".round").css({
			top: "12.2%",
			marginLeft: "-13.8rem",
			width: "28rem",
			height: "28rem",
		});
		$(".wrapper__sites").css({
			fontSize: "9.5rem"
		});
	}
	// iphone XR -> portrait
	if (window.innerWidth === 414 && window.innerHeight === 896) {
		$(".round").css({
			top: "12.8%",
			marginLeft: "-13.8rem",
			width: "28rem",
			height: "28rem",
		});
		$(".wrapper__sites").css({
			fontSize: "9.5rem"
		});
	}
} else {
	// iphone 5/SE -> landscape
	if (window.innerWidth <= 568) {
		$(".round").css({
			top: "6.2%",
			marginLeft: "-12.8rem",
			width: "25rem",
			height: "25rem",
		});
		$(".wrapper__sites").css({
			fontSize: "9rem"
		});
	}
	// iphone 6/7/8 -> landscape
	if (window.innerWidth >= 569 && window.innerWidth <= 667) {
		$(".round").css({
			top: "7.3%",
			marginLeft: "-11.5rem",
			width: "23rem",
			height: "23rem",
		});
		$(".wrapper__sites").css({
			fontSize: "9rem"
		});
	}
	// iphone 6/7/8 Plus -> landscape
	if (window.innerWidth >= 668 && window.innerWidth <= 736) {
		$(".round").css({
			top: "7.5%",
			marginLeft: "-12.5rem",
			width: "25rem",
			height: "25rem",
		});
		$(".wrapper__sites").css({
			fontSize: "9rem"
		});
	}
	// iphone X -> landscape
	if (window.innerWidth >= 739 && window.innerWidth <= 812) {
		$(".round").css({
			top: "10%",
			marginLeft: "-12.5rem",
			width: "25rem",
			height: "25rem",
		});
		$(".wrapper__sites").css({
			fontSize: "9rem"
		});
	}
	// iphone XR -> landscape
	if (window.innerWidth >= 738 && window.innerWidth <= 896) {
		$(".round").css({
			top: "8%",
			marginLeft: "-12.5rem",
			width: "25rem",
			height: "25rem",
		});
		$(".wrapper__sites").css({
			fontSize: "9rem"
		});
	}
}