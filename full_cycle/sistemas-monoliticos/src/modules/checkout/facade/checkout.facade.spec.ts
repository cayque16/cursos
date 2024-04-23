import { Sequelize } from "sequelize-typescript";
import OrderModel from "../repository/order.model";
import ClientOrderModel from "../repository/client.model";
import ProductOrderModel from "../repository/product.model";
import OrderItemsModel from "../repository/order-items.model";
import CheckoutFacade from "./checkout.facade";
import PlaceOrderUseCase from "../usecase/place-order/place-order.usecase";

describe("Checkout facade test", () => {
    let sequelize: Sequelize

    beforeEach(async () => {
        sequelize = new Sequelize({
        dialect: 'sqlite',
        storage: ':memory:',
        logging: false,
        sync: { force: true }
        })

        sequelize.addModels([
            OrderModel,
            ClientOrderModel,
            ProductOrderModel,
            OrderItemsModel,
            ClientOrderModel
        ]);
        await sequelize.sync()
    })

    afterEach(async () => {
        await sequelize.close()
    });

    const clientFacadeMock = {
        add: jest.fn(),
        find: jest.fn().mockResolvedValue({
            id: "1",
            name: "string",
            email: "string",
            document: "string",
            street: "string",
            number: "string",
            complement: "string",
            city: "string",
            state: "string",
            zipcode: "string",
            createdAt: new Date(),
            updatedAt: new Date()
        })
    }
    const productFacadeMock = {
        addProduct: jest.fn(),
        checkStock: jest.fn().mockResolvedValue({
            productId: "1",
            stock: 15
        })
    }
    const catalogFacadeMock = {
        find: jest.fn().mockResolvedValue({
            id: "1",
            name: "string",
            description: "string",
            salesPrice: 150
        }),
        findAll: jest.fn().mockResolvedValue(null),
    }
    const invoiceFacadeMock = {
        find: jest.fn(),
        generate: jest.fn().mockResolvedValue({
            id: "string",
            name: "string",
            document: "string",
            street: "string",
            number: "string",
            complement: "string",
            city: "string",
            state: "string",
            zipCode: "string",
            items: [],
            total: 0
        })
    }
    const orderRepository = {
        addOrder: jest.fn(),
        findOrder: jest.fn()
    }

    it("should create a order", async () => {
        //@ts-expect-error - no params in constructor
        const usecase = new PlaceOrderUseCase();

        //@ts-expected-error - force set clientFacade
        usecase["_clientFacade"] = clientFacadeMock;
        //@ts-expected-error - force set productFacade
        usecase["_productFacade"] = productFacadeMock;
        //@ts-expect-error - force set catalogFacade
        usecase["_catalogFacade"] = catalogFacadeMock;
        //@ts-expected-error - force set invoiceFacade
        usecase["_invoiceFacade"] = invoiceFacadeMock;
        //@ts-expected-error - force set repository
        usecase["_repository"] = orderRepository;

        const facade = new CheckoutFacade(usecase);

        
        const result = await facade.execute({
            clientId: "1",
            products: [
            {productId: "1"},
            ]
        });
        
        expect(result.id).toBeDefined();
        expect(clientFacadeMock.find).toHaveBeenCalled();
        expect(productFacadeMock.checkStock).toHaveBeenCalled();
        expect(catalogFacadeMock.find).toHaveBeenCalled();
        expect(invoiceFacadeMock.generate).toHaveBeenCalled();
        expect(orderRepository.addOrder).toHaveBeenCalled();
    });
});