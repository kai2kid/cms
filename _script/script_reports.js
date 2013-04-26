function reporting_jadwalDosen2(kode) {
  window.open('_app/reports.php?act=jadwal&kode_periode='+kode);
}
function reporting_personal(kode) {
  document.location = 'reports.php?act=kehadiran&kode_absensi='+kode;
}
function reporting_personal2(kode) {
  window.open('_app/reports.php?act=kehadiran&kode_absensi='+kode);
}
function reporting_tahunan(kode,tahun) {
  document.location = 'reports.php?act=kehadiran&kode_absensi='+kode+'&tahun='+tahun;
}
function reporting_bulanan(kode,tahun,bulan) {
  document.location = 'reports.php?act=kehadiran&kode_absensi='+kode+'&tahun='+tahun+'&bulan='+bulan;
}

function reporting_periodik(tahun,sort,filter) {
  window.open('_app/reports.php?act=kehadiran&periode='+tahun+'&sort='+sort+'&filter='+filter);
}
