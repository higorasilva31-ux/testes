<?php
    namespace Projeto\mapa_senac_sbc;

    session_start();
    \mysqli_report(\MYSQLI_REPORT_OFF);

    $erro = "";
    $conexao = new \mysqli("localhost", "root", "", "mapaSenac");

    if($conexao->connect_error){
        $erro = "Não foi possível conectar com o banco de dados.";
    }else{
        $conexao->set_charset("utf8mb4");
    }

    if(isset($_GET["sair"])){
        session_destroy();
        header("Location: index.php");
        exit;
    }

    if(isset($_POST["entrar"]) && !$conexao->connect_error){
        $login = trim($_POST["login"] ?? "");
        $senha = $_POST["senha"] ?? "";

        if($login == "" || $senha == ""){
            $erro = "Preencha o usuário e a senha.";
        }else{
            $contas = [
                ["tabela" => "adm", "status" => "", "tipo" => "adm"],
                ["tabela" => "funcionario", "status" => "status_fun", "tipo" => "funcionario"],
                ["tabela" => "docentes", "status" => "status_docente", "tipo" => "docente"],
                ["tabela" => "usuario", "status" => "status_user", "tipo" => "usuario"]
            ];

            $encontrou = false;

            foreach($contas as $conta){
                $campoStatus = $conta["status"] != "" ? ", ".$conta["status"]." as status_conta" : ", 'ativado' as status_conta";
                $sql = "select id, nome, login, senha".$campoStatus." from ".$conta["tabela"]." where login = ? limit 1";
                $comando = $conexao->prepare($sql);
                $comando->bind_param("s", $login);
                $comando->execute();
                $resultado = $comando->get_result();

                if($resultado->num_rows == 1){
                    $dados = $resultado->fetch_assoc();
                    $encontrou = true;

                    if(strtolower($dados["status_conta"]) != "ativado"){
                        $erro = "Este usuário está desativado.";
                    }else{
                        $senhaCorreta = password_verify($senha, $dados["senha"]) || hash_equals($dados["senha"], $senha);

                        if($senhaCorreta){
                            session_regenerate_id(true);
                            $_SESSION["id"] = $dados["id"];
                            $_SESSION["nome"] = $dados["nome"];
                            $_SESSION["login"] = $dados["login"];
                            $_SESSION["tipo"] = $conta["tipo"];

                            if(isset($_POST["lembrar"])){
                                setcookie("login_mapa", $login, time() + 2592000, "/");
                            }else{
                                setcookie("login_mapa", "", time() - 3600, "/");
                            }

                            header("Location: php/test.php");
                            exit;
                        }else{
                            $erro = "Usuário ou senha incorretos.";
                        }
                    }
                    $comando->close();
                    break;
                }
                $comando->close();
            }

            if(!$encontrou){
                $erro = "Usuário ou senha incorretos.";
            }
        }
    }
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
                         <img style="width: 250px; vertical-align: top;" src="img/senacLogoB.png"  alt="">
                    </div>
                    <div class="text-center mb-4">
                        <h2>Bem-vindo!</h2>
                        <p class="text-muted">Acesse sua conta para continuar</p>
                    </div>
                    <?php if($erro != "") { ?>
                        <div class="alert alert-danger py-2"><i class="bi bi-exclamation-circle-fill me-2"></i><?php echo $erro; ?></div>
                    <?php } ?>
                    <form action="" method="POST" autocomplete="on">
                        <div class="mb-3">
                            <label class="form-label">Usuário</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control" id="login" name="login" value="<?php echo htmlspecialchars($_POST["login"] ?? $_COOKIE["login_mapa"] ?? ""); ?>" placeholder="Digite seu usuário" autocomplete="username" required autofocus>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Senha</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control" id="senha" name="senha" placeholder="Digite sua senha" autocomplete="current-password" required>
                                <button type="button" class="btn btn-outline-secondary" onclick="mostrarSenha()"><i id="iconeSenha" class="bi bi-eye"></i></button>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="lembrar" id="lembrar" <?php if(isset($_COOKIE["login_mapa"])) echo "checked"; ?>>
                                <label class="form-check-label" for="lembrar">Lembrar-me</label>
                            </div>
                            <a href="#" class="esqueci">Esqueci minha senha</a>
                        </div>
                        <button type="submit" name="entrar" class="btn btn-login w-100">Entrar <i class="bi bi-arrow-right"></i></button>
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
