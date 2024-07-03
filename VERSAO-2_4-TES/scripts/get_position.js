get_conf(1, '5').then(function (data) {
  data = data.replace(/\n/g, '');
  let rx = data.split('/');
  rx.forEach(function (elemento) {
    var valores = elemento.split(',');
    let ax = parseInt(valores[0]);
    if (ax) {
      ax = 'p' + ax;
      let pivo = document.getElementById(ax);
      if (pivo !== null) {
        var x = parseFloat(valores[1]);
        var y = parseFloat(valores[2]);
        var tx = parseFloat(valores[3]);
        var ty = parseFloat(valores[4]);
        pivo.style.left = (x - ((1 * x) / 100)) + '%';
        pivo.style.top = (y - ((y) / 100)) + '%';
        pivo.style.width = (tx - ((1 * tx) / 100)) + '%';
        pivo.style.height = (ty - ((ty) / 100)) + '%';
      } else {
        console.error("ID: 'p " + valores[0] + "não encontrado'");
      }
    }
  });
}).catch(function (error) {
  console.error("Ocorreu um erro:", error);
});