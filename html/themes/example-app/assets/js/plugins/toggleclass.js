app.toggleclass = {
    init: function (el) {
        this.hookToggle(this.getProps(el));
    },
    getProps: function(el) {
        return {
            el: el,
            toggleClass: el.attr('data-toggle-class'),
            toggleTarget: $(el.attr('data-toggle-target')),
        };
    },
    hookToggle: function(props) {
        props.el.click(function(){
            props.toggleTarget.toggleClass(props.toggleClass);
        });
    }
};