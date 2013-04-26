function progressBox(id) {
  var o = $("#"+id);
  this.clear = function() {
    o.html('');
  }
  this.append = function(text) {
    o.html(text + o.html());
  }
  this.appendLn = function(text) {
    o.html(text + '\n' + o.html());
  }
  this.startProcess = function() {
//    this.append("123");
    $.ajaxSetup({async:false});
    $.get('_app/_ajaxPage/a_absensi.php', {'act':'getLastUpdate'} , function(data) {
      if (data.result==1) {
        eval(data.script);
        
      }    
    }, "json");
    $.get('_app/_ajaxPage/a_absensi.php', {'act':'startProcess'} , function(data) {
      if (data.result==1) {
//        o.html("<style>" + data.style + "</style>" + data.html);
        eval(data.script);
      }    
    }, "json");
    $.ajaxSetup({async:true});
  }
}