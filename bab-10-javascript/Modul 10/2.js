let jawab = prompt("Apakah anda praktikan PPW1? (ya/tidak)");

if (jawab.toLowerCase() === "ya") {
  let nama = prompt("Masukkan Nama:");
  let nim = prompt("Masukkan NIM:");
  let angkatan = prompt("Masukkan Angkatan:");

  document.write(`
    <!DOCTYPE html>
    <html>
    <head>
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    </head>
    <body class="p-4">
      <table class="table table-bordered table-striped w-50">
        <thead class="table-dark">
          <tr>
            <th>Field</th>
            <th>Data</th>
          </tr>
        </thead>
        <tbody>
          <tr><td>Nama</td><td>${nama}</td></tr>
          <tr><td>NIM</td><td>${nim}</td></tr>
          <tr><td>Angkatan</td><td>${angkatan}</td></tr>
        </tbody>
      </table>
    </body>
    </html>
  `);
} else {
  document.write("Anda bukan praktikan PPW1, anda tidak boleh masuk!");
}