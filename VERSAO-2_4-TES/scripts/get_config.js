function get_conf(num, ret) {
  return new Promise(function (resolve, reject) {
    $.post("script_php/processa.php", { num1: num, num2: '2', num3: '3', num4: '4', num5: '5', num6: ret })
      .done(function (data) {
        resolve(data);
      })
      .fail(function (error) {
        reject(error);
      });
  });
}
get_conf(1, '2').then(function (ret) {
  qdp = parseInt(ret);
}).catch(function (error) {
  console.error('Ocorreu um erro na requisição POST:', error);
});
function pausarPorSegundos(segundos) {
  var inicio = new Date().getTime();
  while (new Date().getTime() - inicio < segundos * 1000) { }
}
pausarPorSegundos(2);