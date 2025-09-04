import CalculaSalario from "./CalculaSalario";
import Colaborador from "./Colaborador";

export default class GeraRelatorio {
  constructor(
    private _colaboradores: Colaborador[],
    private servicoCalculaSalario: CalculaSalario
  ) {}

  gerarJSON() {
    let relatorio = this._colaboradores.map((colaborador) => {
      return {
        nome: colaborador.nome,
        cargo: colaborador.cargo,
        salario: this.servicoCalculaSalario.calcular(colaborador.cargo),
      };
    });
    return JSON.stringify(relatorio);
  }
}
