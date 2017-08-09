function createModal(header, content) {
  if (header == "" || header == null) {
    var modal = $("<div class = 'modal'><span id = 'close'>&#10006;</span>"+content+"</div>");
    var filter = $("<div class = 'filter'></div>");
    filter.appendTo($("body")).css({
      position:"fixed",
      top:0,
      left:0,
      right:0,
      bottom:0,
      backgroundColor:"rgba(0,0,0,0.5)",
      display:"none"
    }).fadeIn().click(function() {
      $(".modal").remove();
      $(".filter").remove();
    });
    modal.appendTo($("body")).css({
      position:"fixed",
      top:"50%",
      left:"50%",
      transform: "translateY(-50%) translatex(-50%)",
      maxWidth:"1500px",
      mexHeight:"90%",
      overflow:"scroll",
      backgroundColor:"#fff",
      padding:"50px",
      fontSize:"24px",
      display:"none"
    }).fadeIn();
    $("#close").css({
      position:"absolute",
      top:"0",
      right:"0",
      display:"block",
      padding:"10px",
      cursor:"pointer"
    })
      .click(function() {
      $(".modal").remove();
      $(".filter").remove();
    });
  } else {
    var modal = $("<div class = 'modal'><span id = 'close'>&#10006;</span><h2>"+header+"</h2><div class = 'modal-content'>"+content+"</div></div>");
    var filter = $("<div class = 'filter'></div>");
    filter.appendTo($("body")).css({
      position:"fixed",
      top:0,
      left:0,
      right:0,
      bottom:0,
      backgroundColor:"rgba(0,0,0,0.5)",
      display:"none"
    }).fadeIn().click(function() {
      $(".modal").remove();
      $(".filter").remove();
    });
    modal.appendTo($("body")).css({
      position:"fixed",
      top:"50%",
      left:"50%",
      transform: "translateY(-50%) translatex(-50%)",
      maxWidth:"2000px",
      maxHeight:"90%",
      overflow:"scroll",
      backgroundColor:"#fff",
      padding:"30px",
      paddingTop:"70px",
      fontSize:"24px",
      display:"none"
    }).fadeIn();
    $(".modal>h2").css({
      position:"absolute",
      top:"0",
      left:"0",
      display:"block",
      padding:"15px",
      fontSize:"30px",
      margin:"0"
    })
    $("#close").css({
      position:"absolute",
      top:"0",
      right:"0",
      display:"block",
      padding:"10px",
      cursor:"pointer"
    })
      .click(function() {
      $(".modal").remove();
      $(".filter").remove();
    });
  }
}