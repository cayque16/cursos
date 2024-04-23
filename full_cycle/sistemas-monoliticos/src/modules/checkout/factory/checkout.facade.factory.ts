import ClientAdmFacade from "../../client-adm/facade/client-adm.facade";
import ClientRepository from "../../client-adm/repository/client.repository";
import AddClientUseCase from "../../client-adm/usecase/add-client/add-client.usecase";
import FindClientUseCase from "../../client-adm/usecase/find-client/find-client.usecase";
import InvoiceFacade from "../../invoice/facade/invoice.facade";
import InvoiceRepository from "../../invoice/repository/invoice.repository";
import FindInvoiceUseCase from "../../invoice/usecase/find-invoice/find-invoice.usecase";
import GenerateInvoiceUseCase from "../../invoice/usecase/generate-invoice/generate-invoice.usecase";
import ProductAdmFacade from "../../product-adm/facade/product-adm.facade";
import ProductRepository from "../../product-adm/repository/product.repository";
import AddProductUseCase from "../../product-adm/usecase/add-product/add-product.usecase";
import CheckStockUseCase from "../../product-adm/usecase/check-stock/check-stock.usecase";
import StoreCatalogFacade from "../../store-catalog/facade/store-catalog.facade";
import StoreCatalogRepository from "../../store-catalog/repository/product.repository";
import FindAllProductsUsecase from "../../store-catalog/usecase/find-all-products/find-all-products.usecase";
import FindProductUseCase from "../../store-catalog/usecase/find-product/find-product.usecase";
import CheckoutFacade from "../facade/checkout.facade";
import OrderRepository from "../repository/order.repository";
import PlaceOrderUseCase from "../usecase/place-order/place-order.usecase";

export default class CheckoutFacadeFactory {
    static create() {
        const clientRepository = new ClientRepository();
        const clientAddUseCase = new AddClientUseCase(clientRepository);
        const clientFindUseCase = new FindClientUseCase(clientRepository);
        const clientFacade = new ClientAdmFacade({
            addUsecase: clientAddUseCase,
            findUsecase: clientFindUseCase
        });

        const productRepository = new ProductRepository();
        const productAddUseCase = new AddProductUseCase(productRepository);
        const productStockUseCase = new CheckStockUseCase(productRepository);
        const productFacade = new ProductAdmFacade({
            addUseCase: productAddUseCase,
            stockUseCase: productStockUseCase
        });

        const catalogRepository = new StoreCatalogRepository();
        const catalogFindUseCase = new FindProductUseCase(catalogRepository);
        const catalogFindAllUseCase = new FindAllProductsUsecase(catalogRepository);
        const catalogFacade = new StoreCatalogFacade({
            findAllUseCase: catalogFindAllUseCase,
            findUseCase: catalogFindUseCase
        });

        const invoiceRepository = new InvoiceRepository();
        const invoiceGenerateUsecase = new GenerateInvoiceUseCase(invoiceRepository);
        const invoiceFindUseCase = new FindInvoiceUseCase(invoiceRepository);
        const invoiceFacade = new InvoiceFacade({
            find: invoiceFindUseCase,
            generate: invoiceGenerateUsecase
        });

        const orderRepository = new OrderRepository();

        const checkoutUseCase = new PlaceOrderUseCase(
            clientFacade,
            productFacade,
            catalogFacade, 
            invoiceFacade,
            orderRepository
        );
        const facade = new CheckoutFacade(checkoutUseCase);

        return facade;
    }
}