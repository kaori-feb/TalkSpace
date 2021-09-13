$(function(){
  smooth();
  search_freetime();
  imgpopup();
  imgpreview();
  wordCut20();
  wordCut10();
  dateSwitch();
  foreigninputchange();
  endtime();
  paswdshow();
  roleCheck();
  popup();
});

function popup(){
  $(".admin_delbtn").on("click",function(){
    let del_id = $(this).prev().val();
    $("span.del_id").html(del_id);
    $(".del_id").val(del_id);
    $('.popup').fadeIn();
  });
  $('.js-popup_close').on('click',function(){
    $('.popup').fadeOut();
    return false;
  });
}


// スタッフ検索対応可能時間NULL対応
function search_freetime(){
  $("[name='search_freetime']").on('change',function(){
    let freetime_val=$("[name='search_freetime']").val();
    $("[name='staff_free_time']").val(freetime_val);
  })
}

// ユーザー情報登録更新画面
// 国外出身居住操作
function foreigninputchange(){
  var birth_val = $('[name="birth_place"]').val();
  var living_val = $('[name="living_place"]').val();

  if(birth_val == 'その他'){
    $('[name="birth_place2"]').prop('disabled',false);
  }

  if(living_val == 'その他'){
    $('[name="living_place2"]').prop('disabled',false);
  }

  $('[name="birth_place"]').on('change',function(){
    var birth_val = $(this).val();

    if(birth_val == 'その他'){
      $('[name="birth_place2"]').prop('disabled',false);
    }else{
      $('[name="birth_place2"]').prop('disabled',true);
    }
  });
  $('[name="living_place"]').on('change',function(){
    var living_val = $(this).val();

    if(living_val == 'その他'){
      $('[name="living_place2"]').prop('disabled',false);
    }else{
      $('[name="living_place2"]').prop('disabled',true);
    }
  });
}
// 対応時間の表示有無
function roleCheck(){
  $('input:radio[name="role_id"]').on('change',function(){
    var role_id = $('input:radio[name="role_id"]:checked').val();
    if (role_id != 3) {
      $('.adminCheck').fadeIn();
    }else {
      $('.adminCheck').fadeOut();
    }
    if(role_id == 2){
      $('.staffCheck').fadeIn();
    }else{
      $('.staffCheck').fadeOut();
    }
  })
  // 管理者
  var back_id = $('input:radio[name="role_id"]:checked').val();
  if (back_id==2) {
    $(".staffCheck").removeClass('staffCheck');
  }

  // 管理者以外
  var role_id = $('[name="role_id"]').val();
  if(role_id == 2){
    $('.staffCheck').fadeIn();
  }else{
    $('.staffCheck').fadeOut();
  }
  if (role_id != 3) {
    $('.adminCheck').fadeIn();
  }else {
    $('.adminCheck').fadeOut();
  }
}
// 対応可能時間の終了時間
function endtime(){
  $('[name="free_time1"]').on('change',function(){
    let time1 = parseInt($('[name="free_time1"]').val());
    let endtime1 = time1 +1;

    if(endtime1 < 10){
      $('.endtime1').text('0'+endtime1);
    }else if(endtime1 == 25){
      $('.endtime1').text('01');
    }else{
      $('.endtime1').text(endtime1);
    }
  })

  $('[name="free_time2"]').on('change',function(){
    let time2 = parseInt($('[name="free_time2"]').val());
    let endtime2 = time2 +1;

    if(endtime2 < 10){
      $('.endtime2').text('0'+endtime2);
    }else if(endtime2 == 25){
      $('.endtime2').text('01');
    }else{
      $('.endtime2').text(endtime2);
    }
  })

}

// パスワードeye
function paswdshow(){
  $(".toggle_pass").on('click',function(){
    // アイコンの切り替え
    $(this).toggleClass("eye eye-off");
    // 入力フォームの取得
    let input = $(this).prev("input");
    // type切替
    if (input.attr("type") == "password") {
        input.attr("type", "text");
    } else {
        input.attr("type", "password");
    }
  })
}

// その他の画像ポップアップ
function imgpopup(){
  $(".imgchoice").on("click",function(){
    $(".imgpop").fadeIn();

    $(".js-popup_close").on('click',function(){
      $(".imgpop").fadeOut();
      return false;
    })
    $(".cancel").on('click',function(){
      $(".imgpop").fadeOut();
      return false;
    })
  })
}

// 画像プレビュー
function imgpreview(){
  let $img_path=$(".tempimg").val();
  let path=$("#preview").attr('src');
  if ($img_path=='') {
    $(".tempimg").attr('value', path);
  }
  $(".imgpop_container img").on("click",function(){
    let src = $(this).attr("src");

    $("#preview").attr('src', src);
    $("[name='userimg']").val('');
    $(".tempimg").attr('value', src);
    $(".imgpop").fadeOut();
    return false;
  })
}

// スムーススクロール
function smooth(){
  $("a[href^='#']").on('click', function(){
    var speed = 500;
    var href = $(this).attr('href');
    var target = $(href == "#" || href == "" ? 'html' : href);
    var position = target.offset().top;
    $("html, body").animate({scrollTop:position}, speed, "swing");
return false;
});
};

function wordCut20(){
  var cutLimit = 20;
  var afterText = '//';
  $('.text_limit').each(function(){
    var textLength = $(this).text().length;
    var textTrim = $(this).text().substr(0,(cutLimit));

    if(textLength > cutLimit){
      $(this).html(textTrim + afterText).css({visibility:'visible'});
    }else if(cutLimit >= textLength){
      $(this).css({visibility:'visible'});
    }

  })
}

function wordCut10(){
  var cutLimit = 10;
  var afterText = '//';
  $('.name_limit').each(function(){
    var textLength = $(this).text().length;
    var textTrim = $(this).text().substr(0,(cutLimit));

    if(textLength > cutLimit){
      $(this).html(textTrim + afterText).css({visibility:'visible'});
    }else if(cutLimit >= textLength){
      $(this).css({visibility:'visible'});
    }

  })
}

function dateSwitch(){
  $("td.appoint_date_bg").on('click',function(){
    $("td.appoint_date_bg").removeClass('date_checked');
    $('[name="appoint_date"]').change(function(){
      $(this).parent().addClass('date_checked');
    })
  })
}
