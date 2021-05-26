console.log("testtest");
var sort = $("#js-sort").find("button");
console.log(sort);
$(document).on("click", "#js-sort-priority, #js-sort-limit", function() {
  console.log("test");
  // var test = Math.round(Math.random() * 100);
  var test = $(this).attr("id");
  console.log(test);
  $.ajax({
      type: "POST",
      url: "./ajax.php",
      data: {
        ajax: test
      }
    })
    .done(function(res) {
      // let json = JSON.stringify(res, null, "	");
      $("#stage").empty();
      $("#stage").append(res);
      console.log("socces");
      console.log(res);
    })
    .fail(function() {
      console.log("error");
    })
});