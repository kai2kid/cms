function menuSelect(page,act,param) {
  var target = '#wrapper_content';
  $.ajaxSetup({async:false});
  $.get('_app/_ajaxPage/' + page + '.php', {'act':act , 'param':param} , function(data) {
    if (data.result==1) {
      $(target).html("");
      $(target).html("<style>" + data.style + "</style>" + data.html);
      $(target).show();
      eval(data.script);
    }    
  }, "json");
  $.ajaxSetup({async:true});
}

function formSubmit(form,path) {
  $.ajaxSetup({async:false});
  $.post(path, {'param':$('#'+form).serialize()} , function(data) {
    if (data.result==1) {
      eval(data.script);
    }    
  }, "json");
  $.ajaxSetup({async:true});
}
