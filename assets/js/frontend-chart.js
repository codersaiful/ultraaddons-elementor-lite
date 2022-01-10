;(function ($, w) {
    'use strict';
    
var $window = $(w);

$window.on( 'elementor/frontend/init', function() {
    
    var EF = elementorFrontend,
        EM = elementorModules;
    
     /**
     * All Chart widget Initialize Here
     * 
     * @author Saiful Islam <codersaiful@gmail.com>
     * @author B M Rafiul Alam <bmrafiul.alam@gmail.com>
     * @since 1.1.0.9
     */

   
    /*****************************************
    * Bar Chart Options and initialization js
    *  @author B M Rafiul Alam
    *****************************************/

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
                        },
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

     
   /*****************************************
    * Line Chart Options and initialization js
    *  @author B M Rafiul Alam
    *****************************************/

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
                        },
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

  
    /*****************************************
    * Pie Chart Options and initialization js
    *  @author B M Rafiul Alam
    *****************************************/

      var Pie_chart = EM.frontend.handlers.Base.extend({
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

            const pieChartData = {
                labels: labels,
                datasets: [{
                  label:  $settings.legend_label,
                  data: $chartData,
                  backgroundColor: $backgroundColor,
                  hoverOffset: 4
                }]
              };

            //Config Data
            const config = {
                type:'pie',
                data: pieChartData,
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
                        },
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
        'frontend/element_ready/ultraaddons-pie-chart.default',
        function ($scope) {
            EF.elementsHandler.addHandler(Pie_chart, {
                $element: $scope,
            });
        }
    );

    /*****************************************
    * Doughnut Chart Options and initialization js
    *  @author B M Rafiul Alam
    *****************************************/

      var Doughnut_chart = EM.frontend.handlers.Base.extend({
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

            const doughnutChartData = {
                labels: labels,
                datasets: [{
                  label:  $settings.legend_label,
                  data: $chartData,
                  backgroundColor: $backgroundColor,
                  hoverOffset: 4
                }]
              };

            //Config Data
            const config = {
                type:'doughnut',
                data: doughnutChartData,
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
                        },
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
        'frontend/element_ready/ultraaddons-doughnut-chart.default',
        function ($scope) {
            EF.elementsHandler.addHandler(Doughnut_chart, {
                $element: $scope,
            });
        }
    );

    /*****************************************
    * Polar Area Chart Chart Options and initialization js
    *  @author B M Rafiul Alam
    *****************************************/

     var Polar_chart = EM.frontend.handlers.Base.extend({
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

          const polarChartData = {
              labels: labels,
              datasets: [{
                label:  $settings.legend_label,
                data: $chartData,
                backgroundColor: $backgroundColor,
                hoverOffset: 4
              }]
            };

          //Config Data
          const config = {
              type:'polarArea',
              data: polarChartData,
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
                      },
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
      'frontend/element_ready/ultraaddons-polar-chart.default',
      function ($scope) {
          EF.elementsHandler.addHandler(Polar_chart, {
              $element: $scope,
          });
      }
  );

  /*****************************************
    * Mixed Chart Options and initialization js
    *  @author B M Rafiul Alam
    *****************************************/

   var Mixed_chart = EM.frontend.handlers.Base.extend({
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
        const $barDataList    = $settings.bar_data_list;
        const $lineDataList    = $settings.line_data_list;

        let $dLen       = $barDataList.length;
        let $chartData = [],
            $labels    = [],
            $backgroundColor = [];

        for (let i = 0; i < $dLen; i++) {
            let $d = $barDataList[i];
            $chartData.push($d.data);
            $labels.push($d.labels);
            $backgroundColor.push($d.backgroundColor);
        }
        //Datasets    
        const labels =  $labels;
        //Config Data
        const config = {
          type: "bar",
          data: {
            labels: labels,
            datasets: [
              {
                type: "bar",
                backgroundColor: $backgroundColor,
                borderColor:$settings.borderColor,
                borderWidth: 1,
                label:$settings.legend_label,
                data: $chartData,
                hoverOffset: 4
              },
              {
                type: "line",
                label:$settings.legend_label,
                data: $chartData,
                borderColor:$settings.borderColor,
                lineTension: 0,
                hoverOffset: 4
              }
            ]
          }
        };
        //Initialize
        var ctx = document.getElementById('uaChart-' + $id);
        const uaChart = new Chart(ctx,
            config
        );
        
        
    }
});

EF.hooks.addAction(
    'frontend/element_ready/ultraaddons-mixed-chart.default',
    function ($scope) {
        EF.elementsHandler.addHandler(Mixed_chart, {
            $element: $scope,
        });
    }
);
  

}); //end elementor/frontend/init 

} (jQuery, window));