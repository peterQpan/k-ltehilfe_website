// Einfacher Counter für die Counter-Elemente
$(document).ready(function() {
    $('.counter-number').each(function() {
        var $this = $(this),
            countTo = $this.attr('data-to');
        $({ countNum: $this.text() }).animate({
            countNum: countTo
        }, {
            duration: parseInt($this.attr('data-speed')) || 1000,
            easing: 'linear',
            step: function() {
                $this.text(Math.floor(this.countNum));
            },
            complete: function() {
                $this.text(this.countNum);
            }
        });
    });
});
