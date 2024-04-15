import Address from "../../../@shared/domain/value-object/address";
import UseCaseInterface from "../../../@shared/usecase/use-case.interface";
import Invoice from "../../domain/invoice.entity";
import InvoiceGateway from "../../gateway/invoice.gateway";
import { GenerateInvoiceUseCaseInputDto, GenerateInvoiceUseCaseOutputDto } from "./generate-invoice.dto";

export default class GenerateInvoiceUseCase implements UseCaseInterface {
    constructor(private invoiceRepository: InvoiceGateway) {}

    async execute(input: GenerateInvoiceUseCaseInputDto): Promise<GenerateInvoiceUseCaseOutputDto> {
        const address = new Address(
            input.street,
            input.number,
            input.complement,
            input.city,
            input.state,
            input.zipCode,
        );
        const invoice = new Invoice({
            name: input.name,
            document: input.document,
            address: address,
            items: input.items
        });
        
        const result = await this.invoiceRepository.save(invoice);
        const total = result.items.reduce((acumulador, item) => acumulador + item.price, 0);

        return {
            id: result.id.id,
            name: result.name,
            document: result.document,
            street: result.address.street,
            number: result.address.number,
            complement: result.address.complement,
            city: result.address.city,
            state: result.address.state,
            zipCode: result.address.zipCode,
            items: result.items,
            total: total,
        }
    }
}