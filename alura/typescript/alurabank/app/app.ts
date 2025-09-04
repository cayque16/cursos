import { NegociacaoController } from "./controllers/negociacao-controller.js";

const controller = new NegociacaoController();

const form = document.querySelector(".form");
if (!form) {
  throw Error(
    "Não foi possível inicializar a aplicação. Verifique se o form existe."
  );
}

form.addEventListener("submit", (event) => {
  event.preventDefault();
  controller.adiciona();
});

const botaoImporta = document.querySelector("#botao-importa");
if (!botaoImporta) {
  throw Error("Botão importa não foi encontrado!");
}

botaoImporta.addEventListener("click", () => {
  controller.importaDados();
});
