// メッセージの受信
function ReadTalkMessage(){
  let bord_id = $("#bord_id").val();
  let user_id = $("#user_id").val();
  var length = $("#length").val();
  var view_name =$("#view_name").val();
  var talkername=$("[name='talkername']").val();

  if (length=='') {
    var length=0;
  }
  if (talkername=="") {
    var talkername=0;
  }

    $.ajax({
      type:'POST',
      url:'/users/ajax_talkspace.php',
      data:{
        bord_id: bord_id,
        user_id: user_id,
        view_name: view_name,
        talkername: talkername,
        action: 'get'
      },
      dataType: "json"
    }).done(function(data){
      if (data.empty==0) {
        $bord.html('<p class="error center">(!) 相談メッセージを待っています</p>');
      }
      // 最後のreplaceは改行コードを<br>にする
      var xss = function xss(str){
        return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#x27').replace(/\r?\n/g, '<br />');
      };
      let $bord = $(".bord");

      if (data.finish!=0) {
        $bord.html('<p class="error center">(!) このTALKSPACEは相談受付を終了しております。</p>');
        $(".sendmessage_btn").prop("disabled",true).addClass('none');
        $("#textmessage").prop("disabled",true);
      }
      // 現在のメッセージ件数と表示されている件数の比較
      // 表示件数が少なかったら
      else if (length < data.length) {
        let count = data.length-length;
        var scrollposition = $bord[0].scrollHeight;

        if(length==0){
        // 0始まりなのでDBのメッセージ件数-1
        for (var i=0; i<=count-1; i++) {
          var talkerflame="";
            talkerflame+="<div class='talker'>";
            talkerflame+="<p class='nickname'>" + xss(data[i].talker) + "</p>";
            talkerflame+="<div class='ballon ballon_left'>" + xss(data[i].message) + "</div>";
            talkerflame+="</div>";
          var staffflame="";
            staffflame+="<div class='staff'>";
            staffflame+="<p class='nickname'>" + xss(data[i].staff) + "</p>";
            staffflame+="<div class='ballon ballon_right'>" + xss(data[i].message) + "</div>";
            staffflame+="</div>";

            if (data[i].user_id==data[i].talkerId) {
                $bord.append(talkerflame);
            } else if(data[i].user_id==data[i].staffId) {
              $bord.append(staffflame);
            }else{
              $bord.append('<p class="error center">(!) 障害が発生しました</p>');
            }
            }

          }else{
            for (var i=length; i<=data.length-1; i++) {
              var talkerflame="";
                talkerflame+="<div class='talker'>";
                talkerflame+="<p class='nickname'>" + xss(data[i].talker) + "</p>";
                talkerflame+="<div class='ballon ballon_left'>" + xss(data[i].message) + "</div>";
                talkerflame+="</div>";
              var staffflame="";
                staffflame+="<div class='staff'>";
                staffflame+="<p class='nickname'>" + xss(data[i].staff) + "</p>";
                staffflame+="<div class='ballon ballon_right'>" + xss(data[i].message) + "</div>";
                staffflame+="</div>";

                if (data[i].user_id==data[i].talkerId) {
                    $bord.append(talkerflame);
                } else if(data[i].user_id==data[i].staffId) {
                  $bord.append(staffflame);
                }else{
                  $bord.append('<p class="error center">(!) 障害が発生しました</p>');
                }
            }

          }
    }
    $("#length").val(data.length);

    var scrollposition = $bord[0].scrollHeight;
    if (length==0) {
      $bord.scrollTop(scrollposition);
    }else{
      for (var i=length; i<=data.length-1; i++) {
        if (data[i].user_id==data[i].staffId) {
          $bord.scrollTop(scrollposition);
        }
      }
    }

    }).fail(function(XMLHttpRequest, textStatus, errorThrown){
      alert("errorThrown");
      console.log("XMLHttpRequest : " + XMLHttpRequest.status);
　　    console.log("textStatus     : " + textStatus);
　　  console.log("errorThrown    : " + errorThrown.message);
      return false;
    });

}


// メッセージをDBへ送信
function WriteTalkMessage(){
  let empty = $("#textmessage").val();
  let errormessage = "　(!) メッセージを入力してください";
  let formData = $("#message_form").serialize();
  let decofontData = decodeURI(formData);
  // alert(decofontData);

  if (empty=="") {
    $("#errormessage").append(errormessage);
  }else {
    $.ajax({
      type:'POST',
      url:'/users/ajax_talkspace.php',
      data:decofontData,
    }).done(function(data){
      ReadTalkMessage();
      $("#textmessage").val('');
    }).fail(function(XMLHttpRequest, textStatus, errorThrown){
      alert("Ajax error write");
      console.log("XMLHttpRequest : " + XMLHttpRequest.status);
　　    console.log("textStatus     : " + textStatus);
　　  console.log("errorThrown    : " + errorThrown.message);
      return false;
    });
  }

}

$(document).ready(function() {
    ReadTalkMessage();
    setInterval('ReadTalkMessage()', 3000);
});
