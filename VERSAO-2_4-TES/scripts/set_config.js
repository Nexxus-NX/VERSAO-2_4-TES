document.addEventListener("DOMContentLoaded", function () {
  document.getElementById('set').addEventListener('click', function () {
    qdp = document.getElementById('qdp').value;
    get_conf(qdp, '4').then(function (ret) {
    }).catch(function (error) {
      console.error('Ocorreu um erro na requisição POST:', error);
    });
  });
});