let a = prompt("Masukkan bilangan ke-1:");
let b = prompt("Masukkan bilangan ke-2:");

a = Number(a);
b = Number(b);

if (a > b) {
  alert("BILANGAN KE-1 LEBIH BESAR DARI BILANGAN KE-2");
} else if (a < b) {
  alert("BILANGAN KE-1 LEBIH KECIL DARI BILANGAN KE-2");
} else {
  alert("BILANGAN KE-1 SAMA DENGAN BILANGAN KE-2");
}