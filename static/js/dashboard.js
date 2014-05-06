function dataKLine(type,container){
    var container = container;
    if (type != 1){
        var url = '/api/kline_data/day';
    }else{
        var url = '/api/kline_data/minute';
    }
    jQuery.getJSON(url, function(data) {
        var typeSeleted = 1;
        var typeDateFormat = '%Y-%m-%d %H:%M %A';
        var typeButtons = [
            {type: 'week',count: 1,text: '1周'},
            {type: 'month',count: 1,text: '1月'},
        ];
        var typexAxis ;
        var xAxisMoney = "BTC/CNY";
        var labelText  = "成交量(BTC)";

        if(type == 1){//5分钟
            typeSeleted = 0;
            typeButtons = [
                {type: 'hour',count:8,text: '8h'},
                {type: 'day',count:1,text: '1d'},
            ];
        }
        if(type !=1){
            typexAxis = [{labels: {formatter: function() { return  Highcharts.dateFormat('%m-%d', this.value);}}}];
            typeDateFormat = '%Y-%m-%d';
        }
        var ohlc = [],
            volume = [],
            dataLength = data.length;
        var  avg5 = [],avg15=[];
        var avg5Sum = 0,avg15Sum=0;
        for (var i = 0; i < dataLength; i++) {
            ohlc.push([
                data[i][0], // the date
                data[i][1], // open
                data[i][2], // high
                data[i][3], // low
                data[i][4] // close
            ]);

            volume.push([
                data[i][0], // the date
                data[i][5] // the volume
            ]);
            if(type!=1){
                //5日均线
                avg5Sum += data[i][4];
                if( i >= 4 ){
                    avg5.push([data[i][0], Math.floor(avg5Sum/5)]);
                    avg5Sum -= data[i-4][4];
                }
                //15日均线
                avg15Sum += data[i][4];
                if( i >= 14 ){
                    avg15.push([data[i][0], Math.floor(avg15Sum/15)]);
                    avg15Sum -= data[i-14][4];
                }
            }
        }
        Highcharts.setOptions({
            global: { useUTC: false },
            lang : {
                loading : 'Loading...',
                weekdays : [ '周日', '周一', '周二', '周三', '周四', '周五', '周六' ],
                decimalPoint : '.',
                numericSymbols : [ 'k', 'M', 'G', 'T', 'P', 'E' ], // SI
                resetZoom : 'Reset zoom',
                resetZoomTitle : 'Reset zoom level 1:1'
            },
            credits : {
                enabled : true,
                text : 'BtDaddy.com',
                href : 'https://www.btdaddy.com',
                position : {
                    align : 'right',
                    x : -10,
                    verticalAlign : 'bottom',
                    y : -5
                },
                style : {
                    cursor : 'pointer',
                    color : '#909090',
                    fontSize : '10px'
                }
            }
        });
        Highcharts.myOpt = {
            symbolColor : '#058dc7'
        };

        // set the allowed units for data grouping
        var groupingUnits = [[
            'week',                         // unit name
            [1]                           // allowed multiples
        ], [
            'month',
            [1, 2, 3, 4, 6]
        ]];
        // create the chart
        jQuery(container).highcharts('StockChart', {
            chart: {
                renderTo: 'rates',
                alignTicks: false
            },
            plotOptions :
            {
                candlestick : {
                    color: '#d33',
                    upColor: '#9e4',
                    ohlc :{
                        //color: 'green',
                        tooltip:{
                            // 日期时间格式化
                            xDateFormat: '%Y-%m-%d %H:%M %A',
                            color : '#f0f',
                            changeDecimals : 4
                        }
                    }
                },
                column:{
                    color : '#1080BB'
                }
            },
            rangeSelector: {
                selected: 1
            },
            colors: ['#058DC7', '#50B432', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4'],
            tooltip:{
                // 日期时间格式化
                xDateFormat: typeDateFormat,
                color : '#f0f',
                changeDecimals : 4,
                borderColor: '#058dc7'
            },
            rangeSelector: {
                buttons: typeButtons,
                // 默认选择域：0（缩放按钮中的第一个）、1（缩放按钮中的第二个）……
                selected: typeSeleted,
                // 是否允许input标签选框
                inputEnabled: false
            },
            exporting:{
                // 是否允许导出
                enabled:false,
                // 按钮配置
                buttons:{
                    // 导出按钮配置
                    exportButton:{
                        enabled:false
                    },
                    // 打印按钮配置
                    printButton:{
                        enabled:true
                    }
                }
            },
            yAxis: [
                {
                    title: {
                        text: '价格(CNY)'
                    },
                    top: 10,
                    height: 270,
                    lineWidth: 2,
                    gridLineDashStyle : 'ShortDot',
                    showLastLabel : true
                },
                {
                    title: {
                        text: labelText
                    },
                    top: 252,
                    height: 50,
                    lineWidth: 2,
                    gridLineColor:'#ffffff',
                    gridLineDashStyle : 'ShortDot',
                    showLastLabel : true,
                    opposite:true
                }
            ],
            xAxis: typexAxis,
            series: [
                {
                    type: 'candlestick',
                    name: xAxisMoney,
                    data: ohlc,
                    yAxis: 0,
                    dataGrouping: {
                        units: groupingUnits,
                        enabled: false
                    }
                },
                {
                    type: 'spline',
                    data: avg5,
                    name: '5均线',
                    color: 'black',
                    lineWidth:1.5
                },
                {
                    type: 'spline',
                    data: avg15,
                    name: '15均线',
                    color: 'blue',
                    lineWidth:1.5
                },
                {
                    type: 'column',
                    name: '成交量',
                    data: volume,
                    yAxis: 1,
                    dataGrouping: {
                        units: groupingUnits,
                        enabled: false
                    }
                }]
        });
    });
}


function refresh_order_list(){
    $.getJSON('/api/recent_deals/dashboard',function(e){
        $('#new_bid_orders').html(e.bid);
        $('#new_ask_orders').html(e.ask);
        $('#new_deals').html(e.deals);
    })
}

function refresh_realtime_price(){
    $.getJSON('/api/recent_deals/real_time_price',function(e){
        $('#newest_deal_price').html(e.newest_deal_price);
        $('#bid1').html(e.bid1);
        $('#ask1').html(e.ask1);
        $('#highest_price').html(e.highest_price);
        $('#lowest_price').html(e.lowest_price);
        $('#today_amount').html(e.today_amount);
    })
}

$(function(){
    var highchartsOptions = Highcharts.setOptions(Highcharts.theme);

    dataKLine(1,'#k-line-container');


});

setInterval("refresh_order_list()",10000);
setInterval("refresh_realtime_price()",10000);

