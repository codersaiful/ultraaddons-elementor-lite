   /**
     * Moving Letters Finalized Here
     * 
     * Actually most of the part of this Widget has done by B M Rafiul Alam but
     * JS part had developed by(Me) Saiful Islam
     * 
     * @author Saiful Islam <codersaiful@gmail.com>
     * @author B M Rafiul Alam <bmrafiul.alam@gmail.com>
     * @since 1.1.0.8
     */

    ;(function ($, w) {
        'use strict';
        
    var $window = $(w);
    
    $window.on( 'elementor/frontend/init', function() {
            
        var cx_settings;
        var EF = elementorFrontend,
            EM = elementorModules;
        
        var ModuleBase = elementorModules.frontend.handlers.Base;
        var CusttomCSS;
    
    
        var Moving_Letters = EM.frontend.handlers.Base.extend({
            onInit: function(){
                this.run();
            },
            onChange: function(){
                this.run();
            },
            run: function(){
    
                var $scope = this.$element;
                var settings = this.getElementSettings(); //Get Data on Editor mode
                var anim_data = $scope[0].dataset.settings; //Get Data on frontend mode
                    if(anim_data){
                        var obj = JSON.parse(anim_data);
                    }
    
                    if(typeof(obj)!= "undefined" && obj.anim_type==1 || settings.anim_type==1){
                        // Wrap every letter in a span
                        var textWrapper = document.querySelector('.ml1 .letters');
                        textWrapper.innerHTML = textWrapper.textContent.replace(/\S/g, "<span class='letter'>$&</span>");
    
                        anime.timeline({loop: true})
                        .add({
                            targets: '.ml1 .letter',
                            scale: [0.3,1],
                            opacity: [0,1],
                            translateZ: 0,
                            easing: "easeOutExpo",
                            duration: 600,
                            delay: (el, i) => 70 * (i+1)
                        }).add({
                            targets: '.ml1 .line',
                            scaleX: [0,1],
                            opacity: [0.5,1],
                            easing: "easeOutExpo",
                            duration: 700,
                            offset: '-=875',
                            delay: (el, i, l) => 80 * (l - i)
                        }).add({
                            targets: '.ml1',
                            opacity: 0,
                            duration: 1000,
                            easing: "easeOutExpo",
                            delay: 1000
                        });
                    }
    
                    if(typeof(obj)!= "undefined" && obj.anim_type==2 || settings.anim_type==2){
                        var textWrapper = document.querySelector('.ml2');
                        textWrapper.innerHTML = textWrapper.textContent.replace(/\S/g, "<span class='letter'>$&</span>");
    
                        anime.timeline({loop: true})
                        .add({
                            targets: '.ml2 .letter',
                            scale: [4,1],
                            opacity: [0,1],
                            translateZ: 0,
                            easing: "easeOutExpo",
                            duration: 950,
                            delay: (el, i) => 70*i
                        }).add({
                            targets: '.ml2',
                            opacity: 0,
                            duration: 1000,
                            easing: "easeOutExpo",
                            delay: 1000
                        });
                    }
    
                    if(typeof(obj)!= "undefined" &&  obj.anim_type==3 || settings.anim_type==3){
                    // Wrap every letter in a span
                        var textWrapper = document.querySelector('.ml3');
                        textWrapper.innerHTML = textWrapper.textContent.replace(/\S/g, "<span class='letter'>$&</span>");
    
                        anime.timeline({loop: true})
                        .add({
                            targets: '.ml3 .letter',
                            opacity: [0,1],
                            easing: "easeInOutQuad",
                            duration: 2250,
                            delay: (el, i) => 150 * (i+1)
                        }).add({
                            targets: '.ml3',
                            opacity: 0,
                            duration: 1000,
                            easing: "easeOutExpo",
                            delay: 1000
                        });
                    }
                    
                    if(typeof(obj)!= "undefined" && obj.anim_type==4 || settings.anim_type==4){
                        var ml4 = {};
                        ml4.opacityIn = [0,1];
                        ml4.scaleIn = [0.2, 1];
                        ml4.scaleOut = 3;
                        ml4.durationIn = 800;
                        ml4.durationOut = 600;
                        ml4.delay = 500;
                        
                        anime.timeline({loop: true})
                        .add({
                            targets: '.ml4 .letters-1',
                            opacity: ml4.opacityIn,
                            scale: ml4.scaleIn,
                            duration: ml4.durationIn
                        }).add({
                            targets: '.ml4 .letters-1',
                            opacity: 0,
                            scale: ml4.scaleOut,
                            duration: ml4.durationOut,
                            easing: "easeInExpo",
                            delay: ml4.delay
                        }).add({
                            targets: '.ml4 .letters-2',
                            opacity: ml4.opacityIn,
                            scale: ml4.scaleIn,
                            duration: ml4.durationIn
                        }).add({
                            targets: '.ml4 .letters-2',
                            opacity: 0,
                            scale: ml4.scaleOut,
                            duration: ml4.durationOut,
                            easing: "easeInExpo",
                            delay: ml4.delay
                        }).add({
                            targets: '.ml4 .letters-3',
                            opacity: ml4.opacityIn,
                            scale: ml4.scaleIn,
                            duration: ml4.durationIn
                        }).add({
                            targets: '.ml4 .letters-3',
                            opacity: 0,
                            scale: ml4.scaleOut,
                            duration: ml4.durationOut,
                            easing: "easeInExpo",
                            delay: ml4.delay
                        }).add({
                            targets: '.ml4',
                            opacity: 0,
                            duration: 500,
                            delay: 500
                        });
                    }
    
                    if(typeof(obj)!= "undefined"&&  obj.anim_type==5 || settings.anim_type==5){
                        anime.timeline({loop: true})
                        .add({
                        targets: '.ml5 .line',
                        opacity: [0.5,1],
                        scaleX: [0, 1],
                        easing: "easeInOutExpo",
                        duration: 700
                        }).add({
                        targets: '.ml5 .line',
                        duration: 600,
                        easing: "easeOutExpo",
                        translateY: (el, i) => (-0.625 + 0.625*2*i) + "em"
                        }).add({
                        targets: '.ml5 .ampersand',
                        opacity: [0,1],
                        scaleY: [0.5, 1],
                        easing: "easeOutExpo",
                        duration: 600,
                        offset: '-=600'
                        }).add({
                        targets: '.ml5 .letters-left',
                        opacity: [0,1],
                        translateX: ["0.5em", 0],
                        easing: "easeOutExpo",
                        duration: 600,
                        offset: '-=300'
                        }).add({
                        targets: '.ml5 .letters-right',
                        opacity: [0,1],
                        translateX: ["-0.5em", 0],
                        easing: "easeOutExpo",
                        duration: 600,
                        offset: '-=600'
                        }).add({
                        targets: '.ml5',
                        opacity: 0,
                        duration: 1000,
                        easing: "easeOutExpo",
                        delay: 1000
                        });
                    }
    
                    if(typeof(obj)!= "undefined"&&  obj.anim_type==6 || settings.anim_type==6){
                        // Wrap every letter in a span
                        var textWrapper = document.querySelector('.ml6 .letters');
                        textWrapper.innerHTML = textWrapper.textContent.replace(/\S/g, "<span class='letter'>$&</span>");
    
                        anime.timeline({loop: true})
                        .add({
                            targets: '.ml6 .letter',
                            translateY: ["1.1em", 0],
                            translateZ: 0,
                            duration: 750,
                            delay: (el, i) => 50 * i
                        }).add({
                            targets: '.ml6',
                            opacity: 0,
                            duration: 1000,
                            easing: "easeOutExpo",
                            delay: 1000
                        });
                    }
    
                    if(typeof(obj)!= "undefined" &&  obj.anim_type==7 || settings.anim_type==7){
                        var textWrapper = document.querySelector('.ml7 .letters');
                        textWrapper.innerHTML = textWrapper.textContent.replace(/\S/g, "<span class='letter'>$&</span>");
    
                        anime.timeline({loop: true})
                        .add({
                            targets: '.ml7 .letter',
                            translateY: ["1.1em", 0],
                            translateX: ["0.55em", 0],
                            translateZ: 0,
                            rotateZ: [180, 0],
                            duration: 750,
                            easing: "easeOutExpo",
                            delay: (el, i) => 50 * i
                        }).add({
                            targets: '.ml7',
                            opacity: 0,
                            duration: 1000,
                            easing: "easeOutExpo",
                            delay: 1000
                        });
                    }
                    
                    if(typeof(obj)!= "undefined" &&  obj.anim_type==8 || settings.anim_type==8){
                        anime.timeline({loop: true})
                            .add({
                                targets: '.ml8 .circle-white',
                                scale: [0, 3],
                                opacity: [1, 0],
                                easing: "easeInOutExpo",
                                rotateZ: 360,
                                duration: 1100
                            }).add({
                                targets: '.ml8 .circle-container',
                                scale: [0, 1],
                                duration: 1100,
                                easing: "easeInOutExpo",
                                offset: '-=1000'
                            }).add({
                                targets: '.ml8 .circle-dark',
                                scale: [0, 1],
                                duration: 1100,
                                easing: "easeOutExpo",
                                offset: '-=600'
                            }).add({
                                targets: '.ml8 .letters-left',
                                scale: [0, 1],
                                duration: 1200,
                                offset: '-=550'
                            }).add({
                                targets: '.ml8 .bang',
                                scale: [0, 1],
                                rotateZ: [45, 15],
                                duration: 1200,
                                offset: '-=1000'
                            }).add({
                                targets: '.ml8',
                                opacity: 0,
                                duration: 1000,
                                easing: "easeOutExpo",
                                delay: 1400
                            });
    
                            anime({
                            targets: '.ml8 .circle-dark-dashed',
                            rotateZ: 360,
                            duration: 8000,
                            easing: "linear",
                            loop: true
                            });
                    }
    
                    if(typeof(obj)!= "undefined" &&  obj.anim_type==9 || settings.anim_type==9){
                        // Wrap every letter in a span
                        var textWrapper = document.querySelector('.ml9 .letters');
                        textWrapper.innerHTML = textWrapper.textContent.replace(/\S/g, "<span class='letter'>$&</span>");
    
                        anime.timeline({loop: true})
                        .add({
                            targets: '.ml9 .letter',
                            scale: [0, 1],
                            duration: 1500,
                            elasticity: 600,
                            delay: (el, i) => 45 * (i+1)
                        }).add({
                            targets: '.ml9',
                            opacity: 0,
                            duration: 1000,
                            easing: "easeOutExpo",
                            delay: 1000
                        });
                    }
    
                    if(typeof(obj)!= "undefined" &&  obj.anim_type==10 || settings.anim_type==10){
                    // Wrap every letter in a span
                        var textWrapper = document.querySelector('.ml10 .letters');
                        textWrapper.innerHTML = textWrapper.textContent.replace(/\S/g, "<span class='letter'>$&</span>");
    
                        anime.timeline({loop: true})
                        .add({
                            targets: '.ml10 .letter',
                            rotateY: [-90, 0],
                            duration: 1300,
                            delay: (el, i) => 45 * i
                        }).add({
                            targets: '.ml10',
                            opacity: 0,
                            duration: 1000,
                            easing: "easeOutExpo",
                            delay: 1000
                        });
                    }
    
                    if(typeof(obj)!= "undefined" &&  obj.anim_type==11 || settings.anim_type==11){
                    // Wrap every letter in a span
                        var textWrapper = document.querySelector('.ml11 .letters');
                        textWrapper.innerHTML = textWrapper.textContent.replace(/([^\x00-\x80]|\w)/g, "<span class='letter'>$&</span>");
    
                        anime.timeline({loop: true})
                        .add({
                            targets: '.ml11 .line',
                            scaleY: [0,1],
                            opacity: [0.5,1],
                            easing: "easeOutExpo",
                            duration: 700
                        })
                        .add({
                            targets: '.ml11 .line',
                            translateX: [0, document.querySelector('.ml11 .letters').getBoundingClientRect().width + 10],
                            easing: "easeOutExpo",
                            duration: 700,
                            delay: 100
                        }).add({
                            targets: '.ml11 .letter',
                            opacity: [0,1],
                            easing: "easeOutExpo",
                            duration: 600,
                            offset: '-=775',
                            delay: (el, i) => 34 * (i+1)
                        }).add({
                            targets: '.ml11',
                            opacity: 0,
                            duration: 1000,
                            easing: "easeOutExpo",
                            delay: 1000
                        });
                    }
    
                    if(typeof(obj)!= "undefined" &&  obj.anim_type==12 || settings.anim_type==12){
                    // Wrap every letter in a span
                        var textWrapper = document.querySelector('.ml12');
                        textWrapper.innerHTML = textWrapper.textContent.replace(/\S/g, "<span class='letter'>$&</span>");
    
                        anime.timeline({loop: true})
                        .add({
                            targets: '.ml12 .letter',
                            translateX: [40,0],
                            translateZ: 0,
                            opacity: [0,1],
                            easing: "easeOutExpo",
                            duration: 1200,
                            delay: (el, i) => 500 + 30 * i
                        }).add({
                            targets: '.ml12 .letter',
                            translateX: [0,-30],
                            opacity: [1,0],
                            easing: "easeInExpo",
                            duration: 1100,
                            delay: (el, i) => 100 + 30 * i
                        });
                    }
    
                    if(typeof(obj)!= "undefined" &&  obj.anim_type==13 || settings.anim_type==13){
                    // Wrap every letter in a span
                        var textWrapper = document.querySelector('.ml13');
                        textWrapper.innerHTML = textWrapper.textContent.replace(/\S/g, "<span class='letter'>$&</span>");
    
                        anime.timeline({loop: true})
                        .add({
                            targets: '.ml13 .letter',
                            translateY: [100,0],
                            translateZ: 0,
                            opacity: [0,1],
                            easing: "easeOutExpo",
                            duration: 1400,
                            delay: (el, i) => 300 + 30 * i
                        }).add({
                            targets: '.ml13 .letter',
                            translateY: [0,-100],
                            opacity: [1,0],
                            easing: "easeInExpo",
                            duration: 1200,
                            delay: (el, i) => 100 + 30 * i
                        });
                    }
                    
                    if(typeof(obj)!= "undefined" &&  obj.anim_type==14 || settings.anim_type==14){
                        // Wrap every letter in a span
                        var textWrapper = document.querySelector('.ml14 .letters');
                        textWrapper.innerHTML = textWrapper.textContent.replace(/\S/g, "<span class='letter'>$&</span>");
    
                        anime.timeline({loop: true})
                        .add({
                            targets: '.ml14 .line',
                            scaleX: [0,1],
                            opacity: [0.5,1],
                            easing: "easeInOutExpo",
                            duration: 900
                        }).add({
                            targets: '.ml14 .letter',
                            opacity: [0,1],
                            translateX: [40,0],
                            translateZ: 0,
                            scaleX: [0.3, 1],
                            easing: "easeOutExpo",
                            duration: 800,
                            offset: '-=600',
                            delay: (el, i) => 150 + 25 * i
                        }).add({
                            targets: '.ml14',
                            opacity: 0,
                            duration: 1000,
                            easing: "easeOutExpo",
                            delay: 1000
                        });
                    }
    
                    if(typeof(obj)!= "undefined" &&  obj.anim_type==15 || settings.anim_type==15){
                        anime.timeline({loop: true})
                        .add({
                            targets: '.ml15 .word',
                            scale: [14,1],
                            opacity: [0,1],
                            easing: "easeOutCirc",
                            duration: 800,
                            delay: (el, i) => 800 * i
                        }).add({
                            targets: '.ml15',
                            opacity: 0,
                            duration: 1000,
                            easing: "easeOutExpo",
                            delay: 1000
                        });
                    }
    
                    if(typeof(obj)!= "undefined" &&  obj.anim_type==16 || settings.anim_type==16){
                      // Wrap every letter in a span
                        var textWrapper = document.querySelector('.ml16');
                        textWrapper.innerHTML = textWrapper.textContent.replace(/\S/g, "<span class='letter'>$&</span>");
    
                        anime.timeline({loop: true})
                        .add({
                            targets: '.ml16 .letter',
                            translateY: [-100,0],
                            easing: "easeOutExpo",
                            duration: 1400,
                            delay: (el, i) => 30 * i
                        }).add({
                            targets: '.ml16',
                            opacity: 0,
                            duration: 1000,
                            easing: "easeOutExpo",
                            delay: 1000
                        });
                    }
            }
        });        
    
    });
    
    } (jQuery, window));