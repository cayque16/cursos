import Address from "../../@shared/domain/value-object/address";
import Id from "../../@shared/domain/value-object/id.value-object";
import InvoiceItems from "../domain/invoice-items.entity";
import Invoice from "../domain/invoice.entity";
import InvoiceGateway from "../gateway/invoice.gateway";
import InvoiceItemsModel from "./invoice-items.model";
import InvoiceModel from "./invoice.model";

export default class InvoiceRepository implements InvoiceGateway {
    async save(input: Invoice): Promise<Invoice> {
        await InvoiceModel.create({
            id: input.id.id,
            name: input.name,
            document: input.document,
            street: input.address.street,
            number: input.address.number,
            complement: input.address.complement,
            city: input.address.city,
            state: input.address.state,
            zipCode: input.address.zipCode,
            createdAt: input.createdAt,
            updatedAt: input.updatedAt,
        });

        input.items.forEach(element => {
            InvoiceItemsModel.create({
                id: element.id.id,
                idInvoice: input.id.id,
                name: element.name,
                price: element.price
            });
        });

        return new Invoice({
            id: input.id,
            name: input.name,
            document: input.document,
            address: input.address,
            items: input.items,
            createdAt: input.createdAt,
            updatedAt: input.updatedAt,
        });
    }

    async find(id: string): Promise<Invoice> {
        const invoice = await InvoiceModel.findAll({
            where: {id},
            include: [{
                model: InvoiceItemsModel,
                required: true,
                attributes: ['id', 'idInvoice', 'name', 'price']
            }]
        });

        if (!invoice.length) {
            throw new Error(`Invoice with id ${id} not found`);
        }

        const address = new Address(
            invoice[0].street,
            invoice[0].number,
            invoice[0].complement,
            invoice[0].city,
            invoice[0].state,
            invoice[0].zipCode,
        );

        let items: InvoiceItems[] = [];
        invoice[0].items.forEach(element => {
            items.push(new InvoiceItems({
                id: new Id(element.id),
                name: element.name,
                price: element.price
            }));
        });

        return new Invoice({
            id: new Id(invoice[0].id),
            name: invoice[0].name,
            document: invoice[0].document,
            address: address,
            items: items,
            createdAt: invoice[0].createdAt,
            updatedAt: invoice[0].updatedAt
        });
    }
}