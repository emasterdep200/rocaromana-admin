@php

$lang = Session::get('language');

@endphp
@extends('layouts.main')

@section('title')
  Comercial Dashboard
@endsection
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.css">

<style>
    .card_info {
        margin-top: 68px;
        position: relative;
        top: 0px !important;
        bottom: 0px !important;
        margin-left: 9px;
    }
</style>

<section class="section">
    <div class="dashboard_title mb-3">Dashboard Comercial</div>
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="row">
                <div class="col-md-3">
                    <a href="https://admin.rocaromana.com/customer">

                    <div class="card">
                        <div class="card_info">
                            <div class="total_number">
                                {{ $asesores }}
                            </div>

                            <div class="card_title">
                                {{ __('Asesores') }}
                            </div>
                        </div>
                    </div>
                    </a>
                </div>

                <div class="col-md-3">
                    <a href="https://admin.rocaromana.com/customer">

                    <div class="card">
                        <div class="card_info">
                            <div class="total_number">
                                ${{ number_format($sum_ventas) }}
                            </div>

                            <div class="card_title">
                                {{ __('Total ventas') }}
                            </div>
                        </div>
                    </div>
                    </a>
                </div>

                <div class="col-md-3">
                    <a href="https://admin.rocaromana.com/customer">

                    <div class="card">
                        <div class="card_info">
                            <div class="total_number">
                                {{ $customers }}
                            </div>

                            <div class="card_title">
                                {{ __('Total clientes') }}
                            </div>
                        </div>
                    </div>
                    </a>
                </div>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 page-content bg-white rounded">
            <h2 class="text-left">Ventas en miles</h2>
            <div class="chart_tab">
                <nav>
                    <ul class="tabs">
                        <li class="tab-li">
                            <a href="#tab1" class="tab-li__link" id="Monthly">Mensual</a>
                        </li>
                        <li class="tab-li">
                            <a href="#tab1" class="tab-li__link" id="Weekly">Semanal</a>
                        </li>
                        <li class="tab-li">
                            <a href="#tab1" class="tab-li__link" id="Daily">Diario</a>
                        </li>
                    </ul>
                </nav>
            </div>
            <section id="tab1" data-tab-content>
                <div class="chart" id="chart"></div>
            </section>
        </div>
        <div class="col-md-6 page-content bg-white rounded">
            <h2 class="text-left">Distribución por ciudad</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Ciudad</th>
                        <th>Cantidad de paquetes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ventasCiudad as $vciudad)
                        <tr>
                            <td>{{ $vciudad['nombre'] }}</td>
                            <td>{{ $vciudad['ventas'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="row my-4">
        <div class="col-md-12 page-content bg-white rounded">
        <h2 class="text-left">Paquetes vendidos</h2>
            @foreach($paquetes as $paquete)
            <div class="col-md-3">
                <div class="card border">
                    <div class="card_info">
                        <div class="total_number">
                            {{ $paquete->vendidos }}
                        </div>

                        <div class="card_title">
                            {{ $paquete->name }}
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

</section>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>




<script>

            var myArray =<?php echo json_encode($chartData); ?>;

            const data = {
                Monthly: {
                    series1: [{{ implode(',', array_values($chartData['sellmonthSeries'])) }}],
                    categories: [{!! implode(',', $chartData['monthDates']) !!}],

                },
                Weekly: {
                    series1:[{{ implode(',', array_values($chartData['sellweekSeries'])) }}],
                    categories: [{!! implode(',', $chartData['weekDates']) !!}],

                },
                Daily: {
                    series1: [{{ implode(',', array_values($chartData['sellcountForCurrentDay'])) }}],
                    categories: [{!! implode(',', $chartData['currentDates']) !!}],

                },
            };

            var chartData = data['Monthly'];

            var options = {
                series: [
                    { name: 'Ventas(en Miles)', data: chartData.series1 },
                ],
                chart: {
                    height: 350,
                    type: 'area',
                    zoom: {
                        enabled: false // Disable zooming
                    },
                    toolbar: {
                        show: false // Hide the toolbar (including download button)
                    }
                },
                dataLabels: {
                enabled: false
                },
                stroke: {
                    curve: 'smooth'
                },
                xaxis: {
                    type: 'date',
                    categories:chartData.categories,
                },
                tooltip: {
                    x: {
                        format: 'dd/MM/yy HH:mm'
                    },
                },
                colors: ['#EB9D55', '#47BC78'],
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        
        chart.render();

        $(document).ready(function(){

            $('#tab1').attr('class','active');
            chart.render();
        });

          var nestedTabSelect = (tabsElement, currentElement) => {
                    const tabs = tabsElement ?? 'ul.tabs';
                    const currentClass = currentElement ?? 'active';

                    document.querySelectorAll(tabs).forEach(function (tabContainer) {
                    let activeLink, activeContent;
                    const links = Array.from(tabContainer.querySelectorAll("a"));

                    activeLink =links.find(function (link) {
                            return link.getAttribute("href") === location.hash;
                    }) || links[0];
                    activeLink.classList.add(currentClass);

                    activeContent = document.querySelector(activeLink.getAttribute("href"));
                    activeContent.classList.add(currentClass);

                    links.forEach(function (link) {
                            if (link !== activeLink) {
                                    const content = document.querySelector(link.getAttribute("href"));
                                    content.classList.remove(currentClass);
                            }
                    });

                    tabContainer.addEventListener("click", function (e) {
                            if (e.target.tagName === "A") {





                                tab=e.target.id;

                                chartData = data[tab];
                                chart.updateOptions({
                                    series: [
                                        { name: 'Ventas', data: chartData.series1 },
                                    ],
                                    xaxis:{
                                    categories:chartData.categories
                                    }

                                });



                                // chart.render();
                                // Make the old tab inactive.
                                activeLink.classList.remove(currentClass);
                                activeContent.classList.remove(currentClass);

                                // Update the variables with the new link and content.
                                activeLink = e.target;
                                activeContent = document.querySelector(activeLink.getAttribute("href"));

                                // Make the tab active.
                                activeLink.classList.add(currentClass);
                                activeContent.classList.add(currentClass);

                                e.preventDefault();
                            }
                    });
          });
        };

         nestedTabSelect('ul.tabs', 'active');

</script>
@endsection