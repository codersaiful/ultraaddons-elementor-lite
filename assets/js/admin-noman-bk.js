/**
     * Script for Widget List Filter on Dashboard
     * @author BM Rafiul Alam
     * @since 1.1.0.10
     */
   
 $('[data-category]').show();
 localStorage.removeItem('category');
 localStorage.removeItem('type');
 //Pro-Free 
 $('.widget-free-pro-list li').on('click', function(){
     $('.widget-free-pro-list li').removeClass('active');
     $(this).addClass('active');

     var $this= $(this).data('target');
     localStorage.setItem('type', $this);

     var $getCategory    = localStorage.getItem('category');
     var $getType        = localStorage.getItem('type');

     if($getCategory ){
         $('[data-category]').hide();
         $('[data-category="'+$getCategory+'"]').show();
         console.log("Category:  " + $getCategory);
     }
     if($getType=="pro" ){
         console.log("Type: " + $getType);
         $('[data-type="free"]').hide();
         $('[data-type="'+$getType+'"]').show();
    }
    if($getType=="free"){
         $('[data-type="free"]').show();
         $('[data-type="pro"]').hide();
    }
    if($getType=="pro" && $getCategory){
         console.log("Type: " + $getType + " " +  $getCategory);
         $('[data-type="free"]').hide();
        
     }
    if($this=="all"){
         $('[data-category]').show();
     }
     
  });

  //Category
  $('.widget-cat-list li').on('click', function(){
     $('.widget-cat-list li').removeClass('active');
     $(this).addClass('active');
     var $this= $(this).data('target');
     localStorage.setItem('category', $this);
     var $getCategory    = localStorage.getItem('category');
     var $getType        = localStorage.getItem('type');

     if($getCategory ){
         $('[data-category]').hide();
         $('[data-category="'+$getCategory+'"]').show();
         console.log("Category:  " + $getCategory);
     }
     if($getType=="pro" ){
         console.log("Type: " + $getType);
         $('[data-type="free"]').hide();
         $('[data-type="'+$getType+'"]').show();
    }
    if($getType=="free"){
        console.log("Type: " + $getType + " Category:  " + $getCategory);
    }
    if($this=="all"){
     $('[data-category]').show();
 }

 });