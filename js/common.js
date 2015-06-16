var isMobile = isMobile();

function isMobile() {
	ua = window.navigator.userAgent;
	isMobile = (/lgtelecom/i.test(ua) 
			|| /Android/i.test(ua) 
			|| /blackberry/i.test(ua) 
			|| /iPhone/i.test(ua) 
			|| /iPad/i.test(ua) 
			|| /samsung/i.test(ua) 
			|| /symbian/i.test(ua) 
			|| /sony/i.test(ua) 
			|| /SCH-/i.test(ua) 
			|| /SPH-/i.test(ua) 
			|| /nokia/i.test(ua) 
			|| /bada/i.test(ua) 
			|| /semc/i.test(ua) 
			|| /IEMobile/i.test(ua) 
			|| /Mobile/i.test(ua) 
			|| /PPC/i.test(ua) 
			|| /Windows CE/i.test(ua) 
			|| /Windows Phone/i.test(ua) 
			|| /webOS/i.test(ua) 
			|| /Opera Mini/i.test(ua) 
			|| /Opera Mobi/i.test(ua) 
			|| /POLARIS/i.test(ua) 
			|| /SonyEricsson/i.test(ua) 
			|| /symbos/i.test(ua));
	return isMobile;
}

Array.prototype.shuffle = function () {
	var i = this.length, j, temp;
	if (i <= 0) return;
	while (--i) {
		j = Math.floor( Math.random() * (i + 1) );
		temp = this[i];
		this[i] = this[j];
		this[j] = temp;
	}
};

// document.getElementById("id").remove();
Element.prototype.remove = function() {
	this.parentElement.removeChild(this);
}

// document.getElementsByClassName("class").remove();
NodeList.prototype.remove = HTMLCollection.prototype.remove = function() {
	for(var i = 0, len = this.length; i < len; i++) {
		if(this[i] && this[i].parentElement) {
			this[i].parentElement.removeChild(this[i]);
		}
	}
}

function trim(s) {
	if (typeof(s) == "undefined") return "";
	return s.replace(/^[\r\n\t ]+/, "").replace(/[\r\n\t ]+$/, "");
}

function trim_f(s) {
	if (typeof(s) == "undefined") return "";
	return s.replace(/^[\r\n\t ]+/, "");
}
