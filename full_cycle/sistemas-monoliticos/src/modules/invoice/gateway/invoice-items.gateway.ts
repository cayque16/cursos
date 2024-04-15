import InvoiceItems from "../domain/invoice-items.entity";

export default interface InvoiceItemsGateway {
    save(input: InvoiceItems): Promise<InvoiceItems>;
}