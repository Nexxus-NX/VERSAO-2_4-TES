$(document).ready(function () {
  let divChart = document.getElementById('divChart');
  let table = document.getElementById('divTable');
  let base = document.getElementById('passwordOverlay');
  $(document).on("click", "#hist", function (event) {
    event.preventDefault();
    divChart.style.display = 'none';
    table.style.display = 'block';
    base.style.display = 'none';
    let piv = document.getElementById('piv').value;
    let dat1 = document.getElementById('dat1').value;
    let dat2 = document.getElementById('dat2').value;
    let dias = calcDiferenca(dat1, dat2);
    let chec = chekbox1();
    if (dias > 30) { alert('Intervalo de busca muinto longo!!', dias); } else
      if (dias > 0) {
        let data = { piv: piv, dat1: dat1, dat2: dat2, chec: chec };
        tabela(data);
      }
  });
  $(document).on("click", "#irri", function (event) {
    event.preventDefault();
    divChart.style.display = 'none';
    table.style.display = 'block';
    base.style.display = 'none';
    let piv = document.getElementById('piv').value;
    let dat1 = document.getElementById('dat1').value;
    let dat2 = document.getElementById('dat2').value;
    let dias = calcDiferenca(dat1, dat2);
    if (dias > 30) { alert('Intervalo de busca muinto longo!!', dias); } else
      if (dias > 0) {
        let data = { piv: piv, dat1: dat1, dat2: dat2, ref: 2 };
        irriga(data);
      }
  });
  $(document).on("click", "#graf", function (event) {
    event.preventDefault();
    divChart.style.display = 'flex';
    table.style.display = 'none';
    base.style.display = 'none';
    let piv = document.getElementById('piv').value;
    let dat1 = document.getElementById('dat1').value;
    let dat2 = document.getElementById('dat2').value;
    let dias = calcDiferenca(dat1, dat2);
    if (dias > 30) { alert('Intervalo de busca muinto longo!!', dias); } else
      if (dias > 0) { let data = { piv: piv, dat1: dat1, dat2: dat2 }; grafico(data); }
  });
  $(document).on("click", "#resu", function (event) {
    event.preventDefault();
    divChart.style.display = 'none';
    table.style.display = 'none';
    base.style.display = 'flex';
    let piv = document.getElementById('piv').value;
    let dat1 = document.getElementById('dat1').value;
    let dat2 = document.getElementById('dat2').value;
    let dias = calcDiferenca(dat1, dat2);
    if (dias > 30) { alert('Intervalo de busca muinto longo!!', dias); } else
      if (dias > 0) { let data = { piv: piv, dat1: dat1, dat2: dat2, ref: 1 }; resumo(data); }
  });
  function tabela(data) {
    $.ajax({
      url: "script_php/nav_hist.php", method: "POST", data: data,
      success: function (response) {
        destroiTabela();
        //console.log(response);
        if (response == 1) { alert('Número acima da quantidade cadastrada!'); } else { $("#divTable").html(response); }
      }, error: function (error) {
        console.error(error);
      }
    });
  }
  function irriga(data) {
    $.ajax({
      url: "script_php/get_irriga.php", method: "POST", data: data,
      success: function (response) {
        //console.log(response);
        destroiTabela();
        if (response == 1) { alert('Número acima da quantidade cadastrada!'); }
        else { $("#divTable").html(response); }//*/
      }, error: function (error) {
        console.error(error);
      }
    });
  }
  function grafico(data) {
    $.ajax({
      url: 'script_php/graficos.php', method: "POST", data: data, dataType: "json",
      success: function (resp) {
        //console.log(resp.tes);
        let ang = []; let max = 0; let min = 999; let angt = 0; let angmin = 0; let med = 0;
        for (var i = 0; i < 360; i++) {
          ang.push(String(i) + '°');
          if (max < resp.mmAng[i]) { max = resp.mmAng[i]; angt = i; }
          if (min > resp.mmAng[i] && resp.mmAng[i] > 0) { min = resp.mmAng[i]; angmin = i; }
          med += resp.mmAng[i];
        }
        med = med / 360;
        destroiTabela();
        grafBarras(1, resp.datS, resp.tSemA, resp.totS, 'Tempo a seco', ' h');
        grafBarras(2, resp.datA, resp.tComA, resp.totA, 'Tempo com água', ' h');
        grafBarras(3, resp.datA, resp.consu, resp.totAg, 'm³ consumidos', ' m³');
        drawGauge(4, resp.mmAng, med, "Lâmina p/ âng.|( mapa de umidade ) máx. " + max.toFixed(2) + " mm no ang." + angt + "°, min. " + min.toFixed(2) + " mm no ang." + angmin + "°, e média de " + med.toFixed(2) + "mm");//*/
      }, error: function (error) {
        console.error('Erro ao obter dados do servidor:', error);
      }
    });
  }
  function resumo(data) {
    $.ajax({
      url: "script_php/graficos.php", method: "POST", data: data,
      success: function (resp) { destroiTabela(); $("#passwordOverlay").html(resp); },
      error: function (error) {
        console.error('Erro ao obter dados do servidor:', error);
      }
    });
  }
  function drawGauge(id, val, med, titulo) {
    var tiGraf = document.getElementById('titGraf' + id);
    var canvas = document.getElementById('myChart' + id);
    var ctx = canvas.getContext('2d');
    var canvasWidth = canvas.offsetWidth;
    var canvasHeight = canvas.offsetHeight;
    var centerX = canvasWidth / 2;
    var centerY = canvasHeight / 2;
    var radius = Math.min(centerX, centerY);
    var startAngle = -Math.PI / 2;
    let tx = titulo.split("|");
    tiGraf.style.color = '#f1e9e9';
    tiGraf.innerHTML = "<h3>" + tx[0] + "</h3><h5>" + tx[1] + "</h5>";
    canvas.width = canvasWidth;
    canvas.height = canvasHeight;
    ctx.clearRect(0, 0, canvasWidth, canvasHeight);
    var colors = calculateColors(val, med);
    drawSegments(colors, ctx, radius, centerX, centerY, startAngle);
    handleMouseEvents(canvas, val);
  }
  function calculateColors(val, med) {
    var colors = [];
    for (let i = 0; i < 360; i++) {
      let adjustedValue = adjustValue(val[i], med);
      let color = 'rgba(' + adjustedValue + ',' + adjustedValue + ',' + (245 - adjustedValue) + ',1)';
      colors.push(color);
    }
    return colors;
  }
  function adjustValue(value, med) {
    if (value > med) {
      value -= value - med;
    } else {
      value += (med - value) * 6;
    }
    return parseInt((value * 115) / 200);
  }
  function drawSegments(colors, ctx, radius, centerX, centerY, startAngle) {
    var angleIncrement = (Math.PI * 2) / colors.length;
    for (var i = 0; i < colors.length; i++) {
      ctx.beginPath();
      ctx.fillStyle = colors[i];
      ctx.moveTo(centerX, centerY);
      ctx.arc(centerX, centerY, radius, startAngle + i * angleIncrement, startAngle + (i + 1) * angleIncrement);
      ctx.lineTo(centerX, centerY);
      ctx.closePath();
      ctx.fill();
    }
  }
  function handleMouseEvents(canvas, val) {
    canvas.addEventListener('mousemove', function (event) {
      var anglePopup = document.getElementById('anglePopup');
      var rect = canvas.getBoundingClientRect();
      var angle = getMouseAngle(event, rect, canvas);
      anglePopup.style.display = 'block';
      anglePopup.style.left = (event.pageX + 10) + 'px';
      anglePopup.style.top = (event.pageY - 40) + 'px';
      anglePopup.innerText = "Ângulo: " + angle + "°, Valor: " + val[angle].toFixed(2) + 'mm';
    });

    canvas.addEventListener('mouseout', function () {
      document.getElementById('anglePopup').style.display = 'none';
    });
  }
  function getMouseAngle(event, rect, canvas) {
    var mouseX = event.clientX - rect.left - canvas.width / 2;
    var mouseY = event.clientY - rect.top - canvas.height / 2;
    var angle = Math.atan2(mouseY, mouseX) - Math.PI / 2;
    if (angle < -Math.PI) angle += 2 * Math.PI;
    var degrees = Math.round(angle * 180 / Math.PI);
    degrees = (degrees < 0) ? 360 + degrees : degrees;
    return degrees;
  }
  function grafBarras(myChart, rotulos, dados, tot, rotuloDoConjunto, uni) {
    var tiGraf = document.getElementById('titGraf' + myChart);
    let chart = 'myChart' + myChart;
    var canvas = document.getElementById(chart);
    var contexto = canvas.getContext('2d');
    if (canvas.chart) { canvas.chart.destroy(); }
    tiGraf.style.color = '#f1e9e9';
    tiGraf.textContent = 'Total de ' + tot + uni;
    let cor = '#36a3eb88';
    if (myChart == 1) { cor = '#a48c8c' }
    canvas.chart = new Chart(contexto, {
      type: 'bar',
      data: {
        labels: rotulos, datasets: [{
          label: rotuloDoConjunto, data: dados,
          backgroundColor: cor,
          borderColor: '#366ceb',
          borderWidth: 1
        }]
      },
      options: {
        scales: {
          y: { beginAtZero: true, ticks: { color: 'white' } },
          x: { ticks: { color: 'white' } }
        },
        plugins: {
          tooltip: {
            callbacks: {
              label: function (context) {
                var label = context.dataset.label || '';
                if (label) { label += ': '; }
                if (context.parsed.y !== null) { label += context.parsed.y.toFixed(2) + ' ' + uni; }
                return label;
              }
            }
          }
        }
      }
    });
  }
  function calcDiferenca(dat1, dat2) {
    var data1 = new Date(dat1); var data2 = new Date(dat2);
    if (isNaN(data1.getTime()) || isNaN(data2.getTime())) { alert("Por favor,insira datas válidas."); return; }
    var diferenca = Math.abs(data2.getTime() - data1.getTime());
    var dias = Math.floor(diferenca / (1000 * 3600 * 24));
    return dias;
  }
  function chekbox1() {
    var checkboxes = document.getElementsByName('chec');
    var selecionadas = [];
    for (var i = 0; i < 4; i++) {
      if (checkboxes[i].checked) { selecionadas.push(checkboxes[i].value); } else { selecionadas.push(0); }
    } return selecionadas;
  }
  function destroiTabela() {
    var tabela = document.getElementById("tb");
    if (tabela) { tabela.parentNode.removeChild(tabela); }
  }
  window.addEventListener('click', function (event) {
    var ps = document.getElementById('passwordOverlay');
    if (event.target == ps) { ps.style.display = 'none'; }
  });
});