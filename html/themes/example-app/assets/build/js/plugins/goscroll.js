app.goscroll={init:function(t){this.el=t,this.hash=t.attr("data-hash"),this.offset=this.getOffset(t),this.windowHash=window.location.hash,this.loadListener()},loadListener:function(){if(this.hash===this.windowHash&&this.hash.length){var t='[data-hash="'+this.hash+'"]',s=$(t).offset().top-this.offset;$("html, body").animate({scrollTop:s},800)}},getOffset:function(t){return void 0!==t.attr("data-offset")?parseInt(t.attr("data-offset")):0}};