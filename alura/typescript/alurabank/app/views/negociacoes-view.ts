import { Neogiciacoes } from "../models/negociacoes.js";

export class NeogiciacoesView {
  private elemento: HTMLElement;

  constructor(seletor: string) {
    this.elemento = document.querySelector(seletor);
  }

  template(model: Neogiciacoes): string {
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
                <td>${new Intl.DateTimeFormat().format(negociacao.data)}</td>
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

  update(model: Neogiciacoes): void {
    this.elemento.innerHTML = this.template(model);
  }
}
