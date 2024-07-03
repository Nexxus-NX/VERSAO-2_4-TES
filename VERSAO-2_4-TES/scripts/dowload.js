$(document).on("click", "#dowload", function (event) { event.preventDefault(); downloadCSV("tb1"); });
function downloadCSV(id_table) {
  const table = document.getElementById(id_table);
  let csv = [];
  for (let i = 0; i < table.rows.length; i++) {
    let row = [];
    for (let j = 0; j < table.rows[i].cells.length; j++) { row.push('"' + table.rows[i].cells[j].innerText + '"'); }
    csv.push(row.join(";"));
  }
  csv = csv.join("\n");
  const bom = "\uFEFF";
  csv = bom + csv;
  const blob = new Blob([csv], { type: "text/csv;charset=utf-8;" });
  const link = document.createElement("a");
  link.style.display = "none";
  const url = URL.createObjectURL(blob);
  link.setAttribute("href", url);
  const l1 = table.rows[0];
  let titulo = [];
  titulo.push(l1.cells[0].innerText);
  let nome = titulo[0].slice(0, 9) + ' ' + new Date().toLocaleString() + '.csv';
  link.setAttribute("download", nome);
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
}
