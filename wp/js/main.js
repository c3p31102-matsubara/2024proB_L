var API_URL = "http://localhost/probc/c_json.php";

function get_fitem(tag_id, key_id){
  $.getJSON(API_URL, {type:"json", data:"find_item"}, function(j){
    var src = "<table class='table table-success table-striped'><tr><th>大分類</th><th>物品名</th><th>拾得場所</th><th>色</th><th>特徴</th><th>日時</th><th>状態</th><th>操作</th></tr>";
    cnt=0;
    key=$("#"+key_id).val();
    $.each(j, function(i,v){
      if(v["物品名"].indexOf(key)>-1){
        src += "<tr>";
        src += "<td>" + v["大分類"] + "</td>";
        src += "<td>" + v["物品名"] + "</td>";
        src += "<td>" + v["拾得場所"] + "</td>";
        src += "<td>" + v["色"] + "</td>";
        src += "<td>" + v["特徴"] + "</td>";
        src += "<td>" + v["変更日時"] + "</td>";
        src += "<td>" + v["変更内容"] + "</td>";
        src += "<td><a href='v_fitem_detail.php?id=" + v["拾得物ID"] + "' class='btn btn-success'>詳細</a></td>";
        src += "</tr>";
        cnt++;
      }
    });
    src += "</table>";
    src = "検索結果：" + cnt + "件" + src;
    $("#"+tag_id).html(src);
  }).fail(function(e){ console.log(e); });
}

function get_fitem_detail(tag_ids, fid){
  $.getJSON(API_URL, {type:"json", data:"find_item_detail", fid:fid}, function(j){
    var src = "<table class='table table-success'>";
    cnt=0;
    src += "<tr><td>スクリプトで文字を書き出します（１）</td></tr>";
    src += "</table>";
    var src2 = "<table class='table table-success'>";
    $.each(j["変更履歴"], function(i,v){
      src2 += "<tr>";
      src2 += "<td>スクリプトで文字を書き出します（２）</td>";
      src2 += "</tr>";
    });
    src2 += "</table>";
    $("#"+tag_ids[0]).html(src);
    $("#"+tag_ids[1]).html(src2);
  }).fail(function(e){ console.log(e); });
}