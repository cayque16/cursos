import { Imprimivel } from "../utils/imprimivel.js";
import { Negociacao } from "./negociacao.js";

export class Neogiciacoes implements Imprimivel {
  private negociacoes: Array<Negociacao> = [];

  adiciona(negociacao: Negociacao) {
    this.negociacoes.push(negociacao);
  }

  lista(): ReadonlyArray<Negociacao> {
    return this.negociacoes;
  }

  public paraTexto(): string {
    return JSON.stringify(this.negociacoes, null, 2);
  }
}
