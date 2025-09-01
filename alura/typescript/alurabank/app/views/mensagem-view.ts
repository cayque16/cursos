import { AbstactView } from "./abstract-view.js";

export class MensagemView extends AbstactView<string> {
  protected template(model: string): string {
    return `
      <p class="alert alert-info">${model}</p>
    `;
  }
}
