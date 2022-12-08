$(document).ready(function() {

    var sidebar_icon = $("i.fa-regular.fa-bars");
    var sidebar = $(".sidebar");
    var $submenu_target = $(".fa-chevron-down").closest('li').children('ul');

    sidebar_icon.click(function() {

        sidebar.toggleClass("open");
        if ($submenu_target.attr("style") != "display: none;") {
            $submenu_target.slideUp();
        }

    });

    $(".fa-chevron-down").click(function(e) {
        if (sidebar.attr("class") != "sidebar") {
            e.preventDefault();
            $submenu_target.slideToggle();
        } else {
            location.href = "http://localhost/coinpay-admin/wallet/";
        }
    });

});