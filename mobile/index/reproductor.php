<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Administracion de Ligas de Padel miligadepadel.manuelbdv.es</title>
<script type="text/javascript" src="../jR3DCarousel/dist/jR3DCarousel.min.js"></script>
<script language="javascript">
	var slides = [{src: '../jRCarousel/images/1.jpg'}, {src: '../jRCarousel/images/2.jpg'}, {src: '../jRCarousel/images/3.jpg'}, {src: '../jRCarousel/images/4.jpg'},{src: '../jRCarousel/images/1.jpg'}, {src: '../jRCarousel/images/2.jpg'}, {src: '../jRCarousel/images/3.jpg'}, {src: '../jRCarousel/images/4.jpg'}]
	var jR3DCarousel;
	var carouselProps =  {
			 		  width: screen.width-(screen.width/2), 				
					  height: screen.height-(screen.height/1.8), 	
					  slideLayout : 'fill',     /* "contain" (fit according to aspect ratio), "fill" (stretches object to fill) and "cover" (overflows box but maintains ratio) */
					  animation: 'fade', 		/* slide | scroll | fade | zoomInSlide | zoomInScroll */
					  animationCurve: 'ease',
					  animationDuration: 2000,
					  animationInterval: 12000,
					  //slideClass: 'jR3DCarouselCustomSlide',
					  autoplay: true,
					  onSlideShow: show,		/* callback when Slide show event occurs */
					  navigation: 'circles',	/* circles | squares */
					  slides: slides 			/* array of images source or gets slides by 'slide' class */
						  
				}
				
	function setUp(){
 		jR3DCarousel = $('.jR3DCarouselGallery').jR3DCarousel(carouselProps);

		$('.settings').html('<pre>$(".jR3DCarouselGallery").jR3DCarousel('+JSON.stringify(carouselProps, null, 4)+')</pre>');		
		
	}
	function show(slide){
		console.log("Slide shown: ", slide.find('img').attr('src'))
	}
	$('.carousel-props input').change(function(){
		if(isNaN(this.value))
			carouselProps[this.name] = this.value || null; 
		else
			carouselProps[this.name] = Number(this.value) || null; 
		
		for(var i = 0; i < 999; i++)
	     clearInterval(i);
		$('.jR3DCarouselGallery').empty();
		setUp();
		jR3DCarousel.showNextSlide();
	})
	
	$('[name=slides]').change(function(){
		carouselProps[this.name] = getSlides(this.value); 
		for (var i = 0; i < 999; i++)
	     clearInterval(i);
		$('.jR3DCarouselGallery').empty();
		setUp();
		jR3DCarousel.showNextSlide();		
	});
	setUp();
</script>
<style>
.jR3DCarouselGallery,.jR3DCarouselGallery1 {
	margin: 0 auto;  /* optional - if want to center align */
}
</style>
</head>

<body>
<div class="jR3DCarouselGallery"></div>
</body>
</html>