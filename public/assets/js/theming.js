// Theaming
// $('#default').click(function(){
//   $("head").append("<link>");
//     css = $("head").children(":last");
//     css.attr({ 
//       rel:  "stylesheet",
//       type: "text/css",
//       href: window.location.protocol + "//" + window.location.host + "/asset/backend-template/css/main.css"
//   });
// })

// $('#whitesmoke').click(function(){
//   $("head").append("<link>");
//     css = $("head").children(":last");
//     css.attr({ 
//       rel:  "stylesheet",
//       type: "text/css",
//       href: window.location.protocol + "//" + window.location.host + "/asset/backend-template/css/whitesmoke.css"
//   });
// })

// $('#facebook').click(function(){
//   $("head").append("<link>");
//     css = $("head").children(":last");
//     css.attr({ 
//       rel:  "stylesheet",
//       type: "text/css",
//       href: window.location.protocol + "//" + window.location.host + "/asset/backend-template/css/facebook.css"
//   });
// })

// $('#tumblr').click(function(){
//   $("head").append("<link>");
//     css = $("head").children(":last");
//     css.attr({ 
//       rel:  "stylesheet",
//       type: "text/css",
//       href: window.location.protocol + "//" + window.location.host + "/asset/backend-template/css/tumblr.css"
//   });
// })

// $('#flickr').click(function(){
//   $("head").append("<link>");
//     css = $("head").children(":last");
//     css.attr({ 
//       rel:  "stylesheet",
//       type: "text/css",
//       href: window.location.protocol + "//" + window.location.host + "/asset/backend-template/css/flickr.css"
//   });
// })

// $('#foursquare').click(function(){
//   $("head").append("<link>");
//     css = $("head").children(":last");
//     css.attr({ 
//       rel:  "stylesheet",
//       type: "text/css",
//       href: window.location.protocol + "//" + window.location.host + "/asset/backend-template/css/foursquare.css"
//   });
// })

// $('#google-plus').click(function(){
//   $("head").append("<link>");
//     css = $("head").children(":last");
//     css.attr({ 
//       rel:  "stylesheet",
//       type: "text/css",
//       href: window.location.protocol + "//" + window.location.host + "/asset/backend-template/css/google-plus.css"
//   });
// })

// $('#instagram').click(function(){
//   $("head").append("<link>");
//     css = $("head").children(":last");
//     css.attr({ 
//       rel:  "stylesheet",
//       type: "text/css",
//       href: window.location.protocol + "//" + window.location.host + "/asset/backend-template/css/instagram.css"
//   });
// })

// $('#linkedin').click(function(){
//   $("head").append("<link>");
//     css = $("head").children(":last");
//     css.attr({ 
//       rel:  "stylesheet",
//       type: "text/css",
//       href: window.location.protocol + "//" + window.location.host + "/asset/backend-template/css/linkedin.css"
//   });
// })

// $('#twitter').click(function(){
//   $("head").append("<link>");
//     css = $("head").children(":last");
//     css.attr({ 
//       rel:  "stylesheet",
//       type: "text/css",
//       href: window.location.protocol + "//" + window.location.host + "/asset/backend-template/css/twitter.css"
//   });
// })

// $('#youtube').click(function(){
//   $("head").append("<link>");
//     css = $("head").children(":last");
//     css.attr({ 
//       rel:  "stylesheet",
//       type: "text/css",
//       href: window.location.protocol + "//" + window.location.host + "/asset/backend-template/css/youtube.css"
//   });
// })

// $('#vimeo').click(function(){
//   $("head").append("<link>");
//     css = $("head").children(":last");
//     css.attr({ 
//       rel:  "stylesheet",
//       type: "text/css",
//       href: window.location.protocol + "//" + window.location.host + "/asset/backend-template/css/vimeo.css"
//   });
// })

// $('#pinterest').click(function(){
//   $("head").append("<link>");
//     css = $("head").children(":last");
//     css.attr({ 
//       rel:  "stylesheet",
//       type: "text/css",
//       href: window.location.protocol + "//" + window.location.host + "/asset/backend-template/css/pinterest.css"
//   });
// })

// $('#grey').click(function(){
//   $("head").append("<link>");
//     css = $("head").children(":last");
//     css.attr({ 
//       rel:  "stylesheet",
//       type: "text/css",
//       href: window.location.protocol + "//" + window.location.host + "/asset/backend-template/css/grey.css"
//   });
// })

// $(function(){
//   $("head").append("<link>");
//     css = $("head").children(":last");
//     css.attr({ 
//       rel:  "stylesheet",
//       type: "text/css",
//       href: window.location.protocol + "//" + window.location.host + "/asset/backend-template/css/main.css"
//   });
// })


  $(document).ready(function(){

      $('.theme').click(function(){
          var theme_uri = $(this).attr('id');
          $.ajax({
            url:window.location.origin+"/ajax/changeTheme/"+theme_uri,
            cache:false,
            success:function(msg){
                if(msg=='success'){

                    location.reload();

                }else{
                    alert("Failed Change Theme");
                }
            }
        });

      });

      var theme = $("#theme").val();

      $("head").append("<link>");
        css = $("head").children(":last");
        css.attr({ 
          rel:  "stylesheet",
          type: "text/css",
          href: window.location.protocol + "//" + window.location.host + "/asset/backend-template/css/"+theme+".css"
      });
  });