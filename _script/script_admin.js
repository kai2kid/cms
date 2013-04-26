function showUploadedImage(id,src) {
  $("#"+id).attr('src',"");
  $("#"+id).attr('src',src);
  $("#"+id).show();
}