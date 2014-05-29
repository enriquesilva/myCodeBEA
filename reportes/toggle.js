// place this before all of your code, outside of document ready.
$.fn.clicktoggle = function(a,b){
    return this.each(function(){
        var clicked = false;
        $(this).bind("click",function(){
            if (clicked) {
                clicked = false;
                return b.apply(this,arguments);
            }
            clicked = true;
            return a.apply(this,arguments);
        });
    });// fixed typo here, was missing )
};


// now you can use it elsewhere in place of existing .toggle
$("#mydiv").clicktoggle(even,odd);