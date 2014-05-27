// @link http://home.jejaju.com/play/colorpicker

$.fn.canvas = function(f){
	return this.map(function(){
		if (this.nodeName=="IMG"){
			var canvas=$('<canvas>')
			this.src = this.src // IE fix
			$(this).one('load',function(){
				canvas.attr({width:this.width,height:this.height})
				canvas[0].getContext('2d').drawImage(this,0,0,this.width,this.height)
				$(this).replaceWith(canvas)
			})
			return canvas[0]
		}else{
			return this
		}
	})
}

jQuery.Event.prototype.rgb=function(){
	var x =  this.offsetX || (this.pageX - $(this.target).offset().left),
		y =  this.offsetY || (this.pageY - $(this.target).offset().top)
	if (this.target.nodeName!=="CANVAS")return null
	return this.target.getContext('2d').getImageData(x,y,1,1).data
}
