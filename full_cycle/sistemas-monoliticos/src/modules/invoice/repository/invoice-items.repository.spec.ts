import { Sequelize } from "sequelize-typescript";
import InvoiceItemsModel from "./invoice-items.model";
import Id from "../../@shared/domain/value-object/id.value-object";
import InvoiceItems from "../domain/invoice-items.entity";
import InvoiceItemsRepository from "./invoice-items.repository";

describe("InvoiceItemsRepository test", () => {
    let sequelize: Sequelize;

    beforeEach(async () => {
        sequelize = new Sequelize({
        dialect: "sqlite",
        storage: ":memory:",
        logging: false,
        sync: { force: true },
        });

        await sequelize.addModels([InvoiceItemsModel]);
        await sequelize.sync();
    });

    afterEach(async () => {
        await sequelize.close();
    });

    it("should save a invoice item", async () => {
        const invoiceItem = new InvoiceItems({
            id: new Id("1"),
            name: "Produto 1",
            price: 1500
        });

        const repository = new InvoiceItemsRepository();
        const result = await repository.save(invoiceItem);

        expect(result.id.id).toBe(invoiceItem.id.id);
        expect(result.name).toBe(invoiceItem.name);
        expect(result.price).toBe(invoiceItem.price);
    });
});