import Id from "../../@shared/domain/value-object/id.value-object";
import InvoiceItems from "../domain/invoice-items.entity";
import InvoiceItemsGateway from "../gateway/invoice-items.gateway";
import InvoiceItemsModel from "./invoice-items.model";

export default class InvoiceItemsRepository implements InvoiceItemsGateway{
    async save(input: InvoiceItems): Promise<InvoiceItems> {
        await InvoiceItemsModel.create({
            id: input.id.id,
            name: input.name,
            price: input.price,
        });

        return new InvoiceItems({
            id: input.id,
            name: input.name,
            price: input.price,
        })
    }
}