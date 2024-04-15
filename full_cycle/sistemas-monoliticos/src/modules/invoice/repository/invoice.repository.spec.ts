import { Sequelize } from "sequelize-typescript";
import InvoiceModel from "./invoice.model";
import Invoice from "../domain/invoice.entity";
import Id from "../../@shared/domain/value-object/id.value-object";
import Address from "../../@shared/domain/value-object/address";
import InvoiceItems from "../domain/invoice-items.entity";
import InvoiceRepository from "./invoice.repository";
import InvoiceItemsModel from "./invoice-items.model";

describe("InvoiceRepository test", () => {
    let sequelize: Sequelize;

    beforeEach(async () => {
        sequelize = new Sequelize({
            dialect: "sqlite",
            storage: ":memory:",
            logging: false,
            sync: { force: true}
        });

        await sequelize.addModels([InvoiceModel, InvoiceItemsModel]);
        await sequelize.sync();
    });

    afterEach(async () => {
        await sequelize.close();
    });

    it("should save a invoice", async () => {
        const invoice = new Invoice({
            id: new Id("1"),
            name: "Teste",
            document: "Teste",
            address: new Address("Rua teste", "1", "Teste", "Cidade", "MG", "35123"),
            items: [new InvoiceItems({id: new Id("1"), name: "Produto 1", price:  150})]
        });

        const repository = new InvoiceRepository();
        const result = await repository.save(invoice);

        expect(result.id.id).toBe(invoice.id.id);
        expect(result.name).toBe(invoice.name);
        expect(result.document).toBe(invoice.document);
        expect(result.address).toBe(invoice.address);
        expect(result.items).toBe(invoice.items);
    });

    it("should find a invoice", async () => {
        const invoiceRepository = new InvoiceRepository();

        InvoiceModel.create({
            id: "1",
            name: "Teste",
            document: "Teste",
            street: "Rua teste",
            number: "1",
            complement: "Teste",
            city: "Cidade",
            state: "MG",
            zipCode: "35123",
            createdAt: new Date(),
            updatedAt: new Date(),
        });

        InvoiceItemsModel.create({
            id: "1",
            idInvoice: "1",
            name: "Produto 1",
            price: 150
        });

        InvoiceItemsModel.create({
            id: "2",
            idInvoice: "1",
            name: "Produto 3",
            price: 178
        });

        const invoice = await invoiceRepository.find("1");

        expect(invoice.id.id).toEqual("1");
        expect(invoice.name).toEqual("Teste");
        expect(invoice.document).toEqual("Teste");
        expect(invoice.address.street).toEqual("Rua teste");
        expect(invoice.address.number).toEqual("1");
        expect(invoice.address.complement).toEqual("Teste");
        expect(invoice.address.city).toEqual("Cidade");
        expect(invoice.address.state).toEqual("MG");
        expect(invoice.address.zipCode).toEqual("35123");
        expect(invoice.items[0].id.id).toEqual("1");
        expect(invoice.items[0].name).toEqual("Produto 1");
        expect(invoice.items[0].price).toEqual(150);
        expect(invoice.items[1].id.id).toEqual("2");
        expect(invoice.items[1].name).toEqual("Produto 3");
        expect(invoice.items[1].price).toEqual(178);
    });
});