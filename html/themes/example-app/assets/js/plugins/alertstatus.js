app.alertstatus = {
    cleanUrl: true,
    init: function (el) {

        var props = this.getProps(el);
        this.checkParams(props);
        this.hookClose(props);
    },
    getProps: function (el) {
        return {
            alert: el,
            params: el.attr('data-params').split(','),
            text: el.find('[data-text]'),
            close: el.find('[data-close]'),
        };
    },
    checkParams: function(props){
        var _this = this;

        $.each(props.params, function(index, value){
            var status = _this.getParameterByName(value, false);
            if (status !== null) {
                _this.triggerAlert(props, value, status);
                _this.removeParamFromUrl(value);
            }
        });
    },
    removeParamFromUrl: function(value){
        if (this.cleanUrl) {
            var url = this.getUrlSansParam(value, false);
            var path = url.replace(window.location.origin, '');
            window.history.pushState({}, document.title, path );
        }
    },
    triggerAlert: function(props, param, status) {
        props.alert.removeClass(props.params.join(' '));
        props.alert.addClass(param);
        props.text.text(status);
    },
    hookClose: function(props) {
        props.close.click(function(){
            props.alert.removeClass(props.params.join(' '));
            props.text.text('');
        });
    },
    getParameterByName: function(name, url) {
        url = (!url) ? window.location.href : url;
        name = name.replace(/[\[\]]/g, "\\$&");

        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)");
        var results = regex.exec(url);

        if (!results) {
            return null;
        }

        return (!results[2])
            ? ''
            : decodeURIComponent(results[2].replace(/\+/g, " "));
    },
    getUrlSansParam: function(parameter, url) {
        url = (!url) ? window.location.href : url;
        //prefer to use l.search if you have a location/link object
        var urlparts= url.split('?');
        if (urlparts.length>=2) {

            var prefix= encodeURIComponent(parameter)+'=';
            var pars= urlparts[1].split(/[&;]/g);

            //reverse iteration as may be destructive
            for (var i= pars.length; i-- > 0;) {
                //idiom for string.startsWith
                if (pars[i].lastIndexOf(prefix, 0) !== -1) {
                    pars.splice(i, 1);
                }
            }

            url= urlparts[0] + (pars.length > 0 ? '?' + pars.join('&') : "");
            return url;
        } else {
            return url;
        }
    }
};

