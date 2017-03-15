var bonceInProgress = false;
init();

function init() {
    _.each($('.listing tr'), observeElement);
    _.each($('.js-temp button'), observeElement);
    _.each($('.js-form'), handleSubmit);
}


function handleSubmit(form) {
    var that = this;
    $(form).submit(function(event) {
        event.preventDefault();

        var data = {};
        var form = this;
        var fields = $(form).find('[name*='+this.name+']');

        _.each($(fields), function(field) {
            data[field.name] = field.value;
        });

        $.ajax({
            url: $(form).attr('action'),
            type: $(form).attr('method'),
            data: data,
            success: function(html) {
                if (html.indexOf('<form') != -1) {
                    _.each($('.js-shown'), hide);
                    $('.js-form').removeAttr('style');
                    $('.js-form').empty();
                    $('.js-form').append(html.replace(/<\/?form[^>]*>/g, ''));
                    _.each($('.js-form'), render);

                    return;
                }

                // remove stale response
                $('.js-response').remove();
                // clear form
                _.each($('.js-form'), function(element) {
                    element.reset();
                    $(element).find('.js-error').empty();
                    $(element).find('textarea').val('');
                });
                _.each($('js-shown'), hide);
                $('.sub-content').append(html);
                _.each($('.js-response'), show);
                $('.js-response .clickable').click(function () {
                    _.each($('.js-response'), hide);
                    _.each($('.js-temp'), show);
                });
            }
        })
    });
}

function observeElement(row) {
    $(row).click(function(){
        _.each($('.js-form'), render);
        $('.js-form').find('[name*=subject]').val($(row).data('subject'));
    });
}

function render(el) {
    this.show(el);
    $('.js-description').focus();
    var that = this;

    _.each($("button[type='reset']"), function(button) {
        $(button).click(function() {
            that.hide(el);
            _.each($('.js-temp'), show);
            $(that).off('keydown');
        });
    });

    $(this).keydown(function(target) {
        if (target.keyCode == 27) {
            that.hide(el);
            _.each($('.js-temp'), show);
            $(that).off('keydown');
        }
    });
}

function hide(el) {
    // Animate the popover
    dynamics.animate(el, {
        opacity: 0,
        scale: .1
    }, {
        type: dynamics.easeInOut,
        duration: 300,
        friction: 100
    });
    $(el).hide();
    $(el).removeClass('js-shown');
}

function show(el) {
    if ($(el).is(":visible") && el.nodeName == 'FORM') {
        this.bounce(el);
        return;
    }

    _.each($('.js-shown'), hide);
    $(el).show();
    $(el).addClass('js-shown');
    // Animate the popover
    dynamics.animate(el, {
        opacity: 1,
        scale: 1
    }, {
        type: dynamics.spring,
        frequency: 200,
        friction: 270,
        duration: 800
    })

    if ($(el).hasClass('js-response')) {
        dynamics.setTimeout(bounceResponse, 3000);
    }
}

function hideResponse() {
    if ($('.js-response').hasClass('js-shown')) {
        _.each($('.js-response'), hide);
        _.each($('.js-temp'), show);
    }

    $('.js-response').remove();
}

function bounceResponse() {
    _.each($('.js-response'), bounce);
    dynamics.setTimeout(hideResponse, 1300);
}

function bounce(el) {
    if (bonceInProgress) {
        return;
    }

    bonceInProgress = true;

    dynamics.animate(el, {
        translateY: -70
    }, {
        type: dynamics.forceWithGravity,
        bounciness: 1200,
        elasticity: 600,
        duration: 1650,
        delay: 100
    })

    setTimeout(function() {bonceInProgress = false;}, 1500);
}
