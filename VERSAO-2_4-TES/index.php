<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link rel="stylesheet" href="styles/login.css">
  <title>PivôPower</title>
</head>

<body>
  <div class="container">
    <div class="row">
      <div class="col-lg-3 col-md-2"></div>
      <div class="col-lg-6 col-md-8 login-box">
        <div class="col-lg-12 login-key">
        <embed src="img/logo2.png" type="image/png" width="200"  />
        </div>
        <div class="col-lg-12 login-title">
          ACESSO AO SISTEMA
        </div>
        <div class="col-lg-12 login-form">
          <div class="col-lg-12 login-form">
            <form id="login-form" class="form" action="script_php/autenticar.php" method="post">
              <div class="form-group">
                <label class="form-control-label" for="usuario">USUÁRIO</label>
                <input type="text" name="usuario" id="usuario" class="form-control" required>
              </div>
              <div class="form-group">
                <label class="form-control-label" for="senha">SENHA</label>
                <input type="password" name="senha" id="senha" class="form-control" required>
              </div>
              <div class="form-group">
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" class="custom-control-input" id="lembrarSenha" name="lembrarSenha">
                  <label class="custom-control-label" for="lembrarSenha">Salvar Senha</label>
                </div>
              </div>
              <div class="col-lg-12 loginbttm">
                <div class="col-lg-6 login-btm login-text">
                  <!-- Error Message -->
                </div>
                <div class="col-lg-6 login-btm login-button">
                  <button type="submit" class="btn btn-outline-primary">ENTRAR</button>
                </div>
              </div>
              <a href="#" class="forgot-password" id="troc_passw">Trocar senha</a>
              <br>
              <br>
              <br>
            </form>
          </div>
        </div>
        <div class="col-lg-3 col-md-2"></div>
      </div>
    </div>
  </div>

  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
  <script>
    function verificarLembrarSenha() {
      if (localStorage.getItem('lembrarSenha') === 'true') {
        let usuario = localStorage.getItem('usuario');
        let senha = localStorage.getItem('senha');
        if (usuario && senha) {
          $('#usuario').val(usuario);
          $('#senha').val(senha);
          $('#lembrarSenha').prop('checked',true);
        }
      }
    }
    $(document).ready(function() {
      verificarLembrarSenha();
    });
    $(document).on("click", "#troc_passw", function(event) {
      event.preventDefault();
      let usuario = $("#usuario").val();
      let senha = $("#senha").val();
      let nova = prompt('Digite a nova senha:');
      let data = {
        usuario: usuario,
        senha: senha,
        nova: nova
      };
      $.ajax({
        url: "script_php/troc_senha.php",
        type: "POST",
        data: data,
        success: function(response) {
          alert(response);
          if (response == 1) {}
        },
        error: function(xhr, status, error) {
          console.error(error);
        }
      });
    });
    $('#login-form').submit(function() {
      if ($('#lembrarSenha').is(':checked')) {
        localStorage.setItem('usuario', $('#usuario').val());
        localStorage.setItem('senha', $('#senha').val());
        localStorage.setItem('lembrarSenha', true);
      } else {
        localStorage.removeItem('usuario');
        localStorage.removeItem('senha');
        localStorage.removeItem('lembrarSenha');
      }
    });
  </script>
</body>

</html>
