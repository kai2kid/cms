function jadwalDosen(kode,periode) {
  $.ajaxSetup({async:false});
  $.post('_app/_ajaxPage/a_jadwal.php', {'act':'ubah', 'dosen_kode':kode, 'periode_kode':$('#periode').val()} , function(data) {
    if (data.result==1) {
      $('#div_jadwal_dosen').html(data.html);
      eval(data.script);
    }    
  }, "json");
  $.ajaxSetup({async:true});
  
}

function showIzin(kode) {
  $.ajaxSetup({async:false});
  $.post('_app/_ajaxPage/a_izin.php', {'act':'beriIzin', 'karyawan_kode':kode} , function(data) {
    if (data.result==1) {
      $('#div_izin_karyawan').html(data.html);
      eval(data.script);
    }    
  }, "json");
  $.ajaxSetup({async:true});
}