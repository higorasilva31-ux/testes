<?php
    namespace Projeto\mapa_senac_sbc;

    \mysqli_report(\MYSQLI_REPORT_OFF);

    $erro = "";
    $sucesso = "";
    $conexao = new \mysqli("localhost", "root", "", "mapaSenac");

    if($conexao->connect_error){
        $erro = "Não foi possível conectar com o banco de dados.";
    }else{
        $conexao->set_charset("utf8mb4");
    }

    function validarCPFServidor($cpf){
        $cpf = preg_replace('/\D/', '', $cpf);
        if(strlen($cpf) != 11 || preg_match('/^(\d)\1{10}$/', $cpf)) return false;

        for($t = 9; $t < 11; $t++){
            $soma = 0;
            for($c = 0; $c < $t; $c++) $soma += (int)$cpf[$c] * (($t + 1) - $c);
            $digito = ((10 * $soma) % 11) % 10;
            if((int)$cpf[$c] != $digito) return false;
        }
        return true;
    }

    function valorCampo($campo){
        return htmlspecialchars($_POST[$campo] ?? "", ENT_QUOTES, "UTF-8");
    }

    if($_SERVER["REQUEST_METHOD"] == "POST" && !$conexao->connect_error){
        $nome = trim($_POST["nome"] ?? "");
        $dataNascimento = $_POST["data_nascimento"] ?? "";
        $email = strtolower(trim($_POST["email"] ?? ""));
        $cpf = trim($_POST["cpf"] ?? "");
        $telefone = trim($_POST["telefone"] ?? "");
        $usuario = trim($_POST["usuario"] ?? "");
        $senha = $_POST["senha"] ?? "";
        $confirmarSenha = $_POST["confirmar_senha"] ?? "";
        $dataValida = \DateTime::createFromFormat("Y-m-d", $dataNascimento);

        if($nome == "" || $dataNascimento == "" || $email == "" || $cpf == "" || $telefone == "" || $usuario == "" || $senha == "" || $confirmarSenha == ""){
            $erro = "Preencha todos os campos.";
        }elseif(!$dataValida || $dataValida->format("Y-m-d") != $dataNascimento || $dataValida > new \DateTime()){
            $erro = "Digite uma data de nascimento válida.";
        }elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $erro = "Digite um e-mail válido.";
        }elseif(!validarCPFServidor($cpf)){
            $erro = "Digite um CPF válido.";
        }elseif(!in_array(strlen(preg_replace('/\D/', '', $telefone)), [10, 11])){
            $erro = "Digite um telefone válido.";
        }elseif(strlen($senha) < 6){
            $erro = "A senha deve possuir pelo menos 6 caracteres.";
        }elseif($senha != $confirmarSenha){
            $erro = "As senhas não são iguais.";
        }elseif(!isset($_POST["termos"])){
            $erro = "Você precisa aceitar os Termos de Uso.";
        }else{
            $verificar = $conexao->prepare("select CPF, login, email from usuario where CPF = ? or login = ? or email = ? limit 1");

            if(!$verificar){
                $erro = "A tabela usuario ainda precisa do campo email e da senha com tamanho 255.";
            }else{
                $verificar->bind_param("sss", $cpf, $usuario, $email);
                $verificar->execute();
                $resultado = $verificar->get_result();

                if($resultado->num_rows > 0){
                    $dados = $resultado->fetch_assoc();
                    if($dados["CPF"] == $cpf) $erro = "Este CPF já está cadastrado.";
                    elseif(strtolower($dados["login"]) == strtolower($usuario)) $erro = "Este usuário já está sendo utilizado.";
                    else $erro = "Este e-mail já está cadastrado.";
                }else{
                    $senhaCriptografada = password_hash($senha, PASSWORD_DEFAULT);
                    $cadastrar = $conexao->prepare("insert into usuario(nome, CPF, dt_nascimento, email, login, senha, telefone) values(?, ?, ?, ?, ?, ?, ?)");

                    if($cadastrar){
                        $cadastrar->bind_param("sssssss", $nome, $cpf, $dataNascimento, $email, $usuario, $senhaCriptografada, $telefone);

                        if($cadastrar->execute()){
                            $sucesso = "Cadastro realizado com sucesso!";
                            $_POST = [];
                        }else{
                            $erro = "Não foi possível realizar o cadastro.";
                        }
                        $cadastrar->close();
                    }else{
                        $erro = "Não foi possível preparar o cadastro.";
                    }
                }
                $verificar->close();
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro | Mapa Senac SBC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/login.css">
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
            <!-- Lado direito -->
            <div class="col-lg-5 d-flex align-items-center justify-content-center bg-white py-4">
                <div class="login-box w-100">
                    <div class="text-center mb-3">
                        <img class="img-fluid w-50" src="../img/senacLogoB.png" alt="Logo Senac">
                    </div>
                    <div class="text-center mb-3">
                        <h2>Crie sua conta</h2>
                    </div>
                    <?php if($erro != "") { ?>
                        <div class="alert alert-danger py-2"><i class="bi bi-exclamation-circle-fill me-2"></i><?php echo htmlspecialchars($erro); ?></div>
                    <?php } ?>
                    <?php if($sucesso != "") { ?>
                        <div class="alert alert-success py-2"><i class="bi bi-check-circle-fill me-2"></i><?php echo $sucesso; ?> <a href="../index.php">Fazer login</a></div>
                    <?php } ?>
                    <form id="formCadastro" action="" method="POST">
                        <div class="row g-2">
                            <!-- Nome -->
                            <div class="col-md-6">
                                <label for="nome" class="form-label mb-1">Nome completo</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input type="text" class="form-control py-2" id="nome" name="nome" value="<?php echo valorCampo("nome"); ?>" placeholder="Digite seu nome" autocomplete="name" required>
                                </div>
                            </div>
                            <!-- Data de nascimento -->
                            <div class="col-md-6">
                                <label for="dataNascimento" class="form-label mb-1">Data de nascimento</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text"><i class="bi bi-calendar-date"></i></span>
                                    <input type="date" class="form-control py-2" id="dataNascimento" name="data_nascimento" value="<?php echo valorCampo("data_nascimento"); ?>" autocomplete="bday" required>
                                </div>
                            </div>
                            <!-- E-mail -->
                            <div class="col-12">
                                <label for="email" class="form-label mb-1">E-mail</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" class="form-control py-2" id="email" name="email" value="<?php echo valorCampo("email"); ?>" placeholder="Digite seu e-mail" autocomplete="email" required>
                                </div>
                            </div>
                            <!-- CPF -->
                            <div class="col-md-6">
                                <label for="cpf" class="form-label mb-1">CPF</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text"><i class="bi bi-person-vcard"></i></span>
                                    <input type="text" class="form-control py-2" id="cpf" name="cpf" value="<?php echo valorCampo("cpf"); ?>" placeholder="000.000.000-00" inputmode="numeric" maxlength="14" required>
                                </div>
                                <div id="erroCPF" class="text-danger small mt-1 d-none">Digite um CPF válido.</div>
                            </div>
                            <!-- Telefone -->
                            <div class="col-md-6">
                                <label for="telefone" class="form-label mb-1">Telefone</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                    <input type="tel" class="form-control py-2" id="telefone" name="telefone" value="<?php echo valorCampo("telefone"); ?>" placeholder="(11) 99999-9999" autocomplete="tel" inputmode="numeric" maxlength="15" required>
                                </div>
                            </div>
                            <!-- Usuário -->
                            <div class="col-12">
                                <label for="usuario" class="form-label mb-1">Usuário</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text"><i class="bi bi-person-circle"></i></span>
                                    <input type="text" class="form-control py-2" id="usuario" name="usuario" value="<?php echo valorCampo("usuario"); ?>" placeholder="Digite seu usuário" autocomplete="username" required>
                                </div>
                            </div>
                            <!-- Senha -->
                            <div class="col-md-6">
                                <label for="senha" class="form-label mb-1">Senha</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    <input type="password" class="form-control py-2" id="senha" name="senha" placeholder="Digite sua senha" minlength="6" autocomplete="new-password" required>
                                    <button type="button" class="btn btn-outline-secondary" onclick="mostrarSenha('senha', 'iconeSenha')"><i id="iconeSenha" class="bi bi-eye"></i></button>
                                </div>
                            </div>
                            <!-- Confirmar senha -->
                            <div class="col-md-6">
                                <label for="confirmarSenha" class="form-label mb-1">Confirmar senha</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                    <input type="password" class="form-control py-2" id="confirmarSenha" name="confirmar_senha" placeholder="Digite novamente" minlength="6" autocomplete="new-password" required>
                                    <button type="button" class="btn btn-outline-secondary" onclick="mostrarSenha('confirmarSenha', 'iconeConfirmarSenha')"><i id="iconeConfirmarSenha" class="bi bi-eye"></i></button>
                                </div>
                                <div id="erroSenha" class="text-danger small mt-1 d-none">As senhas não são iguais.</div>
                            </div>
                        </div>
                        <div class="form-check mt-3 mb-3">
                            <input class="form-check-input" type="checkbox" id="termos" name="termos" required>
                            <label class="form-check-label small" for="termos">
                                Li e concordo com os <a href="#" class="esqueci">Termos de Uso</a> e a <a href="#" class="esqueci">Política de Privacidade</a>.
                            </label>
                        </div>
                        <button type="submit" class="btn btn-login w-100">Cadastrar <i class="bi bi-arrow-right"></i></button>
                    </form>
                    <p class="text-center cadastro mb-2">Já possui uma conta? <a href="../index.php">Fazer login</a></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="text-center text-black bg-white">
        <div class="text-center text-white p-3" style="background-color: #174ea6;">
            © 2026 Copyright:
            <a class="text-white" href="https://www.sp.senac.br/" target="_blank">Senac.com</a>
        </div>
    </footer>

    <script>
        const formulario = document.getElementById("formCadastro");
        const campoCPF = document.getElementById("cpf");
        const erroCPF = document.getElementById("erroCPF");
        const campoTelefone = document.getElementById("telefone");
        const campoSenha = document.getElementById("senha");
        const campoConfirmarSenha = document.getElementById("confirmarSenha");
        const erroSenha = document.getElementById("erroSenha");

        function mostrarSenha(campoId, iconeId){
            const campo = document.getElementById(campoId);
            const icone = document.getElementById(iconeId);
            if(campo.type === "password"){
                campo.type = "text";
                icone.classList.replace("bi-eye", "bi-eye-slash");
            }else{
                campo.type = "password";
                icone.classList.replace("bi-eye-slash", "bi-eye");
            }
        }

        function calcularDigitoCPF(numeros){
            let soma = 0;
            let peso = numeros.length + 1;
            for(let i = 0; i < numeros.length; i++){
                soma += Number(numeros[i]) * peso;
                peso--;
            }
            const resto = soma % 11;
            if(resto < 2) return 0;
            return 11 - resto;
        }

        function validarCPF(cpf){
            const numeros = cpf.replace(/\D/g, "");
            if(numeros.length !== 11) return false;
            if(/^(\d)\1{10}$/.test(numeros)) return false;
            const baseCPF = numeros.substring(0, 9);
            const primeiroDigito = calcularDigitoCPF(baseCPF);
            const segundoDigito = calcularDigitoCPF(baseCPF + primeiroDigito);
            return baseCPF + primeiroDigito + segundoDigito === numeros;
        }

        function validarCampoCPF(){
            const valido = validarCPF(campoCPF.value);
            campoCPF.setCustomValidity(valido ? "" : "CPF inválido");
            campoCPF.classList.toggle("is-valid", valido);
            campoCPF.classList.toggle("is-invalid", !valido);
            erroCPF.classList.toggle("d-none", valido);
            return valido;
        }

        campoCPF.addEventListener("input", function(){
            let valor = this.value.replace(/\D/g, "").slice(0, 11);
            valor = valor.replace(/(\d{3})(\d)/, "$1.$2");
            valor = valor.replace(/(\d{3})(\d)/, "$1.$2");
            valor = valor.replace(/(\d{3})(\d{1,2})$/, "$1-$2");
            this.value = valor;
            this.setCustomValidity("");
            this.classList.remove("is-valid", "is-invalid");
            erroCPF.classList.add("d-none");
        });

        campoCPF.addEventListener("blur", validarCampoCPF);

        campoTelefone.addEventListener("input", function(){
            let valor = this.value.replace(/\D/g, "").slice(0, 11);
            valor = valor.replace(/^(\d{2})(\d)/, "($1) $2");
            valor = valor.replace(/(\d{5})(\d{4})$/, "$1-$2");
            this.value = valor;
        });

        function validarSenhas(){
            const valido = campoSenha.value === campoConfirmarSenha.value;
            campoConfirmarSenha.setCustomValidity(valido ? "" : "As senhas não são iguais");
            campoConfirmarSenha.classList.toggle("is-valid", valido);
            campoConfirmarSenha.classList.toggle("is-invalid", !valido);
            erroSenha.classList.toggle("d-none", valido);
            return valido;
        }

        campoConfirmarSenha.addEventListener("input", validarSenhas);

        formulario.addEventListener("submit", function(event){
            const cpfValido = validarCampoCPF();
            const senhasValidas = validarSenhas();
            if(!cpfValido || !senhasValidas) event.preventDefault();
        });
    </script>
</body>
</html>
