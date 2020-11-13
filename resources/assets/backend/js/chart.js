$(document).ready(function () {
    var productBuy = $('#container').data('order');
    var chartData = [];
    productBuy.forEach(function(element){
        var ele = {name : element.name, y : parseFloat(element.y) };
        chartData.push(ele);
    });
    Highcharts.chart('container', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Count of movies by type'
        },
        plotOptions: {
            pie: {
            allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: false
                },
            showInLegend: true
          }
        },
        series: [{
            name: 'Count',
            colorByPoint: true,
            data: chartData,
        }]
    });
});
