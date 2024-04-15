import { Sequelize } from "sequelize-typescript";
import InvoiceModel from "../repository/invoice.model";
import InvoiceFacadeFactory from "../factory/facade.factory";
import InvoiceItems from "../domain/invoice-items.entity";
import Id from "../../@shared/domain/value-object/id.value-object";
import InvoiceItemsModel from "../repository/invoice-items.model";

describe("Invoice facade test", () => {
    let sequelize: Sequelize;

    beforeEach(async () => {
        sequelize = new Sequelize({
        dialect: "sqlite",
        storage: ":memory:",
        logging: false,
        sync: { force: true },
        });

        await sequelize.addModels([InvoiceModel, InvoiceItemsModel]);
        await sequelize.sync();
    });

    afterEach(async () => {
        await sequelize.close();
    });

    it("should generate a invoice", async () => {
        const invoiceFacade = InvoiceFacadeFactory.create();

        const input = {
            name: "Teste",
            document: "Teste",
            street: "Rua teste",
            number: "1",
            complement: "Teste",
            city: "Cidade",
            state: "MG",
            zipCode: "35123",
            items: [
                new InvoiceItems({id: new Id("1"), name: "Produto 1", price:  150}),
                new InvoiceItems({id: new Id("2"), name: "Produto 2", price:  250})
            ]
        };

        const result = await invoiceFacade.generate(input);

        expect(result.id).toBeDefined();
        expect(result.name).toBe(input.name);
        expect(result.street).toBe(input.street);
        expect(result.number).toBe(input.number);
        expect(result.complement).toBe(input.complement);
        expect(result.city).toBe(input.city);
        expect(result.state).toBe(input.state);
        expect(result.zipCode).toBe(input.zipCode);
        expect(result.items).toBe(input.items);
        expect(result.total).toBe(400);
    });

    it("should find a invoice", async () => {
        const invoiceFacade = InvoiceFacadeFactory.create();

        const invoice = {
            name: "Teste",
            document: "Teste",
            street: "Rua teste",
            number: "1",
            complement: "Teste",
            city: "Cidade",
            state: "MG",
            zipCode: "35123",
            items: [
                new InvoiceItems({id: new Id("1"), name: "Produto 1", price:  150}),
                new InvoiceItems({id: new Id("2"), name: "Produto 2", price:  250})
            ]
        };

        const insert = await invoiceFacade.generate(invoice);
        const result = await invoiceFacade.find({id: insert.id});

        expect(result.id).toBe(insert.id);
        expect(result.name).toBe(invoice.name);
        expect(result.document).toBe(invoice.document);
        expect(result.address.street).toBe(invoice.street);
        expect(result.address.number).toBe(invoice.number);
        expect(result.address.complement).toBe(invoice.complement);
        expect(result.address.city).toBe(invoice.city);
        expect(result.address.state).toBe(invoice.state);
        expect(result.address.zipCode).toBe(invoice.zipCode);
        expect(result.items.length).toBe(2);
        expect(result.total).toBe(400);
    });
});