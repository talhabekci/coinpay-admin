$(document).ready(function() {

    var sidebar_icon = $("i.fa-regular.fa-bars");
    var sidebar = $(".sidebar");

    sidebar_icon.click(function() {

        sidebar.toggleClass("open");

    });

});