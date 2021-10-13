@extends('layouts.master')
@section('content')
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Layout with Topup
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="page-content">
            <section class="row">
                <div class="col-12 col-lg-12">
                    <!------Balance ------------->
                    
                    <div class="row">
                        <div class="mb-2"><h4>Remaining Balance</h4></div>
                        <div class="col-6 col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body px-3 py-4-5">
                                    <div class="row">
                                        {{-- <div class="col-md-4">
                                            <div class="stats-icon purple">
                                                <i class="iconly-boldShow"></i>
                                            </div>
                                        </div> --}}
                                        <div class="col-md-8">
                                            <h6 class="text-muted font-semibold">MPT Balance</h6>
                                            <h6 class="font-extrabold mb-0" style="font-size: 18px;">{{ isset($balance['mpt']) ? $balance['mpt'] : 0 }} <small>MMK</small></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body px-3 py-4-5">
                                    <div class="row">
                                        {{-- <div class="col-md-4">
                                            <div class="stats-icon blue">
                                                <i class="iconly-boldProfile"></i>
                                            </div>
                                        </div> --}}
                                        <div class="col-md-8">
                                            <h6 class="text-muted font-semibold">Telenor Balance</h6>
                                            <h6 class="font-extrabold mb-0" style="font-size: 18px;">{{ isset($balance['telenor']) ? $balance['telenor'] : 0 }} <small>MMK</small></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body px-3 py-4-5">
                                    <div class="row">
                                        {{-- <div class="col-md-4">
                                            <div class="stats-icon green">
                                                <i class="iconly-boldAdd-User"></i>
                                            </div>
                                        </div> --}}
                                        <div class="col-md-8">
                                            <h6 class="text-muted font-semibold">Ooredoo Balance</h6>
                                            <h6 class="font-extrabold mb-0" style="font-size: 18px;">{{ isset($balance['ooredoo']) ? $balance['ooredoo'] : 0 }} <small>MMK</small></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body px-3 py-4-5">
                                    <div class="row">
                                        {{-- <div class="col-md-4">
                                            <div class="stats-icon red">
                                                <i class="iconly-boldBookmark"></i>
                                            </div>
                                        </div> --}}
                                        <div class="col-md-8">
                                            <h6 class="text-muted font-semibold">MyTel Balance</h6>
                                            <h6 class="font-extrabold mb-0" style="font-size: 18px;">{{ isset($balance['mytel']) ? $balance['mytel'] : 0 }} <small>MMK</small></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!------Summery----------->
                    <div class="row">
                        <div class="mb-2"><h4>Used Balance</h4></div>
                        <div class="col-6 col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body px-3 py-4-5">
                                    <div class="row">
                                        <div class="col-md-4">
                                            {{-- <div class="stats-icon purple">
                                                <i class="iconly-boldShow"></i>
                                            </div> --}}
                                            <img src="{{ asset('images/logo/1.png') }}" class="img-fluid">
                                        </div>
                                        <div class="col-md-8">
                                            <h6 class="text-muted font-semibold">Total Used Amount (Bill and Data)</h6>
                                            <h6 class="font-extrabold mb-0" style="font-size: 18px;">{{ $total }} <small>MMK</small></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body px-3 py-4-5">
                                    <div class="row">
                                        <div class="col-md-4">
                                            {{-- <div class="stats-icon blue">
                                                <i class="iconly-boldProfile"></i>
                                            </div> --}}
                                            <img src="{{ asset('images/logo/2.png') }}" class="img-fluid">
                                        </div>
                                        <div class="col-md-8">
                                            <h6 class="text-muted font-semibold">Current Month Total Used (Bill and Data)</h6>
                                            <h6 class="font-extrabold mb-0" style="font-size: 18px;">{{ $currMonthTotal }} <small>MMK</small></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body px-3 py-4-5">
                                    <div class="row">
                                        <div class="col-md-4">
                                            {{-- <div class="stats-icon green">
                                                <i class="iconly-boldAdd-User"></i>
                                            </div> --}}
                                            <img src="{{ asset('images/logo/3.png') }}" class="img-fluid">
                                        </div>
                                        <div class="col-md-8">
                                            <h6 class="text-muted font-semibold">Current Month Bill Total Used</h6>
                                            <h6 class="font-extrabold mb-0" style="font-size: 18px;">{{ $currMonthBillTotalByUser }} <small>MMK</small></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body px-3 py-4-5">
                                    <div class="row">
                                        <div class="col-md-4">
                                            {{-- <div class="stats-icon red">
                                                <i class="iconly-boldBookmark"></i>
                                            </div> --}}
                                            <img src="{{ asset('images/logo/4.png') }}" class="img-fluid">
                                        </div>
                                        <div class="col-md-8">
                                            <h6 class="text-muted font-semibold">Current Month Data Total Used</h6>
                                            <h6 class="font-extrabold mb-0" style="font-size: 18px;">{{ $currMonthDataTotalByUser }} <small>MMK</small></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Chart form layout section start ---->
                    <!-- bill, data, total yearly chart ---->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <h4>Bill, Data and Total (Current Year)</h4>
                                        </div>
                                        <div class="col-md-2">
                                            <select name="" id="choiceYear" class="input-group">
                                                @foreach ($usedYears as $item)
                                                    <option value="{{$item->year}}" {{ (now()->year == $item->year) ? 'selected' : '' }}>{{$item->year}}</option>
                                                @endforeach                                                
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div id="yearlyChart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!---bill by operator --->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Billing Usage</h4>
                                </div>
                                <div class="card-body">
                                    <div id="billByOperator"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--- bill by operator end ---->
                    <!---data by operator chart --->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Data Usage</h4>
                                </div>
                                <div class="card-body">
                                    <div id="dataByOperator"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!---bill chart --->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Bill Services</h4>
                                </div>
                                <div class="card-body">
                                    <div id="billingPrice"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--- bill end ---->
                    <!---data chart --->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Data Packages</h4>
                                </div>
                                <div class="card-body">
                                    <div id="datalingPrice"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--- data end ---->
                </div>
            </section>
        </div>
    </section>
@endsection
@section('script')
    <script>

        var mpt_bill = {!! json_encode($billByMPT) !!};
        var telenor_bill = {!! json_encode($billByTelenor) !!};
        var ooredoo_bill = {!! json_encode($billByOoredoo) !!};
        var mytel_bill = {!! json_encode($billByMyTel) !!};


        var mpt_data = {!! json_encode($dataByMPT) !!};
        var telenor_data = {!! json_encode($dataByTelenor) !!};
        var ooredoo_data = {!! json_encode($dataByOoredoo) !!};
        var mytel_data = {!! json_encode($dataByMyTel) !!};

        var billPrice = {!! json_encode($billByPrice) !!};
        var dataPrice = {!! json_encode($packageByPrice) !!};
        
        let billTotalCurrentYear = {!! json_encode($billTotalCurrentYear) !!};
        let dataTotalCurrentYear = {!! json_encode($dataTotalCurrentYear) !!};
        let totalCurrentYear = {!! json_encode($totalCurrentYear) !!};

        const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

        let currentYearBill = billTotalCurrentYear.map((v) => {
            return parseInt(v.price)
        })

        let currentYearData = dataTotalCurrentYear.map((v) => {
            return parseInt(v.price)
        })

        let currentYearTotal = totalCurrentYear.map((v) => {
            return parseInt(v.total)
        })

        let currentYearCat = totalCurrentYear.map((v) => {
            return `${monthNames[v.month - 1]}`;
        })

        let yearlyChart = Highcharts.chart('yearlyChart', {

            chart: {
                type: 'column'
            },

            title: {
                text: 'Current Year (Bill, Data and Total)'
            },

            xAxis: {
                categories: currentYearCat
            },

            yAxis: {
                allowDecimals: false,
                min: 0,
                title: {
                    text: 'Number of fruits'
                }
            },

            tooltip: {
                formatter: function () {
                    return '<b>' + this.x + '</b><br/>' +
                        this.series.name + ': ' + this.y + '<br/>' +
                        'Total: ' + this.point.stackTotal;
                }
            },

            plotOptions: {
                column: {
                    stacking: 'normal',
                    dataLabels: {
                        enabled: true
                    }
                }
            },

            series: [{
                name: 'Bill',
                data: currentYearBill,
                stack: 'one',
                color: '#00fa9a'
            }, {
                name: 'Data',
                data: currentYearData,
                stack: 'one',
                color: '#809FFF'
            }]
        });


        let price = billPrice.map(val => {
            return parseInt(val.price);
        })
        let service = billPrice.map(val => {
            return val.service;
        })

        let amount = dataPrice.map(val => {
            return val.price;
        })
        let package = dataPrice.map(val => {
            return val.volume;
        })
        
        // billByOperator chart
        Highcharts.chart('billByOperator', {
            chart: {
                type: "column"
            },
            accessibility: {
                announceNewData: {
                    enabled: true
                }
            },
            xAxis: {

                categories: ["MPT", "Telenor", "Ooredoo", "MyTel"]
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                series: {
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        format: "{point.y}"
                    }
                }
            },

            series: [
                {
                    name: "Prices",
                    colorByPoint: true,
                    data: [
                        {
                            name: "MPT",
                            y: parseInt(mpt_bill),
                            color: '#FFCD00'
                        },
                        {
                            name: "Telenor",
                            y: parseInt(telenor_bill),
                            color: '#30C3F9'
                        },
                        {
                            name: "Ooredoo",
                            y: parseInt(ooredoo_bill),
                            color: '#ED1B24'
                        },
                        {
                            name: "MyTel",
                            y: parseInt(mytel_bill),
                            color: '#EF5800'
                        },

                    ]
                }
            ],
        });

        // dataByOperator chart
        Highcharts.chart('dataByOperator', {
            chart: {
                type: "column"
            },
            accessibility: {
                announceNewData: {
                    enabled: true
                }
            },
            xAxis: {
                categories: ["MPT", "Telenor", "Ooredoo", "MyTel"]
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                series: {
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        format: "{point.y}"
                    }
                }
            },

            series: [
                {
                    name: "Prices",
                    colorByPoint: true,
                    data: [
                        {
                            name: "MPT",
                            color: "#FFCD00",
                            y: parseInt(mpt_data),
                        },
                        {
                            name: "Telenor",
                            color: "#30C3F9",
                            y: parseInt(telenor_data),
                        },
                        {
                            name: "Ooredoo",
                            color: "#ED1B24",
                            y: parseInt(ooredoo_data),
                        },
                        {
                            name: "MyTel",
                            color: "#EF5800",
                            y: parseInt(mytel_data),
                        },
                    ]
                }
            ],
        });

        // billingPrice chart
        Highcharts.chart('billingPrice', {
            chart: {
                type: 'bar'
            },
            xAxis: {
                categories: service,
                title: {
                    text: null
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Population (millions)',
                    align: 'high'
                },
                labels: {
                    overflow: 'justify'
                }
            },
            plotOptions: {
                bar: {
                    dataLabels: {
                        enabled: true
                    }
                }
            },
            legend: {
                enabled:false,
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -40,
                y: 80,
                floating: true,
                borderWidth: 1,
                backgroundColor:
                    Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
                shadow: true
            },
            credits: {
                enabled: false
            },
            series: [{
                name: 'MMK',
                data: price
            }]
        });


        // datalingPrice chart
        Highcharts.chart('datalingPrice', {
            chart: {
                type: 'bar'
            },
            xAxis: {
                categories: package,
                title: {
                    text: null
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Population (millions)',
                    align: 'high'
                },
                labels: {
                    overflow: 'justify'
                }
            },
            plotOptions: {
                bar: {
                    dataLabels: {
                        enabled: true
                    }
                }
            },
            legend: {
                enabled: false,
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -40,
                y: 80,
                floating: true,
                borderWidth: 1,
                backgroundColor:
                    Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
                shadow: true
            },
            credits: {
                enabled: false
            },
            series: [{
                name: "MMK",
                data: package
            }]
        });

        $('#choiceYear').change(function (e) { 
            let year = $(this).val();
            axios.post('/ysearch', { year }).then(({data}) => {
                

                currentYearCat = data[2].map(v => {
                    return `${monthNames[v.month - 1]}`;
                })

                currentYearBill = data[0].map(v => {
                    return parseInt(v.price)
                })

                currentYearData = data[1].map(v => {
                    return parseInt(v.price)
                })

                yearlyChart.update({
                    xAxis: {
                        categories: currentYearCat
                    },
                    series: [{
                        
                        data: currentYearBill,
                        
                    }, {
                        
                        data: currentYearData,
                        
                    }]
                })
            })
            
            
        });

    </script>

@endsection
