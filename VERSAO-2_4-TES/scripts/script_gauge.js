function cloneDiv(num) {
  var div_piv = document.getElementById('p1');
  var clone = div_piv.cloneNode(true);
  clone.id = 'p' + num;
  clone.style.zIndex = 0;
  var novoCanvas = clone.querySelector('#gauge1');
  novoCanvas.id = 'gauge' + num;
  var novoInput = clone.querySelector('#num-p1');
  novoInput.id = 'num-' + num;
  novoInput.value = num;
  document.querySelector("#fundo").after(clone);
}
function gralRad(degrees) { return degrees * (Math.PI / 180); }
function criaGauge(piv, s2, ang_i, ang_f, ang_s) {
  const id = 'gauge' + piv;
  const canvas = document.getElementById(id);
  const divP1 = document.getElementById('p' + piv);
  const ctx = canvas.getContext('2d');
  canvas.width = 100;
  canvas.height = 100;
  canvas.margin = 0;
  canvas.padding = 0;
  canvas.border = 0;
  const x = canvas.width / 2;
  const y = canvas.height / 2;
  const radius = canvas.width / 2;
  const espessura = 60;
  const tes = 20;
  let cor1 = '#034413';
  let cor2 = cor1;
  //console.log(piv,"|",ang_i,"|",ang_f,"|",s2);
  if (ang_i != ang_f) {
    ang_f = (ang_f - ang_i) % 360;
    if ([2, 4].includes(s2)) {
      cor2 = '#a48c8c';
    } else if ([3, 5].includes(s2)) {
      cor2 = '#0542fa';
    } else { ang_f = 0; }
    if (s2 > 6 && s2 < 12) {
      divP1.style.backgroundColor = '#f21111dd';
    } else { divP1.style.backgroundColor = cor2; }
    if ([2, 3].includes(s2)) {
      [cor1, cor2] = [cor2, cor1];
    }
  } else { ang_f = (ang_f - ang_i) % 360; }
  ang_i *= -1;
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  ctx.beginPath();
  ctx.arc(x, y, radius, 0, Math.PI * 2);
  ctx.strokeStyle = cor1;
  ctx.lineWidth = espessura;
  ctx.stroke();
  ctx.beginPath();
  ctx.arc(x, y, radius, gralRad(0 - ang_i), gralRad(ang_f - ang_i));
  ctx.strokeStyle = cor2;
  ctx.lineWidth = espessura;
  ctx.stroke();
  if (ang_s) {
    ang_s *= -1;
    criaPonteiro(x, y, radius + tes, gralRad(0 - ang_s), ctx, '#ccc', '#b40b0b');
  }
  criaPonteiro(x, y, radius + tes, gralRad(ang_f - ang_i), ctx, '#ccc', '#ccc');
}
function criaPonteiro(x, y, length, angleInRadians, ctx, corInicio, corFim) {
  var endX = x + length * Math.cos(angleInRadians);
  var endY = y + length * Math.sin(angleInRadians);
  ctx.beginPath();
  ctx.moveTo(x, y);
  ctx.lineTo(endX, endY);
  var gradient = ctx.createLinearGradient(x, y, endX, endY);
  gradient.addColorStop(0, corInicio);
  gradient.addColorStop(1, corFim);
  ctx.strokeStyle = gradient;
  ctx.lineWidth = 4;
  ctx.stroke();
}
function defPosition(qdp) {
  for (var i = 1; i <= qdp; i++) {
    if (i > 1) { cloneDiv(i); }
    criaGauge(i, '1', 0, 1);
  }
}
function waitForEvent(ref) {
  const loadingOverlay = document.getElementById("loading-overlay");
  if (!ref) {
    loadingOverlay.style.display = "flex";
  } else {
    loadingOverlay.style.display = "none";
  }
}
function tempVolta(ai, af, sent, t) {
  let ret = 0; var delta = (af - ai + 180) % 360 - 180;
  if ((sent == 3 || sent == 5) && delta < 0) { ret = 360.0 + delta; } else
    if ((sent == 2 || sent == 4) && delta >= 0) { ret = 360.0 - delta; } else { ret = Math.abs(delta); }
  if (ai == af && ret) { return (t * 360).toFixed(2); }
  return t * ret.toFixed(2);
}
function atualiza(qdp) {
  if (qdp == 0) { qdp = 1; }
  let data = { qdp: qdp };
  $.ajax({
    url: "script_php/atualisa_p.php", method: "POST", data: data, dataType: "json",
    success: function (resp) {
      for (let i = 0; i < resp.piv.length; i++) {
        if (i < qdp && resp.piv[i] > 0 && resp.piv[i] <= qdp) {
          criaGauge(resp.piv[i], resp.sta[i], resp.ang_i[i], resp.ang_f[i], resp.ang_s[i]);
        }
      }
      waitForEvent(1);
    },
    error: function (error) {
      console.error(error);
    }
  });
}
waitForEvent(0);
if (qdp > 0) { defPosition(qdp); atualiza(qdp); }
let temp = 3000;
function setClock() {
  waitForEvent(1);
  atualiza(qdp);
  temp = 25000;
  setTimeout(setClock, temp);
}
setTimeout(setClock, temp);  