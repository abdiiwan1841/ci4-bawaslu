//Tooltip
$("a").tooltip("hide");

//Popover
$(".popover-pop").popover("hide");

//Collapse
$("#myCollapsible").collapse({
  toggle: false,
});

//Dropdown
$(".dropdown-toggle").dropdown();

// Retina Mode
function retina() {
  retinaMode = window.devicePixelRatio > 1;
  return retinaMode;
}

// $(document).ready(function () {
//   pieCharts();
// });

// function pieCharts() {
//   $(function () {
//     //create instance
//     $('.pie_chart_1').easyPieChart({
//       animate: 2000,
//       barColor: '#74b749',
//       trackColor: '#dddddd',
//       scaleColor: '#74b749',
//       size: 180,
//       lineWidth: 8,
//     });
//     //update instance after 5 sec
//     setTimeout(function () {
//       $('.pie_chart_1').data('easyPieChart').update(69);
//     }, 5000);
//     setTimeout(function () {
//       $('.pie_chart_1').data('easyPieChart').update(20);
//     }, 15000);
//     setTimeout(function () {
//       $('.pie_chart_1').data('easyPieChart').update(78);
//     }, 27000);
//     setTimeout(function () {
//       $('.pie_chart_1').data('easyPieChart').update(52);
//     }, 39000);
//     setTimeout(function () {
//       $('.pie_chart_1').data('easyPieChart').update(89);
//     }, 45000a//   });
// }

//Resize charts and graphs on window resize
// $(document).ready(function () {
//   $(window).resize(function(){
//     pieCharts();
//   });
// });

//Tiny Scrollbar
// $('#scrollbar-three').tinyscrollbar();

//Data Tables
$(document).ready(function () {
  // $("#data-table, #data-table1").dataTable({
  //   sPaginationType: "full_numbers",
  // });
  // $("#data-table_length, #data-table1_length").css("float", "right");
  //Date picker
  // $(".date_picker").daterangepicker({
  //   singleDatePicker: true,
  //   showDropdowns: true,
  //   locale: {
  //     format: "YYYY-MM-DD",
  //   },
  // });
  // $(".date_picker").keypress(function (e) {
  //   return false;
  // });
});

$(document).ready(function () {
  // $(".tarkiman").keypress(function (e) {
  //   if (
  //     e.which != 8 &&
  //     e.which != 0 &&
  //     (e.which < 48 || e.which > 57) &&
  //     e.which != 46
  //   ) {
  //     return false;
  //   }
  //   var number = $(this).val();
  //   var cekJumlahTitik = (number.match(/\./g) || []).length;
  //   if (cekJumlahTitik >= 1 && e.which == 46) {
  //     return false;
  //   }
  // });
  // $(".tarkiman").keyup(function (e) {
  //   if ($(this).val() != "") {
  //     var number = $(this).val();
  //     if (!number.includes(".")) {
  //       var newNumber = parseFloat(number.replace(/,/g, ""));
  //       $(this).val(newNumber.toLocaleString());
  //     }
  //   }
  // });
  // $("form").submit(function () {
  //   $(".tarkiman").each(function () {
  //     var thisNumber = $(this).val();
  //     if (thisNumber != "") {
  //       var newNumber = parseFloat(thisNumber.replace(/,/g, ""));
  //       $(this).val(newNumber);
  //     }
  //   });
  // });
  // $(".tarkiman").each(function () {
  //   var thisNumber = $(this).val();
  //   if (thisNumber != "") {
  //     var newNumber = parseFloat(thisNumber.replace(/,/g, ""));
  //     $(this).val(newNumber.toLocaleString());
  //   }
  // });
});

// Notifikasi
function notif(message) {
  var notification = new NotificationFx({
    message:
      '<div class="ns-content"><p><span style=color:#e8ed12;>' +
      message +
      "</span></p></div>",
    layout: "growl",
    effect: "genie",
    type: "notice", // notice, warning or error
    onClose: function () {},
  });
  notification.show();
}
// Notifikasi

$(document).ready(function () {
  $("#full_screen").click(function () {
    FullScreen();
  });

  function FullScreen() {
    if (
      !document.fullscreenElement && // alternative standard method
      !document.mozFullScreenElement &&
      !document.webkitFullscreenElement &&
      !document.msFullscreenElement
    ) {
      // current working methods
      if (document.documentElement.requestFullscreen) {
        document.documentElement.requestFullscreen();
      } else if (document.documentElement.msRequestFullscreen) {
        document.documentElement.msRequestFullscreen();
      } else if (document.documentElement.mozRequestFullScreen) {
        document.documentElement.mozRequestFullScreen();
      } else if (document.documentElement.webkitRequestFullscreen) {
        document.documentElement.webkitRequestFullscreen(
          Element.ALLOW_KEYBOARD_INPUT
        );
      }
    } else {
      if (document.exitFullscreen) {
        document.exitFullscreen();
      } else if (document.msExitFullscreen) {
        document.msExitFullscreen();
      } else if (document.mozCancelFullScreen) {
        document.mozCancelFullScreen();
      } else if (document.webkitExitFullscreen) {
        document.webkitExitFullscreen();
      }
    }
  }
});
