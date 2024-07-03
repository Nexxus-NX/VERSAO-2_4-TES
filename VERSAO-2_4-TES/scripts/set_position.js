var enable = 1;
enableDrag();
function enableDrag() {
  let divp = document.querySelectorAll('.divP1');
  document.getElementById('config').style.zIndex = 0;
  divp.forEach(function (draggable) {
    draggable.addEventListener('mousedown', function (event) {
      if (enable === 1) {
        var offsetX = event.clientX - draggable.getBoundingClientRect().left;
        var offsetY = event.clientY - draggable.getBoundingClientRect().top;
        function moveElement(event) {
          draggable.style.left = event.clientX - offsetX + 'px';
          draggable.style.top = event.clientY - offsetY + 'px';
        }
        function releaseElement() {
          document.removeEventListener('mousemove', moveElement);
          document.removeEventListener('mouseup', releaseElement);
          gravPosition(draggable);
        }
        document.addEventListener('mousemove', moveElement);
        document.addEventListener('mouseup', releaseElement);
      }
    });
  });
}
function gravPosition(draggable) {
  var px = draggable.getBoundingClientRect().left;
  var py = draggable.getBoundingClientRect().top;
  var tx = draggable.getBoundingClientRect().width;
  var ty = draggable.getBoundingClientRect().height;
  const img = document.getElementById('imagem');
  let imagemWidth = img.offsetWidth;
  let imagemHeigh = img.offsetHeight;
  try {
    let piv = draggable.querySelector(".num").value;
    let x = (px / imagemWidth) * 100.0;
    let y = (py / imagemHeigh) * 100.0;
    tx = (tx / imagemWidth) * 100.0;
    ty = (ty / imagemHeigh) * 100.0;
    return new Promise(function (resolve, reject) {
      $.post("script_php/processa.php", { num1: piv, num2: x, num3: y, num4: tx, num5: ty, num6: '6' })
        .done(function (data) {
          resolve(data);
        })
        .fail(function (error) {
          reject(error);
        });
    });
  } catch (error) {
    console.log('Caixa config');
  }
}