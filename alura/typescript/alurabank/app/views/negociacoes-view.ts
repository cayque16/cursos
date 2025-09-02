import { escapar } from "../decorators/escapar.js";
import { inspect } from "../decorators/inspect.js";
import { logarTempoDeExecucao } from "../decorators/logar-tempo-de-execucao.js";
import { Neogiciacoes } from "../models/negociacoes.js";
import { AbstactView } from "./abstract-view.js";

export class NeogiciacoesView extends AbstactView<Neogiciacoes> {
  @escapar
  protected template(model: Neogiciacoes): string {
    return `
      <table class="table table-hover table-bordered">
        <thead>
          <tr>
            <th>DATA</th>
            <th>QUANTIDADE</th>
            <th>VALOR</th>
          </tr>
        </thead>
        <tbody>
          ${model
            .lista()
            .map((negociacao) => {
              return `
              <tr>
                <td>${this.formatar(negociacao.data)}</td>
                <td>${negociacao.quantidade}</td>
                <td>${negociacao.valor}</td>
              </tr>
            `;
            })
            .join("")}
        </tbody>
      </table>
    `;
  }

  private formatar(data: Date): string {
    return new Intl.DateTimeFormat().format(data);
  }
}
