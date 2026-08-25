function mostrarSenha() {

    const senha = document.getElementById("senha");
    const icone = document.getElementById("iconeSenha");

    if (senha.type === "password") {

        senha.type = "text";

        icone.classList.remove("bi-eye");
        icone.classList.add("bi-eye-slash");

    } else {

        senha.type = "password";

        icone.classList.remove("bi-eye-slash");
        icone.classList.add("bi-eye");

    }

}