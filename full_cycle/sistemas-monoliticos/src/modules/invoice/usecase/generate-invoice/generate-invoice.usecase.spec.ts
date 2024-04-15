import Address from "../../../@shared/domain/value-object/address";
import Id from "../../../@shared/domain/value-object/id.value-object";
import InvoiceItems from "../../domain/invoice-items.entity";
import Invoice from "../../domain/invoice.entity";
import GenerateInvoiceUseCase from "./generate-invoice.usecase";

const invoice = new Invoice({
    id: new Id("1"),
    name: "Teste",
    document: "Teste",
    address: new Address("Rua teste", "1", "Teste", "Cidade", "MG", "35123"),
    items: [
        new InvoiceItems({id: new Id("1"), name: "Produto 1", price:  150}),
        new InvoiceItems({id: new Id("2"), name: "Produto 2", price:  250})
    ]
});

const MockRepository = () => {
    return {
      save: jest.fn().mockReturnValue(Promise.resolve(invoice)),
      find: jest.fn()
    };
  };

  describe("Generate invoice usecase unit test", () => {
    it("should generate a invoice", async () => {
        const invoiceRepository = MockRepository();
        const usecase = new GenerateInvoiceUseCase(invoiceRepository);

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

        const result = await usecase.execute(input);

        expect(result.id).toBe(invoice.id.id);
        expect(result.name).toBe(invoice.name);
        expect(result.street).toBe(invoice.address.street);
        expect(result.number).toBe(invoice.address.number);
        expect(result.complement).toBe(invoice.address.complement);
        expect(result.city).toBe(invoice.address.city);
        expect(result.state).toBe(invoice.address.state);
        expect(result.zipCode).toBe(invoice.address.zipCode);
        expect(result.items).toBe(invoice.items);
        expect(result.total).toBe(400);
    });
  });