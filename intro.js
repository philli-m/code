YUI.add('rg.views.examples.IntroView', function (Y) {

    Y.namespace('rg.views.examples');

    Y.rg.views.examples.IntroView = Y.Base.create('examples.IntroView', Y.rg.WidgetView, [], {
        events: {
            '.js-clickme': {
                click: 'myfunction'
            },
            '.js-dontclickme': {
                click: 'thisFunction'
            }
        },
        afterRendered: function () {
            alert("hello");
            Y.log(this.data);
        },
        myfunction: function() {
            Y.rg.loadWidgetInDialog('aboutus.PressNewsletterSubscribeForm.html');
        },

        thisFunction: function () {
            alert("are you sure you want to do that Dave");

        }
    }, {
        ATTRS: {}
    });
}, '1.0.0', {
    requires: []
});
