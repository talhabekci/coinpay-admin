$(document).ready(function () {

    var host_name = "";

    $.ajax({
        method: "POST",
        url: "src/backend/host-name.php",
        async: false,
        data: {
            data: "host_name"
        },
        dataType: "json",
        success: function (response) {
            host_name = response["ip_address"]
        }
    });

    $("div.sidebar > ul.nav-list > li > a > i.fa-regular.fa-box-dollar").addClass("active");

});
