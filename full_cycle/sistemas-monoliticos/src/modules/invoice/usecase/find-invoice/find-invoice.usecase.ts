import UseCaseInterface from "../../../@shared/usecase/use-case.interface";
import InvoiceGateway from "../../gateway/invoice.gateway";
import { FindInvoiceInputDto, FindInvoiceOutputDto } from "./find-invoice.dto";

export default class FindInvoiceUseCase implements UseCaseInterface {
    constructor(private invoiceRepository: InvoiceGateway) {}

    async execute(input: FindInvoiceInputDto): Promise<FindInvoiceOutputDto> {
        const invoice = await this.invoiceRepository.find(input.id);
        const total = invoice.items.reduce((acumulador, item) => acumulador + item.price, 0);
        
        return {
            id: invoice.id.id,
            name: invoice.name,
            document: invoice.document,
            address: invoice.address,
            items: invoice.items,
            total: total,
            createdAt: invoice.createdAt,
        };
    }
}