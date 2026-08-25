<?php
include("cabecalho.php");
?>

<main class="bg-white">
    <div
        class="container d-flex align-items-center justify-content-center py-5"
        style="min-height: calc(100vh - 70px);"
    >
        <div class="w-100" style="max-width: 410px;">

            <!-- Identificação -->
            <div class="text-center mb-4">
                
                <img style="width: 230px" src="../img/senacLogoB.png"  alt="">
                

                <p
                    class="small mb-4"
                    style="color: #174ea6;"
                >
                    São Bernardo do Campo
                </p>

                <h2 class="fw-bold text-primary mb-2">
                    Crie sua conta
                </h2>

                <p class="text-secondary mb-0">
                    Preencha seus dados para continuar
                </p>
            </div>

            <!-- Formulário -->
            <form action="processar_cadastro.php" method="POST">

                <div class="mb-3">
                    <label for="nome" class="form-label fw-semibold">
                        Nome completo
                    </label>

                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 px-3">
                            <i class="bi bi-person text-secondary"></i>
                        </span>

                        <input
                            type="text"
                            class="form-control border-start-0 py-3"
                            id="nome"
                            name="nome"
                            placeholder="Digite seu nome completo"
                            autocomplete="name"
                            required
                        >
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">
                        E-mail
                    </label>

                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 px-3">
                            <i class="bi bi-envelope text-secondary"></i>
                        </span>

                        <input
                            type="email"
                            class="form-control border-start-0 py-3"
                            id="email"
                            name="email"
                            placeholder="Digite seu e-mail"
                            autocomplete="email"
                            required
                        >
                    </div>
                </div>

                <div class="mb-3">
                    <label for="usuario" class="form-label fw-semibold">
                        Usuário
                    </label>

                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 px-3">
                            <i class="bi bi-person-circle text-secondary"></i>
                        </span>

                        <input
                            type="text"
                            class="form-control border-start-0 py-3"
                            id="usuario"
                            name="usuario"
                            placeholder="Digite seu usuário"
                            autocomplete="username"
                            required
                        >
                    </div>
                </div>

                <div class="mb-3">
                    <label for="senha" class="form-label fw-semibold">
                        Senha
                    </label>

                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 px-3">
                            <i class="bi bi-lock text-secondary"></i>
                        </span>

                        <input
                            type="password"
                            class="form-control border-start-0 border-end-0 py-3"
                            id="senha"
                            name="senha"
                            placeholder="Digite sua senha"
                            minlength="6"
                            autocomplete="new-password"
                            required
                        >

                        <button
                            class="btn btn-outline-secondary px-3"
                            type="button"
                            onclick="mostrarSenha('senha', 'iconeSenha')"
                            aria-label="Mostrar senha"
                        >
                            <i class="bi bi-eye" id="iconeSenha"></i>
                        </button>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="confirmarSenha" class="form-label fw-semibold">
                        Confirmar senha
                    </label>

                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 px-3">
                            <i class="bi bi-lock text-secondary"></i>
                        </span>

                        <input
                            type="password"
                            class="form-control border-start-0 border-end-0 py-3"
                            id="confirmarSenha"
                            name="confirmar_senha"
                            placeholder="Digite sua senha novamente"
                            minlength="6"
                            autocomplete="new-password"
                            required
                        >

                        <button
                            class="btn btn-outline-secondary px-3"
                            type="button"
                            onclick="mostrarSenha('confirmarSenha', 'iconeConfirmar')"
                            aria-label="Mostrar senha"
                        >
                            <i class="bi bi-eye" id="iconeConfirmar"></i>
                        </button>
                    </div>
                </div>

                <div class="form-check mb-4">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        id="termos"
                        name="termos"
                        required
                    >

                    <label class="form-check-label" for="termos">
                        Li e concordo com os termos de uso
                    </label>
                </div>

                <a href=""><button  type="submit" class="btn btn-login w-100">Cadastrar <i class="bi bi-arrow-right"></i></button></a>

               
                 

                <p class="text-center text-secondary mt-4 mb-0">
                    Já possui uma conta?

                    <a
                        href="../index.php"
                        class="fw-semibold text-decoration-none"
                        style="color: #174ea6;"
                    >
                        Entrar
                    </a>
                </p>

            </form>
            
        </div>
    </div>
</main>

<script>
    function mostrarSenha(campoId, iconeId) {
        const campo = document.getElementById(campoId);
        const icone = document.getElementById(iconeId);

        if (campo.type === "password") {
            campo.type = "text";
            icone.classList.replace("bi-eye", "bi-eye-slash");
        } else {
            campo.type = "password";
            icone.classList.replace("bi-eye-slash", "bi-eye");
        }
    }
</script>

<?php
include("footer.php");
?>