app.clickyform = {
    init: function (el) {
        this.hookClickies(this.getProps(el));
        this.hookFormSubmit(this.getProps(el));
    },
    getProps: function (form) {
        return {
            form: form,
            activeClass: form.attr('data-clicky-active-class'),
            clickies: form.find('[data-clicky]'),
            submit: form.find('[type="submit"]'),
            formData: form.serialize()
        };
    },
    hookClickies: function(props) {
        var _this = this;
        props.clickies.each(function(){

            var type = $(this).attr('data-clicky');
            var all = props.clickies.filter('[data-clicky=' + type + ']');
            var allExceptCur = all.not($(this));

            $(this).click(function(){
                allExceptCur.removeClass(props.activeClass);
                $(this).toggleClass(props.activeClass);
            });
        });
    },
    hookFormSubmit: function(props)
    {
        props.submit.click(function(e){
            e.preventDefault();

            var fields = props.clickies.filter('.' + props.activeClass);

            fields.each(function(){
                var name = $(this).attr('data-clicky');
                var value = $(this).attr('data-value');

                var input = props.form.find('[name="' + name + '"]');

                // value already set it if exists, otherwise:
                if (!input.length || (input.length && !input.val().length)) {
                    props.form.append('<input name="' + name + '" type="hidden" value="' + value + '"/>');
                }

                props.form.find('input').each(function(){
                    var input = $(this);

                    if (!input.val().length) {
                        input.remove();
                    }
                });

            });

            props.form.submit();
        });
    }

};
