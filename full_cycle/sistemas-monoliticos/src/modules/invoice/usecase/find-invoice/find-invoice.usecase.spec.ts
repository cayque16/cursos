import Address from "../../../@shared/domain/value-object/address";
import Id from "../../../@shared/domain/value-object/id.value-object";
import InvoiceItems from "../../domain/invoice-items.entity";
import Invoice from "../../domain/invoice.entity";
import FindInvoiceUseCase from "./find-invoice.usecase";

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

const MockRepositoryInvoice = () => {
    return {
      save: jest.fn(),
      find: jest.fn().mockReturnValue(Promise.resolve(invoice)),
    };
};

describe("FindInvoice usescase unit test", () => {
    it("should get a invoice", async () => {
        const InvoiceRepository = MockRepositoryInvoice();
        const findInvoiceUseCase = new FindInvoiceUseCase(InvoiceRepository);
        const input = {
            id: "1"
        };

        const result = await findInvoiceUseCase.execute(input);

        expect(InvoiceRepository.find).toHaveBeenCalled();
        expect(result.id).toBe("1");
        expect(result.name).toBe(invoice.name);
        expect(result.document).toBe(invoice.document);
        expect(result.address).toBe(invoice.address);
        expect(result.items).toBe(invoice.items);
        expect(result.total).toBe(400);
    });
});