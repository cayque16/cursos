import { Sequelize } from "sequelize-typescript";
import OrderModel from "./order.model";
import ClientModel from "./client.model";
import ProductModel from "./product.model";
import Order from "../domain/order.entity";
import Client from "../domain/client.entity";
import Product from "../domain/product.entity";
import Id from "../../@shared/domain/value-object/id.value-object";
import OrderRepository from "./order.repository";
import OrderItemsModel from "./order-items.model";

describe("OrderRepository test", () => {
    let sequelize: Sequelize;

    beforeEach(async () => {
        sequelize = new Sequelize({
            dialect: "sqlite",
            storage: ":memory:",
            logging: false,
            sync: { force: true}
        });

        await sequelize.addModels([
            ClientModel,
            ProductModel,
            OrderModel,
            OrderItemsModel
        ]);
        await sequelize.sync();
    });

    afterEach(async () => {
        await sequelize.close();
    });

    it("should save a order", async () => {
        const client = new Client({
            name: "Fulano",
            address: "Rua",
            email: "fulano@teste",
        });

        ClientModel.create({
            id: client.id.id,
            name: client.name,
            email: client.email,
            address: client.address
        });

        const order = new Order({
            client: client,
            products: [
                new Product({
                    id: new Id("1"),
                    name: "Produto 1",
                    description: "Teste",
                    salesPrice: 100
                }),
                new Product({
                    id: new Id("2"),
                    name: "Produto 2",
                    description: "Teste",
                    salesPrice: 150
                })
            ],
        });
        
        const repository = new OrderRepository();
        const result = await repository.addOrder(order);
    });

    it("should find a order", async () => {
        const orderRepository = new OrderRepository();

        const client = new Client({
            name: "Fulano",
            address: "Rua",
            email: "fulano@teste",
        });

        ClientModel.create({
            id: client.id.id,
            name: client.name,
            email: client.email,
            address: client.address
        });

        const product1 = new Product({
            id: new Id("1"),
            name: "Produto 1",
            description: "Teste",
            salesPrice: 100
        });
        const product2 = new Product({
            id: new Id("2"),
            name: "Produto 2",
            description: "Teste",
            salesPrice: 150
        });

        ProductModel.create({
            id: product1.id.id,
            name: product1.name,
            description: product1.description,
            salesPrice: product1.salesPrice
        })

        ProductModel.create({
            id: product2.id.id,
            name: product2.name,
            description: product2.description,
            salesPrice: product2.salesPrice
        })

        const order = new Order({
            client: client,
            products: [product1, product2],
        });

        OrderModel.create({
            id: order.id.id,
            idClient: client.id.id,
            status: order.status
        })

        OrderItemsModel.create({
            id: "1",
            idProduct: product1.id.id,
            idOrder: order.id.id
        });

        OrderItemsModel.create({
            id: "2",
            idProduct: product2.id.id,
            idOrder: order.id.id
        });

        const result = await orderRepository.findOrder(order.id.id);

        expect(result.id.id).toBe(order.id.id);
        expect(result.status).toBe(order.status);
        expect(result.client.id.id).toBe(order.client.id.id);
        expect(result.products.length).toBe(order.products.length);
        expect(result.total).toBe(order.total);
    });
});