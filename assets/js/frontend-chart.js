;(function ($, w) {
    'use strict';
    
var $window = $(w);

$window.on( 'elementor/frontend/init', function() {
    
    var EF = elementorFrontend,
        EM = elementorModules;
    
     /**
     * Chart Initialize Here
     * 
     * @author Saiful Islam <codersaiful@gmail.com>
     * @author B M Rafiul Alam <bmrafiul.alam@gmail.com>
     * @since 1.1.0.8
     */
     //Bar Chart
      var UA_Chart = EM.frontend.handlers.Base.extend({
        onInit: function(){
            this.run();
        },
        onChange: function(){
            this.run();
        },
        run: function(){

            var $scope = this.$element;
           
            /**
             * get data on editor mode
             */
            var $settings = this.getElementSettings();
            var $id = $scope[0].dataset.id;
           //Get Data List
            const $data    = $settings.list;

            let dLen       = $data.length;
            let $chartData = [],
                $labels = [],
                $backgroundColor = [];

            for (let i = 0; i < dLen; i++) {
                let $d = $data[i];
                $chartData.push($d.data);
                $labels.push($d.labels);
                $backgroundColor.push($d.backgroundColor);
            }
            //Datasets    
            const labels =  $labels;
            const barChartData = {
              labels: labels,
              datasets: [{
                label: $settings.legend_label,
                backgroundColor: $backgroundColor,
                borderColor:  $settings.borderColor,
                data: $chartData,
              }]
            };

            //Config Data
            const config = {
                type:'bar',
                data: barChartData,
                options: {
                    responsive: true,
                    plugins: {
                      title: {
                        display: true,
                        text: $settings.chart_title
                      },
                      legend: {
                            display: true,
                            position: $settings.legend_position,
                            align: 'middle',
                            labels: {
                                color: $settings.legend_color
                            }
                        }
                    },
                    scales: {
                      x: {
                        ticks: {
                          color: $settings.x_ticks_color,
                        }
                      },
                      y: {
                        ticks: {
                            color: $settings.y_ticks_color,
                        }
                      },
                      y: {
                        grid: {
                          drawBorder: false,
                          color:$settings.y_grid_color,
                        },
                      }

                    }
                  },
            };
            //Initialize
            var ctx = document.getElementById('uaChart-' + $id);
            const uaChart = new Chart(ctx,
                config
            );
            
        }
    });

     
    EF.hooks.addAction(
        'frontend/element_ready/ultraaddons-bar-chart.default',
        function ($scope) {
            EF.elementsHandler.addHandler(UA_Chart, {
                    $element: $scope,
            });
        }
    );

     //Line Chart

     var Line_chart = EM.frontend.handlers.Base.extend({
        onInit: function(){
            this.run();
        },
        onChange: function(){
            this.run();
        },
        run: function(){

            var $scope = this.$element;
           
            /**
             * get data on editor mode
             */
            var $settings  = this.getElementSettings();
            var $id        = $scope[0].dataset.id;
           //Get Data List
            const $data    = $settings.list;

            let dLen       = $data.length;
            let $chartData = [],
                $labels    = [],
                $backgroundColor = [];

            for (let i = 0; i < dLen; i++) {
                let $d = $data[i];
                $chartData.push($d.data);
                $labels.push($d.labels);
                $backgroundColor.push($d.backgroundColor);
            }
            //Datasets    
            const labels =  $labels;

            const lineChartData = {
                labels: labels,
                datasets: [{
                  label:  $settings.legend_label,
                  data: $chartData,
                  fill: ($settings.fill_color=='yes') ? true : false,
                  borderColor:  $settings.borderColor,
                  backgroundColor: $backgroundColor,
                  tension: 0.1,
                  borderWidth:$settings.borderWidth,
                  pointBorderWidth:$settings.pointBorderWidth,
                  pointBackgroundColor: $backgroundColor
                }]
              };

            //Config Data
            const config = {
                type:'line',
                data: lineChartData,
                options: {
                    responsive: true,
                    plugins: {
                      title: {
                        display: true,
                        text: $settings.chart_title
                      },
                      legend: {
                            display: true,
                            position: $settings.legend_position,
                            align: 'middle',
                            labels: {
                                color: $settings.legend_color
                            }
                        }
                    },
                    scales: {
                      x: {
                        ticks: {
                          color: $settings.x_ticks_color,
                        }
                      },
                      y: {
                        ticks: {
                            color: $settings.y_ticks_color,
                        }
                      },
                      y: {
                        grid: {
                          drawBorder: false,
                          color:$settings.y_grid_color,
                        },
                      }

                    }
                  },
            };
            //Initialize
            var ctx = document.getElementById('uaChart-' + $id);
            const uaChart = new Chart(ctx,
                config
            );
            
        }
    });

     
    EF.hooks.addAction(
        'frontend/element_ready/ultraaddons-line-chart.default',
        function ($scope) {
            EF.elementsHandler.addHandler(Line_chart, {
                    $element: $scope,
            });
        }
    );
  

});

} (jQuery, window));