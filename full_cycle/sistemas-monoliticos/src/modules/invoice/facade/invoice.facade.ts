import UseCaseInterface from "../../@shared/usecase/use-case.interface";
import InvoiceFacadeInterface, { FindInvoiceUseCaseInputDTO, FindInvoiceUseCaseOutputDTO, GenerateInvoiceUseCaseInputDto, GenerateInvoiceUseCaseOutputDto } from "./invoice.facade.interface";

export interface UseCasesProps {
    find: UseCaseInterface;
    generate: UseCaseInterface;
}

export default class InvoiceFacade implements InvoiceFacadeInterface {
    private findUsecase: UseCaseInterface;
    private generateUsecase: UseCaseInterface;

    constructor(usecasesProps: UseCasesProps) {
        this.findUsecase = usecasesProps.find;
        this.generateUsecase = usecasesProps.generate;
    }

    find(input: FindInvoiceUseCaseInputDTO): Promise<FindInvoiceUseCaseOutputDTO> {
        return this.findUsecase.execute(input);
    }

    generate(input: GenerateInvoiceUseCaseInputDto): Promise<GenerateInvoiceUseCaseOutputDto> {
        return this.generateUsecase.execute(input);
    }
}