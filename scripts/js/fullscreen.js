function enterFullscreen(element) {
  if(element.requestFullscreen) {
	element.requestFullscreen();
  } else if(element.mozRequestFullScreen) {
	element.mozRequestFullScreen();
  } else if(element.msRequestFullscreen) {
	element.msRequestFullscreen();
  } else if(element.webkitRequestFullscreen) {
	element.webkitRequestFullscreen();
  }
}

enterFullscreen(document.documentElement);
