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


$.ajax({
    type: 'GET',
    url: 'http://' + host_name + '/coinpay-admin/src/backend/chart-query.php',
    dataType: 'json',
    async: false,
    success: function (response) {

        var labels = [];
        var datax = [];

        for (var i = 0; i < response.length; i++) {
            labels.push(response[i]["Date"]);
            datax.push(response[i]["Total-Price"]);
        }

        const data = {
            labels: labels,
            datasets: [{
                label: 'Daily Revenue',
                backgroundColor: 'rgb(3, 166, 109)',
                borderColor: 'rgb(3, 166, 109)',
                data: datax
            }]
        };

        const config = {
            type: 'line',
            data: data,
            options: {}
        };

        const myChart = new Chart(
            document.getElementById('myChart'),
            config
        );

    }
});