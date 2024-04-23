import { Sequelize } from "sequelize-typescript"
import { Umzug } from "umzug";
import { migrator } from "../../database/migrations/config-migrations/migrator";
import request from "supertest";
import { app } from "../express";
import { ClientModel } from "../../../modules/client-adm/repository/client.model";
import { ProductAdmModel } from "../../../modules/product-adm/repository/product.model";
import ClientOrderModel from "../../../modules/checkout/repository/client.model";
import ProductOrderModel from "../../../modules/checkout/repository/product.model";
import ProductAdmFacadeFactory from "../../../modules/product-adm/factory/facade.factory";
import ProductCatalogModel from "../../../modules/store-catalog/repository/product.model";
import InvoiceModel from "../../../modules/invoice/repository/invoice.model";
import InvoiceItemsModel from "../../../modules/invoice/repository/invoice-items.model";
import OrderModel from "../../../modules/checkout/repository/order.model";
import OrderItemsModel from "../../../modules/checkout/repository/order-items.model";
import ClientAdmFacadeFactory from "../../../modules/client-adm/factory/client-adm.facade.factory";
import Address from "../../../modules/@shared/domain/value-object/address";

describe("API E2E tests", () => {
    let sequelize: Sequelize;
    let migration: Umzug<any>;

    beforeEach(async () => {
        sequelize = new Sequelize({
            dialect: 'sqlite',
            storage: ":memory:",
            logging: false
        })

        sequelize.addModels([
            ClientModel,
            ProductAdmModel, 
            ClientOrderModel,
            ProductOrderModel,
            ProductCatalogModel,
            InvoiceModel,
            InvoiceItemsModel,
            OrderModel,
            OrderItemsModel
        ])
        migration = migrator(sequelize)
        await migration.up()
    })

    afterEach(async () => {
        if (!migration || !sequelize) {
            return 
        }
        migration = migrator(sequelize)
        await migration.down()
        await sequelize.close()
    })

    it("should create a client", async () => {
        const address = {
            street: "Rua tal",
            number: "55",
            complement: "Final da rua",
            city: "Aquela",
            state: "MG",
            zipCode: "1235-152"
        };
        
        const response = await request(app)
            .post("/clients")
            .send({
                name: "Fulano",
                email: "fulado@teste.com",
                document: "1234",
                address: address
            });

        expect(response.status).toBe(200);
    });

    it("should create a product", async () => {
        const response = await request(app)
            .post("/products")
            .send({
                name: "Fritadeira",
                description: "Fritadeira 8L",
                purchasePrice: 800,
                stock: 15
            });

        expect(response.status).toBe(200);
        expect(response.body.id).toBeDefined();
        expect(response.body.name).toBe("Fritadeira");
        expect(response.body.description).toBe("Fritadeira 8L");
        expect(response.body.purchasePrice).toBe(800);
        expect(response.body.stock).toBe(15);
    });

    it("should create a checkout", async () => {
        const clientFacade = ClientAdmFacadeFactory.create();
        const productFacade = ProductAdmFacadeFactory.create();

        const clientDto = {
            id: "1",
            name: "Cliente",
            email: "cliente@teste",
            document: "document",
            address: new Address(
                "Rua tal",
                "55",
                "Final da rua",
                "Aquela",
                "MG",
                "1235-152"
            )
        }
    
        await clientFacade.add(clientDto);
    
        const productDto = {
            id: "47",
            name: "Produto 1",
            description: "Teste",
            purchasePrice: 150,
            stock: 1
        };

        await productFacade.addProduct(productDto);

        const checkoutDto = {
            clientId: clientDto.id,
            products: [
                {productId: productDto.id},
            ]
        }

        const response = await request(app)
            .post("/checkout")
            .send(checkoutDto);

        expect(response.status).toBe(200);
    });
})