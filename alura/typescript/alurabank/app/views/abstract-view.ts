import { inspect } from "../decorators/inspect.js";
import { logarTempoDeExecucao } from "../decorators/logar-tempo-de-execucao.js";

export abstract class AbstactView<T> {
  protected elemento: HTMLElement;
  private escapar = false;

  constructor(seletor: string, escapar?: boolean) {
    const elemento = document.querySelector(seletor);
    if (elemento) {
      this.elemento = elemento as HTMLElement;
    } else {
      throw Error(`Seletor ${seletor} não existe no DOM. Favor verificar.`);
    }
    if (escapar) {
      this.escapar = escapar;
    }
  }

  @logarTempoDeExecucao()
  @inspect
  public update(model: T): void {
    let template = this.template(model);
    if (this.escapar) {
      template = template.replace(/<script>[\s\S]*?<\/script>/, "");
    }
    this.elemento.innerHTML = template;
  }

  protected abstract template(model: T): string;
}
