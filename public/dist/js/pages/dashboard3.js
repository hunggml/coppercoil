$(function () {
  'use strict'

  var ticksStyle = {
    fontColor: '#495057',
    fontStyle: 'bold'
  }

  var mode      = 'index'
  var intersect = true

  var $salesChart = $('#sales-chart')
  var salesChart  = new Chart($salesChart, {
    type   : 'bar',
    data   : {
      labels  : [__month.jan+'-2021', __month.feb+'-2021', __month.mar+'-2021', __month.apr+'-2021', __month.may+'-2021', __month.jun+'-2021', __month.jul+'-2021'],
      datasets: [
        {
          backgroundColor: '#dc3545',
          borderColor    : '#dc3545',
          data           : [1000, 2000, 3000, 2500, 2700, 2500, 3000]
        },
        {
          backgroundColor: '#28a745',
          borderColor    : '#28a745',
          data           : [700, 1700, 2700, 2000, 1800, 1500, 2000]
        }
      ]
    },
    options: {
      maintainAspectRatio: false,
      tooltips           : {
        mode     : mode,
        intersect: intersect
      },
      hover              : {
        mode     : mode,
        intersect: intersect
      },
      legend             : {
        display: false
      },
      scales             : {
        yAxes: [{
          // display: false,
          gridLines: {
            display      : true,
            lineWidth    : '4px',
            color        : 'rgba(0, 0, 0, .2)',
            zeroLineColor: 'transparent'
          },
          ticks    : $.extend({
            beginAtZero: true,

            // Include a dollar sign in the ticks
            callback: function (value, index, values) {
              // if (value >= 1000) {
              //   value /= 1000
              //   // value += 'k'
              // }
              return value
            }
          }, ticksStyle)
        }],
        xAxes: [{
          display  : true,
          gridLines: {
            display: false
          },
          ticks    : ticksStyle
        }]
      }
    }
  })

  var $visitorsChart = $('#visitors-chart')

  var visitorsChart  = new Chart($visitorsChart, {
    data   : {
      labels  : [__month.jan+'-2021', __month.feb+'-2021', __month.mar+'-2021', __month.apr+'-2021', __month.may+'-2021', __month.jun+'-2021', __month.jul+'-2021'],
      datasets: [{
        type                : 'line',
        data                : [100, 120, 170, 167, 180, 177, 160],
        backgroundColor     : 'transparent',
        borderColor         : '#ffc107',
        pointBorderColor    : '#ffc107',
        pointBackgroundColor: '#ffc107',
        fill                : false
        // pointHoverBackgroundColor: '#007bff',
        // pointHoverBorderColor    : '#007bff'
      },
        {
          type                : 'line',
          data                : [60, 80, 70, 67, 80, 77, 100],
          backgroundColor     : 'tansparent',
          borderColor         : '#ced4da',
          pointBorderColor    : '#ced4da',
          pointBackgroundColor: '#ced4da',
          fill                : false
          // pointHoverBackgroundColor: '#ced4da',
          // pointHoverBorderColor    : '#ced4da'
        }]
    },
    options: {
      maintainAspectRatio: false,
      tooltips           : {
        mode     : mode,
        intersect: intersect
      },
      hover              : {
        mode     : mode,
        intersect: intersect
      },
      legend             : {
        display: false
      },
      scales             : {
        yAxes: [{
          // display: false,
          gridLines: {
            display      : true,
            lineWidth    : '4px',
            color        : 'rgba(0, 0, 0, .2)',
            zeroLineColor: 'transparent'
          },
          ticks    : $.extend({
            beginAtZero : true,
            suggestedMax: 200
          }, ticksStyle)
        }],
        xAxes: [{
          display  : true,
          gridLines: {
            display: false
          },
          ticks    : ticksStyle
        }]
      }
    }
  })


  function apiDashboard()
  {
    return $.ajax({
      method  : 'get',
      url     : window.location.origin + '/dashboard',
      data    : {},
      dataType: 'json'
    })
  }

  let time;
  let labels = [];

  apiDashboard().done(function(data)
  {
    // console.log(data);
    $('#importNow').text(data.data.import.t6);
    $('#exportNow').text(data.data.export.t6);
    $('#inventoriesNow').text(data.data.inventory.t6);
    // console.log(visitorsChart.data.datasets,  salesChart.data.datasets);

    for (var i = 0; i <= 6; i++) 
    {
      labels.push(moment().locale(lang).subtract(i, 'month').format('MMM-YYYY'));
    }

    visitorsChart.data.datasets.forEach((dataset) => 
    {
      dataset.data = [];
    });

    salesChart.data.datasets.forEach((dataset) => 
    {
      dataset.data = [];
    });

    // import
    salesChart.data.datasets[0].data = [
      data.data.import.t0,
      data.data.import.t1,
      data.data.import.t2,
      data.data.import.t3,
      data.data.import.t4,
      data.data.import.t5,
      data.data.import.t6,
    ];

    // export
    salesChart.data.datasets[1].data = [
      data.data.export.t0,
      data.data.export.t1,
      data.data.export.t2,
      data.data.export.t3,
      data.data.export.t4,
      data.data.export.t5,
      data.data.export.t6,
    ];

    salesChart.data.labels = labels.reverse();
    
    salesChart.update();

    visitorsChart.data.datasets[0].data.push(
      data.data.inventory.t0,
      data.data.inventory.t1,
      data.data.inventory.t2,
      data.data.inventory.t3,
      data.data.inventory.t4,
      data.data.inventory.t5,
      data.data.inventory.t6,
    );

    visitorsChart.data.datasets[1].data.push(0,0,0,0,0,0,0);

    visitorsChart.data.labels = labels;

    visitorsChart.update();

    set_timeout();
  }).fail(function(err){console.log(err)})

  function set_timeout()
  {
    clearTimeout(time);

    time = setTimeout(function()
    {
      apiDashboard().done(function(data)
      {
        // console.log(data);
        $('#importNow').text(data.data.import.t6);
        $('#exportNow').text(data.data.export.t6);
        $('#inventoriesNow').text(data.data.inventory.t6);
        // console.log(visitorsChart.data.datasets,  salesChart.data.datasets);

        visitorsChart.data.datasets.forEach((dataset) => 
        {
          dataset.data = [];
        });

        salesChart.data.datasets.forEach((dataset) => 
        {
          dataset.data = [];
        });

        labels = [];

        for (var i = 0; i <= 6; i++) 
        {
          labels.push(moment().subtract(i, 'month').format('MMM-YYYY'));
        }

        // import
        salesChart.data.datasets[0].data = [
          data.data.import.t0,
          data.data.import.t1,
          data.data.import.t2,
          data.data.import.t3,
          data.data.import.t4,
          data.data.import.t5,
          data.data.import.t6,
        ];

        // export
        salesChart.data.datasets[1].data = [
          data.data.export.t0,
          data.data.export.t1,
          data.data.export.t2,
          data.data.export.t3,
          data.data.export.t4,
          data.data.export.t5,
          data.data.export.t6,
        ];
        salesChart.data.labels = labels.reverse();
        
        salesChart.update();

        visitorsChart.data.datasets[0].data.push(
          data.data.inventory.t0,
          data.data.inventory.t1,
          data.data.inventory.t2,
          data.data.inventory.t3,
          data.data.inventory.t4,
          data.data.inventory.t5,
          data.data.inventory.t6,
        );

        visitorsChart.data.labels = labels;

        visitorsChart.update();

        set_timeout();
      }).fail(function(err){console.log(err)})

    }, 30000)
  }
})


