$(function() {
  $(".icon").width($(".icon").height());
  $("#reset-code").keyup(function() {
    if ($(this).val() != "") {
      $("#reset-submit-btn").css("opacity","1");
    } else {
      $("#reset-submit-btn").css("opacity","0");
    }
  });
  $("#reset-submit-btn").click(function(){ 
    User.getResetCode($("#reset-code").val());
  });
});