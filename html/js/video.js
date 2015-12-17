"use strict";

// Space bar to pause/resume video playback
document.addEventListener("DOMContentLoaded", function(event) {
	var video = document.getElementById('video');   
	document.onkeypress = function(e){
		if((e || window.event).keyCode === 32){
			video.paused ? video.play() : video.pause();
		}
	}
});