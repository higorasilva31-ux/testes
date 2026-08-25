<?php
        namespace Projeto\mapa_senac_sbc;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa Senac SBC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="container-fluid min-vh-100 p-0">
        <div class="row g-0 min-vh-100">
            <div class="col-lg-7 imagem">
                <div class="overlay"></div>
                <div class="conteudo-imagem">
                    <span class="localizacao"><i class="bi bi-geo-alt-fill"></i> São Bernardo do Campo - SP</span>
                    <h1>Mapa <span>Senac SBC</span></h1>
                    <p>Encontre unidades, salas, serviços, eventos e tudo o que o Senac São Bernardo do Campo oferece.</p>
                    <button class="btn btn-outline-light"><i class="bi bi-map"></i> Explorar mapa</button>
                </div>
            </div>

            <div class="col-lg-5 d-flex align-items-center justify-content-center">
                <div class="login-box">
                    <div class="text-center mb-4">
                        <div class="logo-senac">Senac</div>
                        <small>São Bernardo do Campo</small>
                    </div>

                    <div class="text-center mb-4">
                        <h2>Bem-vindo!</h2>
                        <p class="text-muted">Acesse sua conta para continuar</p>
                    </div>

                    <form action="#" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Usuário</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control" placeholder="Digite seu usuário" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Senha</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" id="senha" class="form-control" placeholder="Digite sua senha" required>
                                <button type="button" class="btn btn-outline-secondary" onclick="mostrarSenha()"><i id="iconeSenha" class="bi bi-eye"></i></button>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="lembrar">
                                <label class="form-check-label" for="lembrar">Lembrar-me</label>
                            </div>
                            <a href="#" class="esqueci">Esqueci minha senha</a>
                        </div>

                        <a href=""><button  type="submit" class="btn btn-login w-100">Entrar <i class="bi bi-arrow-right"></i></button></a>
                    </form>

                    <p class="text-center cadastro">Ainda não possui uma conta? <a href="php/cadastro.php">Cadastre-se</a></p>

                    <div class="row recursos mt-4 pt-4">
                        <div class="col-4 text-center"><i class="bi bi-map"></i><small>Mapa das unidades</small></div>
                        <div class="col-4 text-center"><i class="bi bi-calendar-event"></i><small>Eventos e cursos</small></div>
                        <div class="col-4 text-center"><i class="bi bi-info-circle"></i><small>Serviços e informações</small></div>
                    </div>

                    <p class="text-center copyright">© 2026 Mapa Senac SBC</p>
                </div>
            </div>
        </div>
    </div>
    <section class="">
  <!-- Footer -->
  <footer class="text-center text-black" style="background-color: #ffffffff">
    <!-- Grid container -->
    <div class="container p-4 pb-0">
      <!-- Section: CTA -->
      <section class="">
        <p class="d-flex justify-content-center align-items-center">
          <span class="me-3">Cadastre-se gratis</span>
          <button data-mdb-ripple-init type="button" class="btn btn-login btn-rounded">
            Cadastrar
          </button>
        </p>
      </section>
      <!-- Section: CTA -->
    </div>
    <!-- Grid container -->

    <!-- Copyright -->
    <div class="text-center text-white p-3" style="background-color: #174ea6;">
      © 2026 Copyright:
      <a class="text-white" href="https://www.sp.senac.br/">Senac.com</a>
    </div>
    <!-- Copyright -->
  </footer>
  <!-- Footer -->
</section>

    <script src="js/login.js"></script>
</body>
</html>