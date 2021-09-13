$(function(){
  favBtn();
  proffavBtn();
});

function favBtn(){
  $(".favorite_btn").on('click',function(){
    var $this = $(this);
    let fav = $this.data('favid');

    $.ajax({
      type:'POST',
      url:'ajax_favbtn.php',
      data:{
        favId: fav.favId,
        user_id: fav.user_id
      },
    }).done(function(data){
        $this.toggleClass('gray_btn');

    }).fail(function(XMLHttpRequest, textStatus, errorThrown){
      alert("Ajax error write");
      console.log("XMLHttpRequest : " + XMLHttpRequest.status);
　　    console.log("textStatus     : " + textStatus);
　　  console.log("errorThrown    : " + errorThrown.message);
      return false;
    });

  })
}

function proffavBtn(){
  $(".userprof_fav").on("click",function(e){
    e.preventDefault();

    var $this = $(this);
    var favId = $("#favId").val();
    var user_id = $("#user_id").val();

    $.ajax({
      type:'POST',
      url:'ajax_favbtn.php',
      data:{
        favId: favId,
        user_id: user_id
      },
    }).done(function(data){
      if (data=='del') {
        $this.removeClass('prof_favorite_btn');
        $this.addClass('gray_btn');
      }else{
        $this.removeClass('gray_btn');
        $this.addClass('prof_favorite_btn');
      }

    }).fail(function(XMLHttpRequest, textStatus, errorThrown){
      alert("Ajax error write");
      console.log("XMLHttpRequest : " + XMLHttpRequest.status);
　　    console.log("textStatus     : " + textStatus);
　　  console.log("errorThrown    : " + errorThrown.message);
      return false;
    });

  })
}
