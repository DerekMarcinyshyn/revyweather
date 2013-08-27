jQuery(document).ready(function() {
    jQuery('#nav').onePageNav({
        currentClass: 'active',
        changeHash: false,
        scrollSpeed: 750,
        scrollOffset: 30,
        scrollThreshold: 0.5,
        filter: '',
        easing: 'swing',
        begin: function() {},
        end: function() {},
        scrollChange: function($currentListItem) {}
    });
    $(function() {
        $("#toTop").scrollToTop(1000);
    });
});