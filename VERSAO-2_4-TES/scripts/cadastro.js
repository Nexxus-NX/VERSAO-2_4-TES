$(document).ready(function () {
  let dive = document.getElementById('passwordOverlay');
  function carregarUser() {
    dive.style.display = 'flex';
    $.ajax({
      url: "script_php/list_user.php",
      success: function (response) {
        $("#passwordOverlay").html(response);
      }
    });
  }
  function carregarCadastro() {
    dive.style.display = 'flex';
    $.ajax({
      url: "script_php/carregar_cadastro.php",
      success: function (response) {
        $("#passwordOverlay").html(response);
        $("#submitCadastro").on("click", function () {
          gravCliente(0);
        });
      }
    });
  }
  function loadScript(ref) {
    var script = document.createElement('script');
    script.src = 'scripts/' + ref;
    script.onload = function () {
    };
    document.head.appendChild(script);
  }
  function unloadScript(ref) {
    var scripts = document.querySelectorAll(ref);
    scripts.forEach(function (script) {
      script.parentNode.removeChild(script);
      console.log('Script externo descarregado.');
    });
  }
  function carregaPivs(ref) {
    dive.style.display = 'flex';
    $.ajax({
      url: "script_php/carrega_pivos.php",
      success: function (response) {
        $("#passwordOverlay").html(response);
        $("#submitCadastro").on("click", function () {
          gravPivs(0);
        });
      },
      error: function (xhr, status, error) {
        console.error(error);
      }
    });//*/
  }
  function gravCliente(ref) {
    event.preventDefault();
    let faz = $("#faz2").val();
    let nome = $("#nome").val();
    let email = $("#email").val();
    let cpf = $("#cpf").val();
    let senha = $("#senha").val();
    let nivel = $("#nivel").val();
    let data = {
      faz2: faz,
      nome: nome,
      email: email,
      cpf: cpf,
      senha: senha,
      nivel: nivel,
      ax: ref
    };
    cadastro(data);
  }
  function gravPivs(ref) {
    event.preventDefault();
    let faz = $("#faz").val();
    let piv = $("#piv").val();
    let aria = $("#aria").val();
    let vaz = $("#vaz").val();
    let vel = $("#vel").val();
    let lb = $("#lb").val();
    let data = {
      faz: faz,
      piv: piv,
      aria: aria,
      vaz: vaz,
      vel: vel,
      lb: lb,
      ax: ref
    };
    cad_pivs(data);
  }
  function trocaFaz(data) {
    $.ajax({
      url: "script_php/trocaFaz.php",
      method: "POST",
      data: data,
      success: function (response) {
      },
      error: function (xhr, status, error) {
        console.error(error);
      }
    });
  }
  function cadastro(data) {
    $.ajax({
      type: "POST",
      url: "script_php/cadastro.php",
      data: data,
      success: function (response) {
        alert(response);
      },
      error: function (xhr, status, error) {
        console.error(error);
      }
    });
  }
  function cad_pivs(data) {
    $.ajax({
      url: "script_php/cad_pivs.php",
      type: "POST",
      data: data,
      success: function (response) {
        if (response == 1) {
          if (confirm('Pivô já cadastrado!\nDeseja alterar os dados?')) {
            gravPivs(1);
          }
        } else { alert(response); }
      },
      error: function (xhr, status, error) {
        console.error(error);
      }
    });
  }
  $(document).on("click", "#faz1", function (event) {
    event.preventDefault();
    let faz = document.getElementById('faz1').value;
    let data = { faz1: faz };
    trocaFaz(data);
  });
  $(document).on("click", "#list_cliente", function (event) {
    event.preventDefault();
    carregarUser();
  });
  $(document).on("click", "#configurar", function (event) {
    event.preventDefault();
    loadScript('set_position.js');
  });
  $(document).on("click", "#cad_cliente", function (event) {
    event.preventDefault();
    carregarCadastro();
  });
  $(document).on("click", "#cad_pivs", function (event) {
    event.preventDefault();
    carregaPivs(0);
  });
  window.addEventListener('click', function (event) {
    var ps = document.getElementById('passwordOverlay');
    if (event.target == ps) {
      ps.style.display = 'none';
    }
  });
  document.getElementById('sair').addEventListener('click', function () {
    var config = document.getElementById('config');
    config.style.zIndex = -1;
    enable = 0;
    unloadScript('script[src="scripts/set_position.js"]');
    unloadScript('script[src="scripts/script_redimen.js"]');
  });
  document.getElementById('red').addEventListener('click', function () {
    if (enable === 1) {
      this.style.backgroundColor = "#45a049";
      this.textContent = "Red";
      unloadScript('script[src="scripts/set_position.js"]');
      enable = 2;
      loadScript('script_redimen.js');
    } else {
      this.style.backgroundColor = "#0000ff";
      this.textContent = "Mov";
      unloadScript('script[src="scripts/script_redimen.js"]');
      enable = 1;
      loadScript('set_position.js');
    }
  });
});
