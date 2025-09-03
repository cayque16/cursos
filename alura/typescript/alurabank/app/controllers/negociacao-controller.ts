import { domInjector } from "../decorators/dom-injector.js";
import { inspect } from "../decorators/inspect.js";
import { logarTempoDeExecucao } from "../decorators/logar-tempo-de-execucao.js";
import { DiasDaSemana } from "../enums/dias-da-semana.js";
import { Negociacao } from "../models/negociacao.js";
import { Neogiciacoes } from "../models/negociacoes.js";
import { NegociacoesService } from "../services/negociacoes-service.js";
import { MensagemView, TiposMensagem } from "../views/mensagem-view.js";
import { NeogiciacoesView } from "../views/negociacoes-view.js";

export class NegociacaoController {
  @domInjector("#data")
  private inputData: HTMLInputElement;
  @domInjector("#quantidade")
  private inputQuantidade: HTMLInputElement;
  @domInjector("#valor")
  private inputValor: HTMLInputElement;
  private negociacoes: Neogiciacoes = new Neogiciacoes();
  private negociacoesView = new NeogiciacoesView("#negociacoesView");
  private mensagemView = new MensagemView("#mensagemView");
  private negociacaoService = new NegociacoesService();

  constructor() {
    this.negociacoesView.update(this.negociacoes);
  }

  @inspect
  @logarTempoDeExecucao()
  public adiciona(): void {
    const negociacao = Negociacao.criaDe(
      this.inputData.value,
      this.inputQuantidade.value,
      this.inputValor.value
    );

    if (!this.ehDiaUtil(negociacao.data)) {
      this.mensagemView.setTipo(TiposMensagem.DANGER);
      this.mensagemView.update(
        "Apenas negociações em dias úteis podem ser adicionadas!!!"
      );
      return;
    }

    this.negociacoes.adiciona(negociacao);
    this.limparFormulario();
    this.atualizaView();
  }

  public importaDados(): void {
    this.negociacaoService.obterNegociacoesDoDia().then((negociacoesDeHoje) => {
      for (let negociacao of negociacoesDeHoje) {
        this.negociacoes.adiciona(negociacao);
      }
      this.negociacoesView.update(this.negociacoes);
    });
  }

  private ehDiaUtil(data: Date): boolean {
    return (
      data.getDay() > DiasDaSemana.DOMINGO &&
      data.getDay() < DiasDaSemana.SABADO
    );
  }

  private limparFormulario(): void {
    this.inputData.value = "";
    this.inputQuantidade.value = "";
    this.inputValor.value = "";
    this.inputData.focus();
  }

  private atualizaView(): void {
    this.negociacoesView.update(this.negociacoes);
    this.mensagemView.setTipo(TiposMensagem.INFO);
    this.mensagemView.update("Negociação adicionada com sucesso!!!");
  }
}
