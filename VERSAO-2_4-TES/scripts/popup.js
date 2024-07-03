const containers = document.querySelectorAll('.divP1');
document.getElementById('popup').innerHTML = '';
containers.forEach(container => {
  container.addEventListener('click', function () {
    const popup = this.querySelector('.popup');
    popup.style.display = 'block';
    const piv = container.querySelector('.num').value;
    let data = { piv: piv };
    stat(data, popup);
  });
  container.addEventListener('mouseleave', function () {
    const popup = this.querySelector('.popup');
    popup.innerHTML = '';
    popup.style.display = 'none';
  });
});
function stat(piv, popup) {
  $.ajax({
    url: "script_php/buscSta.php",
    method: "POST",
    data: piv,
    success: function (response) {
      $(popup).html(response);
    },
    error: function (xhr, status, error) {
      console.error(error);
    }
  });
}