import { AbstactView } from "./abstract-view.js";

export enum TiposMensagem {
  INFO = "alert-info",
  DANGER = "alert-danger",
}

export class MensagemView extends AbstactView<string> {
  private tipoMensagem: TiposMensagem = TiposMensagem.INFO;

  public setTipo(tipoMensagem: TiposMensagem): void {
    this.tipoMensagem = tipoMensagem;
  }

  protected template(model: string): string {
    return `
      <p class="alert ${this.tipoMensagem}">${model}</p>
    `;
  }
}
